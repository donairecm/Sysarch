document.addEventListener('DOMContentLoaded', () => {
    const badge = document.querySelector('.badge');
    const notificationsContainer = document.querySelector('.not-empty.all-and-unread');

    // Helper function to log and fetch a script
    const fetchScript = (url) => {
        console.log(`About to fetch ${url}`);
        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch ${url}: ${response.status}`);
                }
                console.log(`${url} fetched successfully.`);
                return response.text(); // Return text for non-JSON responses
            });
    };

    // Fetch Notifications for the Logged-In User
    const fetchNotifications = () => {
        console.log('Starting notification update cycle.');

        // Run dependent PHP scripts first
        Promise.all([
            fetchScript('../php/stock_level_notification.php'),
            fetchScript('../php/check_notification_role.php')
        ])
            .then(() => {
                console.log('All dependent scripts executed successfully.');

                // Fetch notifications
                return fetch('../php/fetch_user_notifications.php');
            })
            .then(response => {
                console.log('Fetch called for fetch_user_notifications.php');
                if (!response.ok) {
                    throw new Error(`Failed to fetch notifications: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetch User Notifications Response:', data);

                const { notifications } = data;

                if (!notifications || notifications.length === 0) {
                    console.log('No notifications found.');
                    document.querySelector('.empty.all-and-unread').classList.remove('hide');
                    notificationsContainer.classList.add('hide');
                    badge.classList.remove('show'); // Hide badge if no notifications are found
                    return;
                }

                // Hide empty message and show notifications container
                document.querySelector('.empty.all-and-unread').classList.add('hide');
                notificationsContainer.classList.remove('hide');

                notificationsContainer.innerHTML = ''; // Clear existing notifications
                const dateFormatter = new Intl.DateTimeFormat('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true,
                });

                let hasUnread = false;

                notifications.forEach(({ id, message, read, created_on }) => {
                    const formattedDate = dateFormatter.format(new Date(created_on));
                    const notificationElement = document.createElement('div');
                    notificationElement.classList.add('notification-content');
                    if (read) {
                        notificationElement.classList.add('read');
                    } else {
                        hasUnread = true;
                    }
                    notificationElement.innerHTML = `
                        <div class="notif-message" data-id="${id}">${message}</div>
                        <div class="notif-message-date">${formattedDate}</div>
                    `;
                    notificationsContainer.appendChild(notificationElement);

                    // Mark as read when clicked
                    notificationElement.addEventListener('click', () => {
                        markAsRead(id, notificationElement);
                    });
                });

                // Show or hide the badge based on unread notifications
                if (hasUnread) {
                    badge.classList.add('show');
                } else {
                    badge.classList.remove('show');
                }
            })
            .catch(error => console.error('Error in notification update cycle:', error));
    };

    // Mark a Notification as Read
    const markAsRead = (notificationId, notificationElement) => {
        console.log(`About to update notification ${notificationId} as read`);
        fetch('../php/update_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `notificationId=${notificationId}`,
        })
            .then(response => {
                console.log('Fetch called for update_notification_read.php');
                if (!response.ok) {
                    throw new Error('Failed to update notification');
                }
                return response.json();
            })
            .then(data => {
                console.log(`Update Notification Read Response for ID ${notificationId}:`, data);
                if (data.success) {
                    notificationElement.classList.add('read'); // Add 'read' class
                    fetchNotifications(); // Refresh notifications after marking as read
                }
            })
            .catch(error => console.error('Error updating notification:', error));
    };

    // Fetch notifications on load and every 5 seconds
    fetchNotifications();
   
});
