let currentEmployeeId = null;

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
            <span class="type">${order.source}</span>
            <span class="status">${order.status}</span>
            <span class="handled-by">${order.handled_by === `SCM-${currentEmployeeId}` ? "You" : order.handled_by}</span>
        `;

        // Add click listener for interaction with orders
        listItem.addEventListener("click", () => openModal(order));

        ordersContainer.appendChild(listItem);
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


// Function to fetch and render supply chain orders
async function fetchAndRenderOrders() {
    try {
        const response = await fetch("db_queries/fetch_sco.php"); // PHP script fetching supply chain orders
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        populateSupplyChainOrders(data);
    } catch (error) {
        console.error("Error fetching supply chain orders:", error);
    }
}

// Initialize on page load
(async () => {
    await fetchLoggedInUser();
    fetchAndRenderOrders();
    setInterval(fetchAndRenderOrders, 5000); // Refresh every 5 seconds
})();
