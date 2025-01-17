// Function to populate the sales details list
function populateSalesDetails(data) {
    const salesDetailsContainer = document.querySelector(".inventory-movements-container");

    if (!Array.isArray(data.products)) {
        console.error('Invalid or missing products data');
        return;
    }

    data.products.forEach(product => {
        const listItem = document.createElement("li");
        listItem.classList.add("inventory-movements-item", "sales-filter", "2", "active");

        listItem.innerHTML = `
            <span class="movement-id">${product.movement_id}</span>
            <span class="product-id">${product.product_id}</span>
            <span class="product-name">${product.product_name}</span>
            <span class="quantity">${product.quantity}</span>
            <span class="date-of-movement">${product.date_of_movement}</span>
        `;

        salesDetailsContainer.appendChild(listItem);
    });
}

// Function to populate reorder requests details list
function populateReorderRequests(data) {
    const reorderDetailsContainer = document.querySelector(".inventory-movements-container");

    if (!Array.isArray(data.reorderrequests)) {
        console.error('Invalid or missing reorder requests data');
        return;
    }

    data.reorderrequests.forEach(reorderRequest => {
        const listItem = document.createElement("li");
        listItem.classList.add("inventory-movements-item", "restock-filter", "2", "active");

        listItem.innerHTML = `
            <span class="request-id">${reorderRequest.request_id}</span>
            <span class="product-id">${reorderRequest.product_id}</span>
            <span class="product-name">${reorderRequest.product_name}</span>
            <span class="quantity">${reorderRequest.quantity}</span>
            <span class="date-of-request">${reorderRequest.date_of_request}</span>
            <span class="date-of-completion">${reorderRequest.completed_on || 'N/A'}</span>
        `;

        reorderDetailsContainer.appendChild(listItem);
    });
}

// Fetch all details at once and distribute them to the respective functions
async function fetchAndRenderDetails() {
    try {
        const response = await fetch('db_queries/fetch_imv_rr.php');
        if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
        const data = await response.json();

        if (data.error) {
            console.error('Error in server response:', data.error);
            return;
        }

        // Distribute data to respective functions
        populateSalesDetails(data);
        populateReorderRequests(data);

        // Only populate location details if locations exist in the response
        if (data.locations) {
            populateLocationDetails(data);
        }
    } catch (error) {
        console.error('Error fetching details:', error);
    }
}

// Call the function to populate the details lists
fetchAndRenderDetails();
