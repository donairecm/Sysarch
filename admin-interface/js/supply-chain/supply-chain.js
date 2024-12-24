// Generate random data for Order Processes
function generateOrderProcesses(count) {
    const types = ["Delivery", "Reorder"];
    const statuses = ["On-process", "In-transit", "Complete"];
    const orderProcessesList = document.querySelector('.order-processes-list');

    // Add header row
    orderProcessesList.innerHTML = `
        <li class="order-processes-header">
            <span class="header-product-id">Product ID</span>
            <span class="header-quantity">Quantity</span>
            <span class="header-type">Type</span>
            <span class="header-status">Status</span>
        </li>
    `;

    // Add data rows
    let html = '';
    for (let i = 0; i < count; i++) {
        const productId = `PID-${Math.floor(1000 + Math.random() * 9000)}`;
        const quantity = Math.floor(1 + Math.random() * 100);
        const type = types[Math.floor(Math.random() * types.length)];
        const status = statuses[Math.floor(Math.random() * statuses.length)];

        html += `
            <li class="order-processes-item">
                <span class="product-id">${productId}</span>
                <span class="quantity">${quantity}</span>
                <span class="type">${type}</span>
                <span class="status">${status}</span>
            </li>
        `;
    }
    orderProcessesList.innerHTML += html;
}

// Generate random data for Order Requests
function generateOrderRequests(count) {
    const types = ["Delivery", "Reorder"];
    const orderRequestsList = document.querySelector('.order-requests-list');

    // Add header row
    orderRequestsList.innerHTML = `
        <li class="order-requests-header">
            <span class="header-product-id">Product ID</span>
            <span class="header-quantity">Quantity</span>
            <span class="header-type">Type</span>
        </li>
    `;

    // Add data rows
    let html = '';
    for (let i = 0; i < count; i++) {
        const productId = `PID-${Math.floor(1000 + Math.random() * 9000)}`;
        const quantity = Math.floor(1 + Math.random() * 100);
        const type = types[Math.floor(Math.random() * types.length)];

        html += `
            <li class="order-requests-item">
                <span class="product-id">${productId}</span>
                <span class="quantity">${quantity}</span>
                <span class="type">${type}</span>
            </li>
        `;
    }
    orderRequestsList.innerHTML += html;
}

// Call the functions to populate data
document.addEventListener('DOMContentLoaded', () => {
    generateOrderProcesses(15); // Adjust the count as needed
    generateOrderRequests(15); // Adjust the count as needed
});
