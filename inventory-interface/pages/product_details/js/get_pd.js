// Function to populate the product details list
function populateProductDetails(data) {
    const productDetailsContainer = document.querySelector(".product-details-container");

    data.forEach(product => {
        const listItem = document.createElement("li");
        listItem.classList.add("product-details-item", "products-filter", "2", "active");

        listItem.innerHTML = `
            <span class="product-id">${product.product_id}</span>
            <span class="product-name">${product.product_name}</span>
            <span class="quantity">${product.quantity}</span>
            <span class="price">${product.price}</span>
            <span class="product-status">${product.product_status}</span>
            <span class="created-on">${product.created_on}</span>
        `;

        productDetailsContainer.appendChild(listItem);
    });
}

// Function to populate the reorder details list
function populateReorderDetails(data) {
    const reorderDetailsContainer = document.querySelector(".product-details-container");

    data.forEach(product => {
        const listItem = document.createElement("li");
        listItem.classList.add("product-details-item", "reorder-filter", "2", "active");

        listItem.innerHTML = `
            <span class="product-id">${product.product_id}</span>
            <span class="product-name">${product.product_name}</span>
            <span class="quantity">${product.quantity}</span>
            <span class="reorder-point">${product.reorder_point}</span>
            <span class="reorder-cost">${product.reorder_cost}</span>
            <span class="last-restocked">${product.last_restocked}</span>
        `;

        reorderDetailsContainer.appendChild(listItem);
    });
}

// Function to populate the location details list
function populateLocationDetails(data) {
    const locationDetailsContainer = document.querySelector(".product-details-container");

    data.forEach(product => {
        const listItem = document.createElement("li");
        listItem.classList.add("product-details-item", "location-filter", "active");

        listItem.innerHTML = `
            <span class="product-id">${product.product_id}</span>
            <span class="product-name">${product.product_name}</span>
            <span class="location">${product.stock_location}</span>
        `;

        locationDetailsContainer.appendChild(listItem);
    });
}

// Example usage: Fetch products and populate the product, reorder, and location details lists
async function fetchAndRenderDetails() {
    try {
        const response = await fetch('db_queries/fetch_pd.php');
        const data = await response.json();
        populateProductDetails(data);
        populateReorderDetails(data);
        populateLocationDetails(data);
    } catch (error) {
        console.error('Error fetching details:', error);
    }
}

// Call the function to populate the details lists
fetchAndRenderDetails();
