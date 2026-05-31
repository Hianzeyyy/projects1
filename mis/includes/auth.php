<?php
require_once __DIR__ . '/db.php';

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('MIS_SESSION');
        session_start();
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function currentUser() {
    startSession();
    if (!isLoggedIn()) return null;
    $conn = getDB();
    $id = (int)$_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, fullname, email, username, role, avatar, created_at FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

function login($username, $password) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, fullname, username, password, role FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        startSession();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        logActivity($user['id'], 'LOGIN', 'User logged in');
        return ['success' => true, 'user' => $user];
    }
    return ['success' => false, 'message' => 'Invalid username or password.'];
}

function logout() {
    startSession();
    if (isLoggedIn()) {
        logActivity($_SESSION['user_id'], 'LOGOUT', 'User logged out');
    }
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

function register($data) {
    $conn = getDB();
    // Check uniqueness
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $data['username'], $data['email']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Username or email already exists.'];
    }
    $stmt->close();

    $hash = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, 'staff')");
    $stmt->bind_param('ssss', $data['fullname'], $data['email'], $data['username'], $hash);
    if ($stmt->execute()) {
        $new_id = $stmt->insert_id;
        logActivity($new_id, 'REGISTER', 'New user registered');
        $stmt->close();
        return ['success' => true];
    }
    $stmt->close();
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

function generateResetToken($email) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'No account found with that email.'];
    }
    $user = $result->fetch_assoc();
    $stmt->close();

    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
    $stmt->bind_param('ssi', $token, $expires, $user['id']);
    $stmt->execute();
    $stmt->close();
    return ['success' => true, 'token' => $token, 'message' => 'Reset link generated. (Token: ' . $token . ')'];
}

function resetPassword($token, $new_password) {
    $conn = getDB();
    $now = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > ? LIMIT 1");
    $stmt->bind_param('ss', $token, $now);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Invalid or expired reset token.'];
    }
    $user = $result->fetch_assoc();
    $stmt->close();

    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->bind_param('si', $hash, $user['id']);
    $stmt->execute();
    $stmt->close();
    logActivity($user['id'], 'PASSWORD_RESET', 'Password was reset');
    return ['success' => true];
}

function sanitize($val) {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

function getInitials($name) {
    $parts = explode(' ', $name);
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= strtoupper($p[0] ?? '');
    }
    return $initials;
}
?>
