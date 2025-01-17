document.addEventListener('DOMContentLoaded', () => {
    const inventorydashboardInventoryMovementsCtx = document
        .getElementById('inventorydashboardInventoryMovements')
        .getContext('2d');

    const currentDate = new Date();
    const currentMonth = currentDate.getMonth(); // 0-indexed current month
    const currentYear = currentDate.getFullYear();

    // Chart data
    const inventorydashboardInventoryMovementsData = {
        labels: Array.from({ length: currentDate.getDate() }, (_, i) => i + 1), // Only numbers for days (1, 2, 3, ...) up to today
        datasets: [
            {
                label: 'Received',
                data: Array(currentDate.getDate()).fill(0), // Placeholder for daily data
                borderColor: 'rgb(36, 112, 21)',
                pointBackgroundColor: 'rgb(36, 112, 21)',
                tension: 0.4,
                fill: false,
                borderWidth: 1.5,
                pointRadius: 3,
                pointHitRadius: 10,
                pointHoverRadius: 6
            },
            {
                label: 'Delivered',
                data: Array(currentDate.getDate()).fill(0), // Placeholder for daily data
                borderColor: 'rgb(190, 43, 43)',
                pointBackgroundColor: 'rgb(190, 43, 43)',
                tension: 0.4,
                fill: false,
                borderWidth: 1.5,
                pointRadius: 3,
                pointHitRadius: 10,
                pointHoverRadius: 6
            }
        ]
    };

    const inventorydashboardInventoryMovementsOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
                labels: {
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 14
                    },
                    boxWidth: 20
                }
            },
            tooltip: {
                callbacks: {
                    title: (tooltipItems) => {
                        const day = tooltipItems[0].label; // Day from x-axis
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        const monthAbbreviation = monthNames[currentMonth];
                        return `${monthAbbreviation} ${day}, ${currentYear}`; // Tooltip format: "Jan 1, 2025"
                    }
                }
            }
        },
        scales: {
            x: {
                title: { display: false },
                ticks: { font: { size: 12 } },
                grid: { color: 'rgba(200, 200, 200, 0.2)' }
            },
            y: {
                title: { display: false },
                ticks: { font: { size: 12 } },
                grid: { color: 'rgba(200, 200, 200, 0.2)' },
                beginAtZero: true
            }
        }
    };

    const inventorydashboardInventoryMovementsChart = new Chart(inventorydashboardInventoryMovementsCtx, {
        type: 'line',
        data: inventorydashboardInventoryMovementsData,
        options: inventorydashboardInventoryMovementsOptions
    });

    const fetchDataAndUpdateChart = () => {
        fetch('db_queries/fetch_in_mvh.php')
            .then(response => response.json())
            .then(data => {
                if (data.restocked && data.delivered) {
                    const restockedDailyArray = Array.from({ length: currentDate.getDate() }, (_, i) =>
                        data.restocked.daily[i + 1] || 0
                    );
                    const deliveredDailyArray = Array.from({ length: currentDate.getDate() }, (_, i) =>
                        data.delivered.daily[i + 1] || 0
                    );

                    // Update chart data
                    inventorydashboardInventoryMovementsData.labels = Array.from(
                        { length: currentDate.getDate() },
                        (_, i) => i + 1
                    );
                    inventorydashboardInventoryMovementsData.datasets[0].data = restockedDailyArray;
                    inventorydashboardInventoryMovementsData.datasets[1].data = deliveredDailyArray;

                    inventorydashboardInventoryMovementsChart.update();
                } else {
                    console.error('Error fetching inventory movements:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Initial fetch
    fetchDataAndUpdateChart();

    // Refresh data every 5 seconds
    setInterval(fetchDataAndUpdateChart, 5000);
});
