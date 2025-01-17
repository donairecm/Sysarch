document.addEventListener('DOMContentLoaded', () => {
    const fetchUnitsRestockedAnalytics = () => {
        fetch('db_queries/calc_in_tur.php') // Adjust the path to your PHP script
            .then(response => response.json())
            .then(data => {
                if (data.currentTotalUnitsRestocked && data.lastMonthTotalUnitsRestocked) {
                    // Format current total units restocked with commas
                    const currentRestocked = Math.floor(data.currentTotalUnitsRestocked)
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    // Update the current restocked value in the DOM
                    const restockedElement = document.querySelector('.im9 .value');
                    restockedElement.innerHTML = currentRestocked;

                    // Fetch current and last month's total units restocked
                    const thisMonth = data.currentTotalUnitsRestocked;
                    const lastMonth = data.lastMonthTotalUnitsRestocked;

                    // Calculate percentage difference
                    const percentChange = ((thisMonth - lastMonth) / lastMonth) * 100;
                    const formattedPercent = `${Math.abs(percentChange).toFixed(2)}%`;

                    // Update the analytics value in the DOM
                    const analyticsElement = document.querySelector('.im9 .dashboard-analytics');
                    analyticsElement.innerHTML = `
                        <span style="color: ${percentChange >= 0 ? 'green' : 'red'}">${formattedPercent}</span> 
                        ${percentChange >= 0 ? 'more' : 'less'} than last month
                    `;
                } else {
                    console.error('Error fetching total units restocked values:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Fetch initial data
    fetchUnitsRestockedAnalytics();

    // Refresh data every 5 seconds
    setInterval(fetchUnitsRestockedAnalytics, 5000); // 5,000 milliseconds = 5 seconds
});
