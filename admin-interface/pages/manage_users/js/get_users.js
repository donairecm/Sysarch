document.addEventListener("DOMContentLoaded", () => {
    const usersContainer = document.querySelector(".manage-users-table-container");
    // Function to format date
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };
        const date = new Date(dateString);
        return date.toLocaleString('en-US', options);
    }

    // Fetch user activities data
    fetch("db_queries/fetch_users.php")
        .then(response => response.json())
        .then(data => {
            if (data.uaactivities) {
                // Sort the activities by date in descending order
                data.uaactivities.sort((a, b) => new Date(b.date_of_activity) - new Date(a.date_of_activity));

                data.uaactivities.forEach(activity => {
                    // Create a new list item for each activity
                    const activityItem = document.createElement("div");
                    activityItem.classList.add("manage-users-item", "manage-users-alignment");

                    // Format the date using formatDate function
                    const formattedDate = formatDate(activity.date_of_activity);

                    // Populate the fields dynamically
                    activityItem.innerHTML = `
                        <span class="employee-id">${activity.performed_by}</span>
                        <span class="name">${activity.activity_type}</span>
                        <span class="email">${activity.details}</span>
                        <span class="phone-number-1">${formattedDate}</span>
                    `;

                    // Add click listener to open modal
                    activityItem.addEventListener("click", () => {
                        modalEmployeeId.textContent = activity.performed_by; // Set performed_by in modal
                        modal.classList.add("show");
                        modalOverlay.classList.add("show");
                    });

                    // Append the activity item to the container
                    usersContainer.appendChild(activityItem);
                });
            } else {
                // Handle errors or empty data
                console.error("No activities found");
            }
        })
        .catch(error => console.error("Error fetching activity data:", error));
});
