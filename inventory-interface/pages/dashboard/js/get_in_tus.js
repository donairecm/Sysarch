document.addEventListener('DOMContentLoaded', () => {
    const fetchUnitsSoldAnalytics = () => {
        fetch('db_queries/calc_in_tus.php') // Adjust the path to your PHP script
            .then(response => response.json())
            .then(data => {
                if (data.current_total_units_sold && data.last_months_total_units_sold) {
                    // Format current total units sold with commas
                    const currentUnitsSold = Math.floor(data.current_total_units_sold)
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    // Update the current units sold value in the DOM
                    const unitsSoldElement = document.querySelector('.im8 .value');
                    unitsSoldElement.innerHTML = currentUnitsSold;

                    // Fetch current and last month's total units sold
                    const thisMonth = data.current_total_units_sold;
                    const lastMonth = data.last_months_total_units_sold;

                    // Calculate percentage difference
                    const percentChange = ((thisMonth - lastMonth) / lastMonth) * 100;
                    const formattedPercent = `${Math.abs(percentChange).toFixed(2)}%`;

                    // Update the analytics value in the DOM
                    const analyticsElement = document.querySelector('.im8 .dashboard-analytics');
                    analyticsElement.innerHTML = `
                        <span style="color: ${percentChange >= 0 ? 'green' : 'red'}">${formattedPercent}</span> 
                        ${percentChange >= 0 ? 'more' : 'less'} than last month
                    `;
                } else {
                    console.error('Error fetching total units sold values:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Fetch initial data
    fetchUnitsSoldAnalytics();

    // Refresh data every 5 seconds
    setInterval(fetchUnitsSoldAnalytics, 5000); // 5,000 milliseconds = 5 seconds
});
