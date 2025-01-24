document.addEventListener("DOMContentLoaded", () => {
    const usersContainer = document.querySelector(".manage-users-table-container");
    const modal = document.querySelector(".modal");
    const modalOverlay = document.querySelector(".modal-overlay");
    const modalEmployeeId = document.getElementById("modal-employee-id");
    const modalClose = document.getElementById("modal-close");

    // Fetch user activities data
    fetch("db_queries/fetch_users.php")
        .then(response => response.json())
        .then(data => {
            if (data.uaactivities) {
                data.uaactivities.forEach(activity => {
                    // Create a new list item for each activity
                    const activityItem = document.createElement("div");
                    activityItem.classList.add("manage-users-item", "manage-users-alignment");

                    // Populate the fields dynamically
                    activityItem.innerHTML = `
                        <span class="employee-id">${activity.performed_by}</span>
                        <span class="name">${activity.activity_type}</span>
                        <span class="email">${activity.details}</span>
                        <span class="phone-number-1">${activity.date_of_activity}</span>
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

    // Close modal when overlay or close button is clicked
    modalOverlay.addEventListener("click", () => {
        modal.classList.remove("show");
        modalOverlay.classList.remove("show");
    });

    modalClose.addEventListener("click", () => {
        modal.classList.remove("show");
        modalOverlay.classList.remove("show");
    });
});
