let currentEmployeeId = null;
let currentFilterStatus = null; 

// Fetch the logged-in employee ID
async function fetchLoggedInUser() {
    try {
        const response = await fetch('../php/get_logged_in_user.php');
        const data = await response.json();
        if (data.success) {
            currentEmployeeId = data.employee_id.toString().padStart(3, '0');
        } else {
            console.error('Error fetching logged-in user:', data.error);
        }
    } catch (error) {
        console.error('Error fetching logged-in user:', error);
    }
}

function sortOrdersByStatus(data) {
    const statusOrder = ["Pending", "On process", "In transit", "Completed", "Cancelled"];
    return data.sort((a, b) => statusOrder.indexOf(a.status) - statusOrder.indexOf(b.status));
}

// Function to populate the supply chain orders table
function populateSupplyChainOrders(data) {
    const ordersContainer = document.querySelector(".supply-chain-orders");

    // Clear existing rows except the header
    ordersContainer.querySelectorAll(".supply-chain-orders-item").forEach(item => item.remove());

    data.forEach(order => {
        const listItem = document.createElement("li");
        listItem.classList.add("supply-chain-orders-item", "products-filter", "2", "active");

        listItem.innerHTML = `
            <span class="order-id">${order.sc_order_id}</span>
            <span class="status">${order.status}</span>
            <span class="handled-by">${order.handled_by === `SCM-${currentEmployeeId}` ? "You" : order.handled_by}</span>
        `;

        // Add click listener for interaction with orders
        listItem.addEventListener("click", () => openModal(order));

        ordersContainer.appendChild(listItem);
    });
}

// Function to filter orders based on selected status
function filterOrdersByStatus(data, status) {
    if (!status) {
        return data; // Return all orders if no specific status is selected
    }
    return data.filter(order => order.status === status);
}

// Function to handle metric clicks
function setupMetrics(data) {
    const metrics = document.querySelectorAll(".supply-chain-order-processing-item.metric");

    metrics.forEach(metric => {
        metric.addEventListener("click", () => {
            const status = metric.querySelector(".left").textContent; // Get status from the metric

            // If the clicked metric is already active, deactivate it
            if (metric.classList.contains("active")) {
                metric.classList.remove("active");
                currentFilterStatus = null; // Clear the active filter
                populateSupplyChainOrders(data); // Show all orders
                return;
            }

            // Deactivate the previously active metric
            document.querySelectorAll(".supply-chain-order-processing-item.metric.active").forEach(activeMetric => {
                activeMetric.classList.remove("active");
            });

            // Activate the clicked metric
            metric.classList.add("active");
            currentFilterStatus = status; // Update the active filter status

            // Filter and render the orders
            const filteredData = filterOrdersByStatus(data, currentFilterStatus);
            populateSupplyChainOrders(filteredData);
        });
    });
}

// Function to open modal and populate details
function openModal(order) {
    const modal = document.querySelector(".modal-supply-chain-orders");
    modal.classList.add("show");

    // Log the clicked item details
    console.log(`Clicked Order ID: ${order.sc_order_id}, Status: ${order.status}, Handled By: ${order.handled_by}, Current Employee ID: SCM-${currentEmployeeId}`);

    // Reset any existing show classes
    modal.querySelectorAll(".reorder, .delivery, .accept, .ready, .delivered").forEach(el => el.classList.remove("show"));

    // Populate modal details
    modal.querySelector(".sc_order_idANDstatus").textContent = `${order.sc_order_id} | ${order.status}`;
    modal.querySelector(".handled_by span").textContent = order.handled_by === `SCM-${currentEmployeeId}` ? "You" : order.handled_by;
    modal.querySelector(".accepted_on span").textContent = order.accepted_on || "...";
    modal.querySelector(".product_id span").textContent = order.product_id || "...";
    modal.querySelector(".product_name span").textContent = order.product_name || "...";
    modal.querySelector(".quantity span").textContent = order.quantity || "...";
    modal.querySelector(".supplier_name span").textContent = order.supplier_name || "...";
    modal.querySelector(".contact_person span").textContent = order.contact_person || "...";
    modal.querySelector(".phone_number span").textContent = order.phone_number || "...";

    // Show type-specific modal content
    if (order.source === "Reorder") {
        modal.querySelector(".reorder").classList.add("show");
    } else if (order.source === "Delivery") {
        modal.querySelector(".delivery").classList.add("show");
    }

    // Show button based on status
    if (order.status === "Pending") {
        modal.querySelector(".accept").classList.add("show");
    } else if (order.status === "On process" && order.handled_by === `SCM-${currentEmployeeId}`) {
        modal.querySelector(".ready").classList.add("show");
    } else if (order.status === "In transit" && order.handled_by === `SCM-${currentEmployeeId}`) {
        modal.querySelector(".delivered").classList.add("show");
    }

    // Add event listeners to buttons
    addStatusUpdateHandlers(modal, order);

    // Close modal when clicking outside the modal content
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });
}

