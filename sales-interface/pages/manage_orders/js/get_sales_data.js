async function fetchAndDisplaySalesData() {
    try {
        // Fetch sales data from the server
        const response = await fetch("db_queries/fetch_sales_data.php"); // Replace with your API endpoint
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const salesData = await response.json(); // Assume the data is an array of sales objects

        // Reference to the sales table container
        const salesTable = document.querySelector(".sales-manage-orders-table");

        // Clear existing rows except the header
        salesTable.querySelectorAll(".sales-manage-orders-table-item").forEach(item => item.remove());

        // Loop through sales data and populate rows
        salesData.forEach(sale => {
            const orderItemCount = sale.order_items ? sale.order_items.length : 0; // Count order items

            const listItem = document.createElement("li");
            listItem.classList.add("sales-manage-orders-table-item", "sales");

            listItem.innerHTML = `
                <span class="sales-order-id">${sale.sales_order_id}</span>
                <span class="order-item-count">${orderItemCount}</span>
                <span class="hd-managed-by">${sale.managed_by}</span>
                <span class="total-amount">${sale.total_amount}</span>
                <span class="payment-method">${sale.payment_method}</span>
                <span class="created-on">${sale.created_on}</span>
            `;

            salesTable.appendChild(listItem);
        });
    } catch (error) {
        console.error("Error fetching sales data:", error);
    }
}

// Call the function to fetch and display data
fetchAndDisplaySalesData();
