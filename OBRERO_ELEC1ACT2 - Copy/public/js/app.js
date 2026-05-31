// Splash screen redirect logic
// (No import statements for browser version)
document.addEventListener('DOMContentLoaded', function() {
    const splash = document.getElementById('splash-card');
    if (splash) {
        setTimeout(function() {
            const isAuth = splash.getAttribute('data-auth') === '1';
            window.location.href = isAuth ? '/dashboard' : '/login';
        }, 3000);
    }
});