document.addEventListener('DOMContentLoaded', () => {
    const fetchInventoryTurnover = () => {
        fetch('db_queries/calc_in_turno.php')
            .then(response => response.json())
            .then(data => {
                if (data.current_inventory_turnover && data.last_inventory_turnover) {
                    const currentTurnover = data.current_inventory_turnover;
                    const lastTurnover = data.last_inventory_turnover;

                    // Calculate the percentage change in inventory turnover
                    const changePercent = ((currentTurnover - lastTurnover) / lastTurnover) * 100;

                    // Format the current inventory turnover value to two decimal places
                    const formattedValue = currentTurnover.toFixed(2);

                    // Update the inventory turnover value in the specific div element
                    const inventoryTurnoverElement = document.querySelector('.inventory-turnover-rate .value');
                    inventoryTurnoverElement.innerHTML = formattedValue;

                    // Format the percentage change without sign
                    const formattedPercent = `${Math.abs(changePercent).toFixed(2)}%`;

                    // Update the analytics value in the specific div element
                    const analyticsElement = document.querySelector('.inventory-turnover-rate .dashboard-analytics');
                    analyticsElement.innerHTML = `<span style="color: ${changePercent >= 0 ? 'green' : 'red'}">${formattedPercent}</span> ${changePercent >= 0 ? 'more' : 'less'} than last month`;
                } else {
                    console.error('Error fetching inventory turnover:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Fetch initial data
    fetchInventoryTurnover();

    // Set interval to fetch data every 5 seconds
    setInterval(fetchInventoryTurnover, 5000); // 5,000 milliseconds = 5 seconds
});
