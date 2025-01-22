// Function to populate the supply chain orders table
function populateSupplyChainOrders(data) {
    const ordersContainer = document.querySelector(".supply-chain-orders");

    // Clear existing rows except the header
    ordersContainer.querySelectorAll(".supply-chain-orders-item").forEach(item => item.remove());

    // Filter out orders with status "completed" or "cancelled"
    const filteredData = data.filter(order => order.status !== 'completed' && order.status !== 'cancelled');

    // Sort data by reverse order of sc_order_id
    filteredData.sort((a, b) => {
        // Extract numeric part of sc_order_id for comparison
        const idA = parseInt(a.sc_order_id.replace('SCO-', ''), 10);
        const idB = parseInt(b.sc_order_id.replace('SCO-', ''), 10);
        return idB - idA; // Sort in descending order
    });

    filteredData.forEach(order => {
        const listItem = document.createElement("li");
        listItem.classList.add("supply-chain-orders-item", "products-filter", "2", "active");

        listItem.innerHTML = `
            <span class="order-id">${order.sc_order_id}</span>
            <span class="type">${order.source}</span>
            <span class="status">${order.status}</span>
            <span class="handled-by">${order.handled_by}</span>
        `;

        // Add click listener if needed for interaction with orders
        listItem.addEventListener("click", () => {
            console.log(`Order ID: ${order.sc_order_id} clicked.`);
            // Additional logic for modals or actions can be added here
        });

        ordersContainer.appendChild(listItem);
    });
}

// Function to fetch and render supply chain orders
async function fetchAndRenderOrders() {
    try {
        const response = await fetch('db_queries/fetch_sco.php'); // PHP script fetching supply chain orders
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        populateSupplyChainOrders(data);
    } catch (error) {
        console.error('Error fetching supply chain orders:', error);
    }
}

// Fetch and populate on page load
fetchAndRenderOrders();
setInterval(fetchAndRenderOrders, 5000);