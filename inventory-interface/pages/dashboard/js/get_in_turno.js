document.addEventListener('DOMContentLoaded', () => {
    // Fetch the current and last month's inventory turnover from the backend
    fetch('db_queries/calc_in_turno.php') // Adjust the path to your PHP script
        .then(response => response.json())
        .then(data => {
            if (data.current_inventory_turnover && data.last_inventory_turnover) {
                // Calculate the change in inventory turnover
                const change = data.current_inventory_turnover - data.last_inventory_turnover;

                // Format the inventory turnover value to two decimal places
                const formattedValue = data.current_inventory_turnover.toFixed(2);

                // Update the inventory turnover value in the DOM
                const inventoryTurnoverElement = document.querySelector('.im2 .value');
                inventoryTurnoverElement.innerHTML = formattedValue;

                // Determine the sign for the change
                const sign = change >= 0 ? '+' : '-';

                // Update the analytics value in the DOM
                const analyticsElement = document.querySelector('.im2 .dashboard-analytics');
                analyticsElement.innerHTML = `<span style="color: ${change >= 0 ? 'green' : 'red'}">${sign}${Math.abs(change).toFixed(2)}</span> ${change >= 0 ? 'more' : 'less'} than last month`;
            } else {
                console.error('Error fetching inventory turnover:', data.error || 'Unknown error');
            }
        })
        .catch(error => console.error('Error:', error));
});
