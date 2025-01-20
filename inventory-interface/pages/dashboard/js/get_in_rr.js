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
    console.log("Activity:", activity); // Log the activity object for debugging

    fields.forEach(field => {
        const fieldName = field.getAttribute("data-field");
        if (activity[fieldName]) {
            console.log(`${fieldName}: ${activity[fieldName]}`); // Log each field value

            if (fieldName === "status" && activity[fieldName].trim().toLowerCase() === "cancelled") {
                const loggedInID = String(employeeID).trim();
                const requestedByID = String(activity.requested_by).trim();

                // Display "Cancelled by You" or "Cancelled by [requested_by]"
                field.textContent = requestedByID === loggedInID
                    ? "Cancelled by You"
                    : `Cancelled by ${requestedByID}`;
            } else if (fieldName === "requested_by") {
                const requestedByID = String(activity[fieldName]).trim();
                const loggedInID = String(employeeID).trim();

                console.log(`Comparing requested_by (${requestedByID}) with logged-in Employee ID (${loggedInID})`);

                // Display "You" if requested_by matches logged-in user
                field.textContent = requestedByID === loggedInID
                    ? "You"
                    : activity[fieldName];

                // Show or hide the save button based on the requested_by match
                if (requestedByID === loggedInID) {
                    document.querySelector(".save").classList.remove("hide"); // Remove the hide class
                } else {
                    document.querySelector(".save").classList.add("hide"); // Add the hide class
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

            // Remove the prefix from request_id, sort numerically in descending order, and re-add the prefix
            data.sort((a, b) => {
                const idA = parseInt(a.request_id.replace('RRD-', ''), 10);
                const idB = parseInt(b.request_id.replace('RRD-', ''), 10);
                return idB - idA; // Descending order
            });

            populateInventoryActivities(data);
        })
        .catch(error => console.error('Error:', error));
}



// Function to show the confirmation modal for cancellation
function showConfirmationModal(activity) {
    const modal = document.getElementById("confirmationModal4");
    const detailsList = modal.querySelector("#cancelreorderrequest");

    // Clear any existing details
    detailsList.innerHTML = "";

    // Populate the modal with activity details
    const details = [
        { label: "Request ID", value: activity.request_id },
        { label: "Product ID", value: activity.product_id },
        { label: "Product Name", value: activity.product_name },
        { label: "Requested Quantity", value: activity.quantity }
    ];

    details.forEach(detail => {
        const li = document.createElement("li");
        li.textContent = `${detail.label}: ${detail.value}`;
        detailsList.appendChild(li);
    });

    // Show the modal
    modal.style.display = "flex";

    // Handle Confirm button
    document.getElementById("confirmcancellation").onclick = async () => {
        try {
            const response = await fetch('db_queries/cancel_rr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    request_id: activity.request_id,
                    product_id: activity.product_id
                })
            });

            const result = await response.json();

            if (result.success) {
                alert("Reorder request cancelled successfully!");

                // Refresh data and close modals
                fetchInventoryData();
                modal.style.display = "none";
                document.querySelector(".modal-style.modal-view-delete-reorder-detail").classList.remove("show");
            } else {
                alert(`Error cancelling reorder request: ${result.error}`);
            }
        } catch (error) {
            console.error("Error cancelling reorder request:", error);
            alert("An error occurred. Please try again.");
        }
    };

    // Handle Cancel button
    document.getElementById("cancelcancellation").onclick = () => {
        modal.style.display = "none";
    };

    // Close modal when clicking outside or pressing Escape
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            modal.style.display = "none";
        }
    });
}

// Add event listener for Delete Request button
document.addEventListener("DOMContentLoaded", () => {
    const deleteRequestButton = document.getElementById("deleterequestButton");

    deleteRequestButton.addEventListener("click", (event) => {
        event.preventDefault();

        const modal = document.querySelector(".modal-style.modal-view-delete-reorder-detail");
        const fields = modal.querySelectorAll("[data-field]");

        const activity = {};
        fields.forEach(field => {
            const fieldName = field.getAttribute("data-field");
            if (fieldName) {
                activity[fieldName] = field.textContent.trim();
            }
        });

        showConfirmationModal(activity);
    });

    // Fetch and populate data on page load
    fetchInventoryData();

    // Refresh data periodically
    setInterval(fetchInventoryData, 5000);

    // Set up modal close behavior
    setupModalClose();
});
