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
    const modal = document.querySelector("#order-details-modal");

    // Populate the order details on the left
    document.querySelector("#sales-order-id").innerText = `Order ID: ${sale.sales_order_id}`;
    document.querySelector("#managed-by").innerText = `Managed By: ${sale.managed_by}`;
    document.querySelector("#total-amount").innerText = `Total Amount: ${sale.total_amount}`;
    document.querySelector("#status").innerText = `Status: ${sale.status}`;
    document.querySelector("#created-on").innerText = `Created On: ${sale.created_on}`;

    // Populate the order items table on the right
    const orderItemsBody = document.querySelector("#order-items-body");
    orderItemsBody.innerHTML = ""; // Clear previous items

    if (sale.order_items.length > 0) {
        sale.order_items.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>₱${item.total_price}</td>
            `;
            orderItemsBody.appendChild(row);
        });
    } else {
        // Show a message if no order items are found
        const row = document.createElement("tr");
        row.innerHTML = `<td colspan="3" style="text-align:center;">No items found for this order.</td>`;
        orderItemsBody.appendChild(row);
    }

    // Show the modal
    modal.style.display = "block";
}

// Close modal when clicking outside the content
const modal = document.querySelector("#order-details-modal");
modal.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Close modal when clicking the close button
document.querySelector("#order-details-modal .close").addEventListener("click", () => {
    modal.style.display = "none";
});


// Close modal when clicking the close button
document.querySelector("#order-details-modal .close").addEventListener("click", () => {
    modal.style.display = "none";
});

// Fetch and display sales data every 5 seconds
fetchAndDisplaySalesData();
setInterval(fetchAndDisplaySalesData, 5000);
