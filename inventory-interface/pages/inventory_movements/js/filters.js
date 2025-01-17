document.addEventListener("DOMContentLoaded", () => {
    // Select all inventory movement items
    const inventoryMovementItems = document.querySelectorAll('.inventory-inventory-movements-item.filter');

    // Define mapping for inventory movement filters, including the new classes
    const inventoryMovementFilterMapping = {
        imv3: ['sales-filter', 'sales-filter-2'],
        imv4: ['restock-filter', 'restock-filter-2'],
    };

    // Add click event listener to each inventory movement item
    inventoryMovementItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove 'active' class from all inventory movement items
            inventoryMovementItems.forEach(i => i.classList.remove('active'));

            // Add 'active' class to the clicked item
            item.classList.add('active');

            // Update the inventory movement filters' active state
            Object.values(inventoryMovementFilterMapping).flat().forEach(filter => {
                const filterElements = document.querySelectorAll(`li.${filter}`);
                filterElements.forEach(filterElement => {
                    filterElement.classList.remove('active');
                });
            });

            // Identify the corresponding filter classes and activate them
            const itemClass = [...item.classList].find(cls => inventoryMovementFilterMapping[cls]);
            if (itemClass) {
                const correspondingFilters = inventoryMovementFilterMapping[itemClass];
                correspondingFilters.forEach(filter => {
                    const filterElements = document.querySelectorAll(`li.${filter}`);
                    filterElements.forEach(filterElement => {
                        filterElement.classList.add('active');
                    });
                });
            }
        });
    });

    // Search functionality for inventory movements
    const searchInventoryInput = document.querySelector(".imv1 .search-input");
    const inventoryMovementsContainer = document.querySelector(".inventory-movements-container");

    searchInventoryInput.addEventListener("input", () => {
        const query = searchInventoryInput.value.toLowerCase();

        // Select only rows with the "inventory-movements-item" and "active" classes
        const activeInventoryRows = inventoryMovementsContainer.querySelectorAll(".inventory-movements-item.active");

        // Loop through all active inventory rows to apply the filter
        activeInventoryRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();

            // Show or hide the row based on whether it includes the query
            if (rowText.includes(query)) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        });
    });
});
