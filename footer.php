    </div>
</div>
<script>
$(document).ready(function () {
    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>"; // Get CSRF token from session

    function fetchNotifications() {
    fetch('admin/process/admin_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        credentials: 'include',
        body: new URLSearchParams({ action: 'getNotifications', csrf_token: csrfToken })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Notifications:', data);
        let notificationList = document.getElementById('notification-list');
        let notificationIndicator = document.getElementById('notification-indicator');

        notificationList.innerHTML = ''; // Clear previous notifications

        if (data.success && data.notifications.length > 0) {
            notificationIndicator.classList.remove('d-none'); // Show red dot

            data.notifications.forEach(notification => {
                // Format items list
                let itemsList = '';
                if (notification.items && notification.items.length > 0) {
                    itemsList = `<ul class="mb-0 text-dark" style="font-size: 14px;">` +
                        notification.items.map(item => `<li>${item}</li>`).join('') +
                        `</ul>`;
                }

                let notifItem = document.createElement('div');
                notifItem.className = 'notification-item d-flex justify-content-between align-items-start p-2 bg-light rounded mb-2';
                notifItem.innerHTML = `
                    <div>
                        <h6 class="mb-1 fw-bold text-danger">${notification.title}</h6>
                        <p class="mb-0 text-muted" style="font-size: 14px;">
                            ${notification.message}
                        </p>
                        ${itemsList} <!-- Items list -->
                    </div>
                `;
                notificationList.appendChild(notifItem);
            });
        } else {
            notificationIndicator.classList.add('d-none'); // Hide red dot if no notifications
            notificationList.innerHTML = '<p class="text-center text-muted">No new notifications</p>';
        }
    })
    .catch(error => console.error('Error fetching notifications:', error));
}

    // Fetch notifications on page load and refresh every 30 seconds
    fetchNotifications();
    setInterval(fetchNotifications, 30000);
});
</script>

</body>
</html>