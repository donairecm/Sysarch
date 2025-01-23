// Fetch and display sales data
async function fetchAndDisplaySalesData() {
    try {
        const response = await fetch("db_queries/fetch_sales_data.php");
        const salesData = await response.json();

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

function showOrderDetailsModal(sale) {
    // Get modal and necessary sections
    const modal = document.querySelector(".modal-order-items-attached2");
    const orderDetailsSection = modal.querySelector(".salesadditem2");
    const orderItemsContainer = modal.querySelector(".orderlist-container");

    // Populate order details
    orderDetailsSection.innerHTML = `
        <div class="prod-m sales2">
            <span>${sale.sales_order_id}</span>
        </div>
        <div class="">
            <span>Managed by</span>
            <span>${sale.managed_by}</span>
        </div>
        <div class="">
            <span>Total Amount</span>
            <span>${sale.total_amount}</span>
        </div>
        <div class="">
            <span>Status</span>
            <span>${sale.status}</span>
        </div>
        <div class="">
            <span class="last">${sale.created_on}</span>
        </div>
    `;

    // Populate order items
    orderItemsContainer.innerHTML = `
        <li class="header">
            <span>Product ID</span>
            <span>Product name</span>
            <span>Quantity</span>
            <span>Price</span>
        </li>
    `;

    if (sale.order_items.length > 0) {
        sale.order_items.forEach(item => {
            const orderItemHTML = `
                <li class="item">
                    <span>${item.product_id}</span>
                    <span>${item.product_name}</span>
                    <span>${item.quantity}</span>
                    <span>₱${item.total_price}</span>
                </li>
            `;
            orderItemsContainer.innerHTML += orderItemHTML;
        });
    } else {
        // Display message if no items found
        const emptyMessageHTML = `
            <li class="item">
                <span colspan="4" style="text-align: center;">No items found for this order.</span>
            </li>
        `;
        orderItemsContainer.innerHTML += emptyMessageHTML;
    }

    // Show modal
    modal.classList.add("show");
}

// Close modal by removing the "show" class when clicking outside the modal content
const modal = document.querySelector(".modal-order-items-attached2");
modal.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.classList.remove("show");
    }
});


// Fetch and display sales data every 5 seconds
fetchAndDisplaySalesData();
setInterval(fetchAndDisplaySalesData, 5000);
