document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll(".option-item");
    const overlay = document.getElementById("overlay");
    const tableContent = document.getElementById("table-content");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-button");
    const tableNameContainer = document.getElementById("table-name");
    const sortContainer = document.getElementById("sort-container");
    const logsButton = document.querySelector(".logs");
    const logsOverlay = document.getElementById("logs-overlay");
    const logsContent = document.querySelector(".logs-content"); // For dynamic modal content
    const clearTablesButton = document.querySelector(".clear-tables");
    const setQuantityButton = document.querySelector(".set-quantity");

    let sortOrder = "descending"; // Initial sort order

    setQuantityButton.addEventListener("click", async () => {
        try {
            const response = await fetch('set_quantity.php', { method: 'POST' });
            if (response.ok) {
                alert("Quantities updated successfully!");
            } else {
                alert("Failed to update quantities.");
            }
        } catch (error) {
            showLogsModal(`<h3>Error:</h3><p>An error occurred while updating quantities: ${error.message}</p>`);
        }
    });

    items.forEach(item => {
        item.addEventListener("click", async () => {
            const tableName = item.dataset.table;
            tableNameContainer.textContent = `${tableName} Table`;

            try {
                const response = await fetch(`fetch_table.php?table=${tableName}`);
                const data = await response.json();

                if (data && data.length > 0 && data[0]) {
                    let html = `<table><thead><tr>`;
                    Object.keys(data[0]).forEach(col => {
                        html += `<th>${col}</th>`;
                    });
                    html += `</tr></thead><tbody>`;
                    data.forEach(row => {
                        html += `<tr>`;
                        Object.values(row).forEach(value => {
                            html += `<td>${value}</td>`;
                        });
                        html += `</tr>`;
                    });
                    html += `</tbody></table>`;
                    tableContent.innerHTML = html;
                    overlay.style.display = "block";
                } else {
                    alert(`Table "${tableName}" is empty.`);
                }
            } catch (error) {
                tableContent.innerHTML = `<p>Error loading table data.</p>`;
                showLogsModal(`<h3>Error:</h3><p>An error occurred while loading table data: ${error.message}</p>`);
            }
        });
    });

    overlay.addEventListener("click", (event) => {
        if (event.target === overlay) {
            overlay.style.display = "none";
            tableContent.innerHTML = "";
        }
    });

    const triggerSearch = () => {
        const searchValue = searchInput.value.toLowerCase().trim();
        const rows = tableContent.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            let rowContainsSearchValue = false;

            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    rowContainsSearchValue = true;
                }
            });

            if (rowContainsSearchValue) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    };

    searchButton.addEventListener("click", triggerSearch);

    searchInput.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent form submission if inside a form
            triggerSearch();
        }
    });

    sortContainer.addEventListener("click", () => {
        const rows = Array.from(tableContent.querySelectorAll("tbody tr"));
        
        rows.sort((rowA, rowB) => {
            const cellA = rowA.querySelector("td").textContent.trim();
            const cellB = rowB.querySelector("td").textContent.trim();

            if (sortOrder === "descending") {
                return cellB.localeCompare(cellA);
            } else {
                return cellA.localeCompare(cellB);
            }
        });

        const tbody = tableContent.querySelector("tbody");
        tbody.innerHTML = ""; // Clear existing rows
        rows.forEach(row => tbody.appendChild(row)); // Append sorted rows

        // Toggle the sort order for next click
        sortOrder = (sortOrder === "descending") ? "ascending" : "descending";
    });

    logsButton.addEventListener("click", () => {
        window.open('logs.php', '_blank'); // Open code.php in a new tab
    });


    clearTablesButton.addEventListener("click", async () => {
        try {
            const response = await fetch('clear_tables.php', { method: 'POST' });
            if (response.ok) {
                const message = await response.text(); // Fetch the server's response
                console.log("Server Response:", message); // Log the server's response for debugging
                alert("Tables cleared successfully!");
            } else {
                console.error("Failed to clear tables. Status:", response.status);
                alert("Failed to clear tables.");
            }
        } catch (error) {
            console.error("Error during fetch:", error); // Log the error to the console
            showLogsModal(`<h3>Error:</h3><p>An error occurred while clearing tables: ${error.message}</p>`);
        }
    });
    

    // Function to show the logs modal with dynamic content
    const showLogsModal = (content) => {
        logsContent.innerHTML = content;
        logsOverlay.style.display = "flex";
    };
});
