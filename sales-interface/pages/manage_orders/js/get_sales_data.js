let loggedInEmployeeId = null; // Global variable to store the logged-in employee ID

// Fetch the logged-in employee ID
async function getLoggedInEmployeeId() {
    try {
        const response = await fetch("../php/get_logged_in_user.php");
        const data = await response.json();

        if (data.success) {
            // Format the employee ID with the prefix "SSM-" and zero-padding
            const rawEmployeeId = parseInt(data.employee_id, 10); // Convert to integer
            loggedInEmployeeId = `SSM-${rawEmployeeId.toString().padStart(3, "0")}`; // Add prefix and pad with zeros

            console.log("Logged-in Employee ID:", loggedInEmployeeId);
        } else {
            console.error("Error fetching logged-in user:", data.error);
        }
    } catch (error) {
        console.error("Failed to fetch logged-in user:", error);
    }
}

// Fetch and display sales data
async function fetchAndDisplaySalesData() {
    // Ensure employee ID is fetched before proceeding
    if (!loggedInEmployeeId) await getLoggedInEmployeeId();

    try {
        const response = await fetch("db_queries/fetch_sales_data.php");
        const salesData = await response.json();

        console.log("Fetched sales data:", salesData);

        const salesTable = document.querySelector(".sales-manage-orders-table");
        salesTable.querySelectorAll(".sales-manage-orders-table-item").forEach(item => item.remove());

        // Loop through sales data and populate rows
        salesData.forEach(sale => {
            const orderItemCount = sale.order_items ? sale.order_items.length : 0;

            // Replace `managed_by` with "You" if it matches the logged-in employee ID
            const managedByDisplay = (sale.managed_by === loggedInEmployeeId) ? "You" : sale.managed_by;

            const listItem = document.createElement("li");
            listItem.classList.add("sales-manage-orders-table-item", "sales");

            listItem.innerHTML = `
                <span class="sales-order-id">${sale.sales_order_id}</span>
                <span class="order-item-count">${orderItemCount}</span>
                <span class="hd-managed-by">${managedByDisplay}</span>
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

async function handleCancelOrder(salesOrderId, rawEmployeeId) {
    try {
        const response = await fetch("db_queries/update_sale_order_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                sales_order_id: salesOrderId, // Without prefix
                new_status: "cancelled",
                performed_by: rawEmployeeId, // Without prefix
                details: `Cancelled sale SID-${salesOrderId.toString().padStart(3, "0")}`,
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert("Order successfully cancelled!");
            location.reload(); // Reload the page after the alert
        } else {
            console.error("Failed to cancel the order:", result.error);
        }
    } catch (error) {
        console.error("Error cancelling order:", error);
    }
}

async function handleMarkOrderComplete(salesOrderId, rawEmployeeId) {
    try {
        const response = await fetch("db_queries/update_sale_order_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                sales_order_id: salesOrderId, // Without prefix
                new_status: "completed",
                performed_by: rawEmployeeId, // Without prefix
                details: `Made a sale. SID-${salesOrderId.toString().padStart(3, "0")} marked as complete`,
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert("Order successfully marked as complete!");
            location.reload(); // Reload the page after the alert
        } else {
            console.error("Failed to mark the order as complete:", result.error);
        }
    } catch (error) {
        console.error("Error marking order complete:", error);
    }
}

function showOrderDetailsModal(sale) {
    // Get modal and necessary sections
    const modal = document.querySelector(".modal-order-items-attached2");
    const orderDetailsSection = modal.querySelector(".salesadditem2");
    const orderItemsContainer = modal.querySelector(".orderlist-container");
    const actionButtonsContainer = modal.querySelector(".save.sales.ss2"); // Action buttons container

    // Determine the "Managed by" display value
    const managedByDisplay = loggedInEmployeeId === sale.managed_by ? "You" : sale.managed_by;

    // Log the status and managed_by for debugging
    console.log("Order Status:", sale.status);
    console.log("Managed By (raw):", sale.managed_by);
    console.log("Logged-in Employee ID:", loggedInEmployeeId);

    // Populate order details
    orderDetailsSection.innerHTML = `
        <div class="prod-m sales2">
            <span>${sale.sales_order_id}</span>
        </div>
        <div class="">
            <span>Managed by</span>
            <span>${managedByDisplay}</span>
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
        const emptyMessageHTML = `
            <li class="item">
                <span colspan="4" style="text-align: center;">No items found for this order.</span>
            </li>
        `;
        orderItemsContainer.innerHTML += emptyMessageHTML;
    }

    // Attach button functionality
    const cancelButton = document.getElementById("cancelsaleorder");
    const completeButton = document.getElementById("marksaleordercomplete");

    // Hide buttons based on conditions
    if (sale.status === "cancelled" || sale.status === "completed" || sale.managed_by !== loggedInEmployeeId) {
        console.log("Hiding buttons - Condition met.");
        actionButtonsContainer.classList.add("hide");
    } else {
        console.log("Showing buttons - Condition NOT met.");
        actionButtonsContainer.classList.remove("hide");
    }

    // Add fresh event listeners to buttons
    cancelButton.onclick = async (event) => {
        event.preventDefault();
        const rawEmployeeId = parseInt(loggedInEmployeeId.replace("SSM-", ""), 10); // Remove prefix
        const salesOrderId = parseInt(sale.sales_order_id.replace("SID-", ""), 10); // Extract raw sales_order_id
        await handleCancelOrder(salesOrderId, rawEmployeeId);
    };

    completeButton.onclick = async (event) => {
        event.preventDefault();
        const rawEmployeeId = parseInt(loggedInEmployeeId.replace("SSM-", ""), 10); // Remove prefix
        const salesOrderId = parseInt(sale.sales_order_id.replace("SID-", ""), 10); // Extract raw sales_order_id
        await handleMarkOrderComplete(salesOrderId, rawEmployeeId);
    };

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

// Call fetch and display data every 5 seconds
fetchAndDisplaySalesData();
setInterval(fetchAndDisplaySalesData, 5000);
