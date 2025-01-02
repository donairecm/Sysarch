// Inventory Activities
// JavaScript to populate Inventory Activities dynamically with random data

// Utility to generate random integers within a range
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Utility to format dates as "Dec 21, 2023 3:00am"
function formatDate(date) {
    const options = { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
    return date.toLocaleString('en-US', options);
}

// Example random data generation
function generateRandomData() {
    const employees = ["John Doe", "Jane Smith", "Emily Davis", "Michael Brown", "Sarah Johnson"];
    const activities = ["Reorder Request", "Delivered", "Received", "Moved", "Inspected"];
    const data = [];
    const numEntries = getRandomInt(16, 28);

    for (let i = 0; i < numEntries; i++) {
        const employee = employees[getRandomInt(0, employees.length - 1)];
        const productId = `P-${getRandomInt(1000, 9999)}`;
        const quantity = getRandomInt(1, 500);
        const activity = activities[getRandomInt(0, activities.length - 1)];
        const date = new Date(Date.now() - getRandomInt(0, 30) * 24 * 60 * 60 * 1000); // Random date within the last 30 days

        data.push({
            employee,
            productId,
            quantity,
            activity,
            date: formatDate(date)
        });
    }

    return data;
}

// Function to populate the inventory activities list
function populateInventoryActivities(data) {
    const inventoryList = document.querySelector(".inventory-activities-list");

    data.forEach(activity => {
        const listItem = document.createElement("li");
        listItem.classList.add("inventory-activities-item");

        listItem.innerHTML = `
            <span class="employee">${activity.employee}</span>
            <span class="product-id">${activity.productId}</span>
            <span class="quantity">${activity.quantity}</span>
            <span class="activity">${activity.activity}</span>
            <span class="date">${activity.date}</span>
        `;

        inventoryList.appendChild(listItem);
    });
}

// Generate and populate data on page load
document.addEventListener("DOMContentLoaded", () => {
    const randomData = generateRandomData();
    populateInventoryActivities(randomData);
});
