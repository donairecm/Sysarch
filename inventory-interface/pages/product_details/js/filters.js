// Select all items
const items = document.querySelectorAll('.inventory-product-details-item.filter');

// Define mapping for filters, including the new classes
const filterMapping = {
    pd3: ['products-filter', 'products-filter-2'],
    pd4: ['reorder-filter', 'reorder-filter-2'],
    pd5: ['location-filter', 'location-filter-2']
};

// Add click event listener to each item
items.forEach(item => {
    item.addEventListener('click', () => {
        // Remove 'active' class from all items
        items.forEach(i => i.classList.remove('active'));

        // Add 'active' class to the clicked item
        item.classList.add('active');

        // Update the filters' active state
        Object.values(filterMapping).flat().forEach(filter => {
            const filterElements = document.querySelectorAll(`li.${filter}`);
            filterElements.forEach(filterElement => {
                filterElement.classList.remove('active');
            });
        });

        // Identify the corresponding filter classes and activate them
        const itemClass = [...item.classList].find(cls => filterMapping[cls]);
        if (itemClass) {
            const correspondingFilters = filterMapping[itemClass];
            correspondingFilters.forEach(filter => {
                const filterElements = document.querySelectorAll(`li.${filter}`);
                filterElements.forEach(filterElement => {
                    filterElement.classList.add('active');
                });
            });
        }
    });
});

//search 
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector(".search-input");
    const productDetailsContainer = document.querySelector(".product-details-container");

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();

        // Select only rows with the "product-details-item" and "active" classes
        const activeRows = productDetailsContainer.querySelectorAll(".product-details-item.active");

        // Loop through all active rows to apply the filter
        activeRows.forEach(row => {
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

