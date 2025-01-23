// Fetch and display sales data
async function fetchAndDisplaySalesData() {
    try {
        const response = await fetch("db_queries/fetch_sales_data.php"); // Replace with your API endpoint
        const salesData = await response.json(); // Assume the data is an array of sales objects

        console.log("Fetched sales data:", salesData);

        const salesTable = document.querySelector(".sales-manage-orders-table");
        salesTable.querySelectorAll(".sales-manage-orders-table-item").forEach(item => item.remove());

        // Loop through sales data and populate rows
        salesData.forEach(sale => {
            const orderItemCount = sale.order_items ? sale.order_items.length : 0;

            const listItem = document.createElement("li");
            listItem.classList.add("sales-manage-orders-table-item", "sales");

            listItem.innerHTML = `
                <span class="sales-order-id">${sale.sales_order_id}</span>
                <span class="order-item-count">${orderItemCount}</span>
                <span class="hd-managed-by">${sale.managed_by}</span>
                <span class="total-amount">${sale.total_amount}</span>
                <span class="sales-item-status">${sale.status}</span>
                <span class="created-on">${sale.created_on}</span>
            `;

            // Add click event to show order items in a modal
            listItem.addEventListener("click", () => showOrderDetailsModal(sale));

            salesTable.appendChild(listItem);
        });
    } catch (error) {
        console.error("Error fetching sales data:", error);
    }
}

// Show order items in a modal
function showOrderDetailsModal(sale) {
    const modal = document.querySelector("#order-details-modal");
    const modalContent = modal.querySelector(".modal-content");

    // Clear previous content
    modalContent.innerHTML = `
        <h3>Order Details: ${sale.sales_order_id}</h3>
        <p>Managed By: ${sale.managed_by}</p>
        <p>Total Amount: ${sale.total_amount}</p>
        <p>Status: ${sale.status}</p>
        <p>Created On: ${sale.created_on}</p>
        <h4>Order Items (${sale.order_items.length}):</h4>
        <ul class="order-items-list">
            ${
                sale.order_items.length > 0
                    ? sale.order_items
                          .map(
                              item => `
                <li>
                    <span>Product: ${item.product_name} (ID: ${item.product_id})</span>
                    <span>Quantity: ${item.quantity}</span>
                    <span>Price: ₱${item.total_price}</span>
                </li>
            `
                          )
                          .join("")
                    : "<li>No items found for this order.</li>"
            }
        </ul>
    `;

    // Show modal
    modal.style.display = "block";
}

// Close modal functionality
document.querySelector("#order-details-modal .close").addEventListener("click", () => {
    document.querySelector("#order-details-modal").style.display = "none";
});

// Fetch and display sales data every 5 seconds
fetchAndDisplaySalesData();
setInterval(fetchAndDisplaySalesData, 5000);
