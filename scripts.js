document.addEventListener('DOMContentLoaded', () => {
    // Real-time input validation
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => {
            if (input.checkValidity()) {
                input.style.borderColor = '#e0e0e0';
            } else {
                input.style.borderColor = '#e74c3c';
            }
        });
    });

    // Confirm before actions
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this task?')) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('.complete-btn:not(.completed)').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Mark this task as completed?')) {
                e.preventDefault();
            }
        });
    });

    // Prevent form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});
