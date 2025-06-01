// Example usage of SweetAlert
function showSuccess(message = 'Operation successful') {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        confirmButtonColor: '#198754',
    });
}

function showError(message = 'Something went wrong') {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#dc3545',
    });
}

// Confirm delete action
function confirmDelete(callback) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === "function") {
            callback();
        }
    });
}

// Example usage: confirmDelete(() => window.location.href = 'delete_user.php?id=3');
// main.js

document.addEventListener("DOMContentLoaded", () => {
    // Confirm delete action
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
});
