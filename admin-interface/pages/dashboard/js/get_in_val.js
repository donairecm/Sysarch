document.addEventListener('DOMContentLoaded', () => {
    const fetchInventoryValues = () => {
        fetch('db_queries/calc_in_val.php') // Adjust the path to your PHP script
            .then(response => response.json())
            .then(data => {
                if (data.thisMonthInventoryValue && data.lastMonthInventoryValue) {
                    const thisMonthValue = Math.floor(data.thisMonthInventoryValue)
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    // Update the inventory value in the DOM
                    const inventoryValueElement = document.querySelector('.im1 .value');
                    inventoryValueElement.innerHTML = `<span>₱</span>${thisMonthValue}`; // Add the peso sign

                    const thisMonth = data.thisMonthInventoryValue;
                    const lastMonth = data.lastMonthInventoryValue;

                    // Calculate percentage difference
                    const percentChange = ((thisMonth - lastMonth) / lastMonth) * 100;
                    const formattedPercent = `${Math.abs(percentChange).toFixed(2)}%`; // Format without sign

                    // Update the analytics value in the DOM
                    const analyticsElement = document.querySelector('.im1 .dashboard-analytics');
                    analyticsElement.innerHTML = `
                        <span style="color: ${percentChange >= 0 ? 'green' : 'red'}">${formattedPercent}</span> 
                        ${percentChange >= 0 ? 'more' : 'less'} than last month
                    `;
                } else {
                    console.error('Error fetching inventory values:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Fetch initial data
    fetchInventoryValues();

    // Refresh data every 5 seconds
    setInterval(fetchInventoryValues, 5000); // 5,000 milliseconds = 5 seconds
});
