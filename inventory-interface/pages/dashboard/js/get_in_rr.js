// Utility to format dates as "Jan 21, 2020"
function formatDate(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

// Function to populate the modal with details
function populateModal(activity) {
    const modal = document.querySelector(".modal-style.modal-view-delete-reorder-detail");
    const fields = modal.querySelectorAll("[data-field]");

    console.log("Logged in Employee ID:", employeeID); // Log the logged-in employee ID
    console.log(activity); // Log the activity object for debugging

    fields.forEach(field => {
        const fieldName = field.getAttribute("data-field");
        if (activity[fieldName]) {
            console.log(`${fieldName}: ${activity[fieldName]}`); // Log each field value

            if (fieldName === "requested_by") {
                console.log(`Comparing requested_by: ${activity[fieldName]} with logged in Employee ID: ${employeeID}`);
                if (activity[fieldName] === employeeID && activity.status === "pending") {
                    field.textContent = "You";
                    document.querySelector(".save").classList.remove("hide"); // Remove the hide class
                } else {
                    field.textContent = activity[fieldName];
                    // Ensure the save button remains hidden if conditions aren't met
                    document.querySelector(".save").classList.add("hide");
                }
            } else {
                field.textContent = fieldName === "date_of_request"
                    ? formatDate(activity[fieldName]) // Format date fields
                    : activity[fieldName];
            }
        } else {
            console.log(`${fieldName}: N/A`); // Log missing fields
            field.textContent = "N/A"; // Fallback for missing data
        }
    });

    // Show the modal
    modal.classList.add("show");
}

// Function to handle closing the modal when clicking outside content
function setupModalClose() {
    const modal = document.querySelector(".modal-style.modal-view-delete-reorder-detail");

    modal.addEventListener("click", (event) => {
        const isClickInside = modal.querySelector(".modal-content").contains(event.target);

        if (!isClickInside) {
            modal.classList.remove("show"); // Close the modal
        }
    });
}

// Function to populate the inventory activities list
function populateInventoryActivities(data) {
    const inventoryList = document.querySelector(".inventory-activities-list");
    inventoryList.querySelectorAll(".inventory-activities-item").forEach(item => item.remove()); // Clear previous rows

    data.forEach(activity => {
        const listItem = document.createElement("li");
        listItem.classList.add("inventory-activities-item");

        listItem.innerHTML = `
            <span class="request-id">${activity.request_id}</span>
            <span class="product-id">${activity.product_id}</span>
            <span class="quantity">${activity.quantity}</span>
            <span class="status">${activity.status}</span>
            <span class="activity-date">${formatDate(activity.date_of_request)}</span>
        `;

        // Attach click event to list item
        listItem.addEventListener("click", () => populateModal(activity));

        inventoryList.appendChild(listItem);
    });
}

// Function to fetch inventory data from the server
function fetchInventoryData() {
    fetch('db_queries/fetch_in_rr.php') // Adjust the path to your PHP script
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching inventory activities:', data.error);
                return;
            }

            // Sort data by date_of_request (descending order)
            data.sort((a, b) => new Date(b.date_of_request) - new Date(a.date_of_request));

            populateInventoryActivities(data);
        })
        .catch(error => console.error('Error:', error));
}

// Fetch and populate data on page load
document.addEventListener("DOMContentLoaded", () => {
    console.log("Employee ID available for use:", employeeID);

    // Fetch inventory data on page load
    fetchInventoryData();

    // Refresh data every 5 seconds
    setInterval(fetchInventoryData, 5000);

    // Set up modal close behavior
    setupModalClose();
});