async function handleStatusUpdate(order, newStatus, action) {
    const modal = document.querySelector("#confirmationModalforstatusupdate");
    modal.style.display = "flex";

    // Update confirmation text
    modal.querySelector("span").textContent = `${action} ${order.sc_order_id}?`;

    // Confirm button action
    const confirmBtn = modal.querySelector("#confirmupdateStatus");
    confirmBtn.onclick = async () => {
        modal.style.display = "none";

        try {
            // Log the status update attempt
            console.log(`Updating Order ID: ${order.sc_order_id}, New Status: ${newStatus}, Handled By: ${order.handled_by}, Current Employee ID: SCM-${currentEmployeeId}`);

            // Update status in the database and log the activity
            const response = await fetch("db_queries/update_order_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    sc_order_id: order.sc_order_id,
                    new_status: newStatus,
                    employee_id: currentEmployeeId
                })
            });

            const result = await response.json();
            if (result.success) {
                // Reload the page after successful update
                location.reload();
            } else {
                console.error(result.error);
                alert("Failed to update status: " + result.error);
            }
        } catch (error) {
            console.error("Error updating status:", error);
            alert("An unexpected error occurred while updating the status.");
        }
    };

    // Cancel button action
    const cancelBtn = modal.querySelector("#cancelupdateStatus");
    cancelBtn.onclick = () => {
        modal.style.display = "none";
    };
}


function closeModal(modal) {
    modal.classList.remove("show");
}

// Add global click listener for modal-content propagation prevention
document.querySelectorAll(".modal-content").forEach(modalContent => {
    modalContent.addEventListener("click", (event) => {
        event.stopPropagation(); // Prevent closing the modal when clicking inside the modal content
    });
});

// Add handlers for status update buttons
function addStatusUpdateHandlers(modal, order) {
    const acceptBtn = modal.querySelector(".accept");
    const readyBtn = modal.querySelector(".ready");
    const deliveredBtn = modal.querySelector(".delivered");

    // Remove existing click listeners
    [acceptBtn, readyBtn, deliveredBtn].forEach(btn => {
        btn.removeEventListener("click", handleStatusUpdate);
    });

    // Add click listeners
    if (acceptBtn) {
        acceptBtn.addEventListener("click", () => handleStatusUpdate(order, "on_process", "Accepted"));
    }
    if (readyBtn) {
        readyBtn.addEventListener("click", () => handleStatusUpdate(order, "in_transit", "Ready for delivery"));
    }
    if (deliveredBtn) {
        deliveredBtn.addEventListener("click", () => handleStatusUpdate(order, "completed", "Delivered"));
    }
}

// Handle status update
async function handleStatusUpdate(order, newStatus, action) {
    const modal = document.querySelector("#confirmationModalforstatusupdate");
    modal.style.display = "flex";

    // Update confirmation text
    modal.querySelector("span").textContent = `${action} ${order.sc_order_id}?`;

    // Confirm button action
    const confirmBtn = modal.querySelector("#confirmupdateStatus");
    confirmBtn.onclick = async () => {
        modal.style.display = "none";

        try {
            // Update status in the database and log the activity
            const response = await fetch("db_queries/update_order_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    sc_order_id: order.sc_order_id,
                    new_status: newStatus,
                    employee_id: currentEmployeeId
                })
            });

            const result = await response.json();
            if (result.success) {
                // Reload the page after successful update
                location.reload();
            } else {
                console.error(result.error);
                alert("Failed to update status: " + result.error);
            }
        } catch (error) {
            console.error("Error updating status:", error);
            alert("An unexpected error occurred while updating the status.");
        }
    };

    // Cancel button action
    const cancelBtn = modal.querySelector("#cancelupdateStatus");
    cancelBtn.onclick = () => {
        modal.style.display = "none";
    };
}

// Function to calculate and display status counts
function displayStatusCounts(data) {
    const statusCounts = {
        Pending: 0,
        "On process": 0,
        "In transit": 0,
        Completed: 0,
        Cancelled: 0
    };

    // Calculate counts
    data.forEach(order => {
        if (statusCounts[order.status] !== undefined) {
            statusCounts[order.status]++;
        }
    });

    // Update DOM elements with calculated counts
    document.querySelector(".supply-chain-order-processing-item.op1 .right").textContent = statusCounts.Pending;
    document.querySelector(".supply-chain-order-processing-item.op2 .right").textContent = statusCounts["On process"];
    document.querySelector(".supply-chain-order-processing-item.op3 .right").textContent = statusCounts["In transit"];
    document.querySelector(".supply-chain-order-processing-item.op4 .right").textContent = statusCounts.Completed;
    document.querySelector(".supply-chain-order-processing-item.op5 .right").textContent = statusCounts.Cancelled;
}


async function fetchAndRenderOrders(status = null) {
    try {
        const response = await fetch("db_queries/fetch_sco.php"); // PHP script fetching supply chain orders
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const rawData = await response.json();

        // Sort orders by status
        const sortedData = sortOrdersByStatus(rawData);

        // Apply the filter if a specific status is provided
        const dataToRender = status
            ? filterOrdersByStatus(sortedData, status) // Apply filter
            : sortedData; // No filter, render all data

        // Populate the orders table
        populateSupplyChainOrders(dataToRender);

        // Update status counts and reapply metric click interactions
        displayStatusCounts(sortedData);
        setupMetrics(sortedData);
    } catch (error) {
        console.error("Error fetching supply chain orders:", error);
    }
}

function setupFetchInterval() {
    // Clear any existing interval
    if (fetchInterval) {
        clearInterval(fetchInterval);
    }

    // Start a new interval
    fetchInterval = setInterval(() => fetchAndRenderOrders(), 5000);
}

// Initialize on page load
(async () => {
    await fetchLoggedInUser();
    fetchAndRenderOrders();
    setInterval(fetchAndRenderOrders, 5000); // Refresh every 5 seconds
})();