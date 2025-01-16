// Utility to format dates as "3:00 AM"
function formatTime(date) {
    const options = { hour: 'numeric', minute: 'numeric', hour12: true };
    return new Date(date).toLocaleTimeString('en-US', options);
}

// Function to populate the inventory activities list
function populateInventoryActivities(data) {
    const inventoryList = document.querySelector(".inventory-activities-list");
    inventoryList.querySelectorAll(".inventory-activities-item").forEach(item => item.remove()); // Clear previous rows

    data.forEach(activity => {
        const listItem = document.createElement("li");
        listItem.classList.add("inventory-activities-item");

        listItem.innerHTML = `
            <span class="movement-id">${activity.movement_id}</span>
            <span class="product-id">${activity.product_id}</span>
            <span class="quantity">${activity.quantity}</span>
            <span class="activity">${activity.activity}</span>
            <span class="date">${formatTime(activity.date_of_movement)}</span>
        `;

        inventoryList.appendChild(listItem);
    });
}

// Function to fetch inventory data from the server
function fetchInventoryData() {
    fetch('db_queries/fetch_in_mv.php') // Adjust the path to your PHP script
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching inventory activities:', data.error);
                return;
            }

            // Sort data by movement_id (ascending order)
            data.sort((a, b) => a.movement_id - b.movement_id);

            populateInventoryActivities(data);
        })
        .catch(error => console.error('Error:', error));
}

// Fetch and populate data on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchInventoryData();
});
