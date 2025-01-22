document.addEventListener('DOMContentLoaded', () => {
    const badge = document.querySelector('.badge');
    const countDiv = badge.querySelector('.count');
    const notificationsContainer = document.querySelector('.not-empty.all-and-unread');
    const notificationIcon = document.querySelector('.ic-container'); // Replace with your specific icon selector

    // Fetch Notifications for the Logged-In User
    const fetchNotifications = () => {
        fetch('../php/fetch_user_notifications.php') // PHP script to fetch notifications
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch notifications');
                }
                return response.json();
            })
            .then(data => {
                const { notifications, badgeCount } = data;

                // Handle badge count
                if (badgeCount > 0) {
                    badge.classList.add('show');
                    countDiv.textContent = badgeCount;
                } else {
                    badge.classList.remove('show');
                    countDiv.textContent = '';
                }

                // Display notifications
                notificationsContainer.innerHTML = ''; // Clear existing notifications
                const dateFormatter = new Intl.DateTimeFormat('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true,
                });

                notifications.forEach(({ message, created_on }) => {
                    const formattedDate = dateFormatter.format(new Date(created_on));
                    const notificationElement = document.createElement('div');
                    notificationElement.classList.add('notification-content');
                    notificationElement.innerHTML = `
                        <div class="notif-message">${message}</div>
                        <div class="notif-message-date">${formattedDate}</div>
                    `;
                    notificationsContainer.appendChild(notificationElement);
                });
            })
            .catch(error => console.error('Error fetching notifications:', error));
    };

    // Sequentially Fetch Notifications
    fetchNotifications();

    // Remove badge when the notification icon is clicked
    notificationIcon.addEventListener('click', () => {
        badge.classList.remove('show');
        countDiv.textContent = '';
    });
});
