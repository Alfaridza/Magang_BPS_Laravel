// Auto-close flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    // Select all flash message elements (success and error alerts)
    const alerts = document.querySelectorAll('div.bg-green-100, div.bg-red-100, div.bg-green-50, div.bg-red-50');

    alerts.forEach(function (alert) {
        // Add close button functionality
        const closeBtn = alert.querySelector('.flash-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeAlert(alert);
            });
        }

        // Set timeout to auto-close after 5 seconds
        setTimeout(function () {
            closeAlert(alert);
        }, 5000); // 5000ms = 5 seconds
    });

    // Function to close alert with fade effect
    function closeAlert(alert) {
        if (alert.style.display === 'none') return; // Already closed

        // Add fade out transition
        alert.style.transition = 'opacity 0.5s ease-out';
        alert.style.opacity = '0';

        // Remove element after fade out
        setTimeout(function () {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 500);
    }
});