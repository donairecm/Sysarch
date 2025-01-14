document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.option-item');
    const overlay = document.getElementById('overlay');
    const tableContainer = document.getElementById('table-container');
    const tableContent = document.getElementById('table-content');
    const tableName = document.getElementById('table-name');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const sortContainer = document.getElementById('sort-container');
    const generateDataButton = document.querySelector('.generate-data');
    const resetQuantityButton = document.querySelector('.reset-quantity');
    const resetTablesButton = document.querySelector('.reset-tables');

    let currentTableData = ''; // Holds the raw HTML data of the current table
    let isAscending = true; // Tracks the current sort order

    // Fetch and display table data when an option is clicked
    options.forEach(option => {
        option.addEventListener('click', async () => {
            const table = option.getAttribute('data-table');
            const name = option.querySelector('.item-name').textContent;

            tableName.textContent = name;

            try {
                const response = await fetch(`db/fetch_table.php?table=${table}`);
                if (!response.ok) throw new Error('Failed to fetch data');
                const data = await response.text();
                currentTableData = data; // Save fetched data
                tableContent.innerHTML = data; // Render table data
                overlay.style.display = 'block';
            } catch (error) {
                console.error('Error fetching table data:', error);
                tableContent.innerHTML = '<p>Error loading data. Please try again.</p>';
                overlay.style.display = 'block';
            }
        });
    });

    // Close the overlay on click outside the content
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.style.display = 'none';
    });

    // Search table rows based on input
    const performSearch = () => {
        const searchTerm = searchInput.value.toLowerCase();
        if (!currentTableData) return;

        const parser = new DOMParser();
        const doc = parser.parseFromString(currentTableData, 'text/html');
        const rows = doc.querySelectorAll('tr');
        const header = rows[0];
        const filteredRows = Array.from(rows).slice(1).filter(row =>
            row.textContent.toLowerCase().includes(searchTerm)
        );

        tableContent.innerHTML = filteredRows.length > 0
            ? `<table><thead>${header.outerHTML}</thead><tbody>${filteredRows.map(row => row.outerHTML).join('')}</tbody></table>`
            : `<table><thead>${header.outerHTML}</thead><tbody><tr><td colspan="${header.children.length}">No results found</td></tr></tbody></table>`;
    };

    // Bind search functionality to button click and "Enter" key press
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') performSearch();
    });

    // Sort table rows by the first column
    sortContainer.addEventListener('click', () => {
        if (!currentTableData) return;

        const parser = new DOMParser();
        const doc = parser.parseFromString(currentTableData, 'text/html');
        const rows = Array.from(doc.querySelectorAll('tr'));
        const header = rows.shift(); // Remove the header row for sorting

        rows.sort((a, b) => {
            const aText = a.cells[0]?.textContent.trim() || '';
            const bText = b.cells[0]?.textContent.trim() || '';
            return isAscending
                ? aText.localeCompare(bText, undefined, { numeric: true })
                : bText.localeCompare(aText, undefined, { numeric: true });
        });

        isAscending = !isAscending; // Toggle sort order

        tableContent.innerHTML = `<table><thead>${header.outerHTML}</thead><tbody>${rows.map(row => row.outerHTML).join('')}</tbody></table>`;
    });

    // Open a new tab to generate data
    generateDataButton.addEventListener('click', () => {
        if (confirm('Generate new data?')) {
            const newWindow = window.open('generator/logs.php', '_blank'); // Open logs.php in a new window
            
            if (newWindow) {
                newWindow.addEventListener('load', () => {
                    console.log('Logs window fully loaded.');
                    // You can add additional actions here if needed
                });
            } else {
                alert('Failed to open logs window. Please allow pop-ups for this site.');
            }
        }
    });
    

    // Reset product quantities
    resetQuantityButton.addEventListener('click', async () => {
        if (confirm('Reset all product quantities to 90?')) {
            try {
                const response = await fetch('db/reset_quantity.php');
                if (!response.ok) throw new Error('Failed to reset quantities.');
                alert('Product quantities reset successfully.');
            } catch (error) {
                console.error('Error resetting quantities:', error);
                alert('Failed to reset quantities.');
            }
        }
    });

    // Reset all tables
    resetTablesButton.addEventListener('click', async () => {
        if (confirm('Reset all table data? This action cannot be undone.')) {
            try {
                const response = await fetch('db/reset_tables.php');
                if (!response.ok) throw new Error('Failed to reset tables.');
                alert('All tables reset successfully.');
            } catch (error) {
                console.error('Error resetting tables:', error);
                alert('Failed to reset tables.');
            }
        }
    });
});
