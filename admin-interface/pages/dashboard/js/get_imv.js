document.addEventListener("DOMContentLoaded", async () => {
    const container = document.querySelector(".inventory-movements-container");

    try {
        // Fetch data from the server
        const response = await fetch("db_queries/fetch_imv.php"); // Replace with the correct path to your PHP file
        const data = await response.json();

        if (!data.inventory_movements) {
            console.error("No inventory movements found");
            return;
        }

        // Sort inventory movements by movement_id (descending)
        const sortedMovements = data.inventory_movements
            .map((item, index) => ({
                ...item,
                movement_id: index + 1 // Temporarily assign a numeric movement_id
            }))
            .sort((a, b) => b.movement_id - a.movement_id); // Sort in descending order

        // Clear any existing dynamically inserted rows
        container.querySelectorAll(".inventory-movements-item:not(.inventory-movements-header)").forEach(el => el.remove());

        // Loop through the sorted inventory movements and add them dynamically
        sortedMovements.forEach((item, index) => {
            // Format values
            const movementId = `MOV-${String(item.movement_id).padStart(3, "0")}`;
            const productId = `PRD-${String(item.product_id).padStart(3, "0")}`;
            const productName = item.product_name || "N/A"; // Use product_name from the API
            const quantity = item.quantity;
            const movementType = capitalizeFirstLetter(item.movement_type);
            const dateOfMovement = formatDate(item.date_of_movement);

            // Create a new list item
            const listItem = document.createElement("li");
            listItem.className = "inventory-movements-item sales-filter active";

            // Add Movement ID
            const movementIdSpan = createSpan("movement-id", movementId);
            listItem.appendChild(movementIdSpan);

            // Add Product ID
            const productIdSpan = createSpan("product-id", productId);
            listItem.appendChild(productIdSpan);

            // Add Product Name
            const productNameSpan = createSpan("product-name", productName);
            listItem.appendChild(productNameSpan);

            // Add Quantity
            const quantitySpan = createSpan("quantity", quantity);
            listItem.appendChild(quantitySpan);

            // Add Movement Type
            const movementTypeSpan = createSpan("movement-type", movementType);
            listItem.appendChild(movementTypeSpan);

            // Add Date of Movement
            const dateOfMovementSpan = createSpan("date-of-movement", dateOfMovement);
            listItem.appendChild(dateOfMovementSpan);

            // Append the new list item to the container
            container.appendChild(listItem);
        });
    } catch (error) {
        console.error("Failed to fetch inventory movements:", error);
    }
});

// Helper function to capitalize the first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Helper function to format date
function formatDate(dateString) {
    const options = { month: "short", day: "numeric", year: "numeric", hour: "numeric", minute: "numeric", hour12: true };
    return new Date(dateString).toLocaleString("en-US", options).replace(",", " |");
}

// Helper function to create a span element
function createSpan(className, textContent) {
    const span = document.createElement("span");
    span.className = className;
    span.textContent = textContent;
    return span;
}
