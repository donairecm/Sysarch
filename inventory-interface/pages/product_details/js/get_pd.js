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
            <span class="actions">
                <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                </svg>
                <div class="tooltip">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            </span>
        `;

        productDetailsContainer.appendChild(listItem);
    });

    // Add event listeners for tooltips
    const actionIcons = document.querySelectorAll(".action-icon");
    actionIcons.forEach(icon => {
        icon.addEventListener("click", (e) => {
            e.stopPropagation(); // Prevent event bubbling
            const tooltip = icon.nextElementSibling;
            tooltip.classList.toggle("visible");
        });
    });

    // Close tooltips when clicking elsewhere
    document.addEventListener("click", () => {
        const tooltips = document.querySelectorAll(".tooltip");
        tooltips.forEach(tooltip => tooltip.classList.remove("visible"));
    });
}

// Functions for reorder and location lists (no changes needed)
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
            <span class="actions">
                <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                </svg>
                <div class="tooltip">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            </span>
        `;

        reorderDetailsContainer.appendChild(listItem);
    });
}

function populateLocationDetails(data) {
    const locationDetailsContainer = document.querySelector(".product-details-container");

    data.forEach(product => {
        const listItem = document.createElement("li");
        listItem.classList.add("product-details-item", "location-filter", "active");

        listItem.innerHTML = `
            <span class="product-id">${product.product_id}</span>
            <span class="product-name">${product.product_name}</span>
            <span class="location">${product.stock_location}</span>
            <span class="actions">
                <svg class="action-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                    <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z"/>
                </svg>
                <div class="tooltip">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            </span>
        `;

        locationDetailsContainer.appendChild(listItem);
    });
}

// Example usage: Fetch products and populate the product, reorder, and location details lists
async function fetchAndRenderDetails() {
    try {
        const productResponse = await fetch('db_queries/fetch_pd.php');
        const productData = await productResponse.json();
        populateProductDetails(productData);

        const reorderResponse = await fetch('db_queries/fetch_pd.php');
        const reorderData = await reorderResponse.json();
        populateReorderDetails(reorderData);

        const locationResponse = await fetch('db_queries/fetch_pd.php');
        const locationData = await locationResponse.json();
        populateLocationDetails(locationData);
    } catch (error) {
        console.error('Error fetching details:', error);
    }
}

// Add necessary CSS for tooltip
const style = document.createElement('style');
style.innerHTML = `
    .tooltip {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        padding: 5px;
        z-index: 1000;
    }
    .tooltip.visible {
        display: block;
    }
    .tooltip button {
        background: none;
        border: none;
        padding: 5px;
        cursor: pointer;
        margin: 2px;
    }
    .tooltip button:hover {
        background: #f0f0f0;
    }
    .actions {
        position: relative;
    }
    .action-icon {
        cursor: pointer;
    }
`;
document.head.appendChild(style);

// Call the function to populate the details lists
fetchAndRenderDetails();
