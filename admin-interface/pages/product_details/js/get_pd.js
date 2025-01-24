// Function to open the modal
function openModal(product) {
    const modal = document.querySelector(".modal-product-details");
    modal.classList.add("show");

    // Populate modal with product details using IDs
    document.getElementById("modal-product-id").textContent = product.product_id;
    document.getElementById("md-product_name").textContent = product.product_name;
    document.getElementById("md-quantity").textContent = product.quantity;
    document.getElementById("md-reorder_point").textContent = product.reorder_point;
    document.getElementById("md-price").textContent = product.price;
    document.getElementById("md-reorder_cost").textContent = product.reorder_cost;
    document.getElementById("md-location").textContent = product.stock_location;


    
    // Add event listener to close the modal when clicking outside the modal content
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}

// Function to close the modal
function closeModal() {
    const modal = document.querySelector(".modal-product-details");
    modal.classList.remove("show");
}

// Prevent event propagation inside modal content
const modalContent = document.querySelector(".modal-content");
modalContent.addEventListener("click", (event) => {
    event.stopPropagation();
});

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

        listItem.addEventListener("click", () => openModal(product));

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

        listItem.addEventListener("click", () => openModal(product));

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
            <span class="units-sold">${product.total_units_sold}</span>
            <span class="supplier">${product.supplier_name}</span>
            <span class="location">${product.stock_location}</span>
        `;

        listItem.addEventListener("click", () => openModal(product));

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