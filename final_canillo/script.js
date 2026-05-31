// Validates that fields are not empty before PHP takes over
function validateForm() {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;

    if (title === "") {
        alert("Please enter a project title.");
        return false;
    }

    if (category === "") {
        alert("Please select a category from the dropdown.");
        return false;
    }

    return true;
}

// Interactivity: Asks user to confirm before deletion
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this project? This action cannot be undone.")) {
        // Redirects to the delete logic file
        window.location.href = "process_delete.php?id=" + id;
    }
}