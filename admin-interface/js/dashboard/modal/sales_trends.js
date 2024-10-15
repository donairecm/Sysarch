
    // Get the context of the canvas element for the modal
    const ctxModal = document.getElementById('salesTrendsChartModal').getContext('2d');

    // Function to generate random sales data
    function generateSalesData(numProducts) {
        return Array.from({ length: numProducts }, () => Math.floor(Math.random() * 1000) + 50);
    }

    // Generate sales data based on different timeframes
    const salesDataByWeek = generateSalesData(376);
    const salesDataByMonth = generateSalesData(376);
    const salesDataByYear = generateSalesData(376);

    // Initial Data (default to weekly data)
    let currentSalesData = salesDataByWeek;

    // Calculate thresholds for top 20% and bottom 20% for the modal
    function calculateThresholds(salesData) {
        const sortedSalesData = [...salesData].sort((a, b) => b - a);
        const top20PercentThreshold = sortedSalesData[Math.floor(salesData.length * 0.2) - 1];
        const bottom20PercentThreshold = sortedSalesData[Math.floor(salesData.length * 0.8)];
        return { top20PercentThreshold, bottom20PercentThreshold };
    }

    // Function to update the chart data
    function updateChartData(salesData) {
        const { top20PercentThreshold, bottom20PercentThreshold } = calculateThresholds(salesData);

        salesTrendsChartModal.data.datasets[0].data = salesData;
        salesTrendsChartModal.data.datasets[0].backgroundColor = salesData.map(sale => {
            if (sale >= top20PercentThreshold) {
                return 'rgba(75, 192, 75, 0.7)'; // Green for top 20%
            } else if (sale <= bottom20PercentThreshold) {
                return 'rgba(255, 99, 132, 0.7)'; // Red for bottom 20%
            } else {
                return 'rgba(54, 162, 235, 0.7)'; // Blue for middle 60%
            }
        });
        salesTrendsChartModal.data.datasets[0].borderColor = salesData.map(sale => {
            if (sale >= top20PercentThreshold) {
                return 'rgba(75, 192, 75, 1)'; // Green border for top 20%
            } else if (sale <= bottom20PercentThreshold) {
                return 'rgba(255, 99, 132, 1)'; // Red border for bottom 20%
            } else {
                return 'rgba(54, 162, 235, 1)'; // Blue border for middle 60%
            }
        });

        // Update the chart
        salesTrendsChartModal.update();
    }

    // Create the initial Chart.js bar chart for modal
    const salesTrendsChartModal = new Chart(ctxModal, {
        type: 'bar',
        data: {
            labels: Array.from({ length: 376 }, (_, i) => (i + 1).toString()), // Product IDs
            datasets: [{
                data: currentSalesData, // Default sales data (week)
                backgroundColor: currentSalesData.map(sale => 'rgba(54, 162, 235, 0.7)'), // Default color
                borderColor: currentSalesData.map(sale => 'rgba(54, 162, 235, 1)'), // Default border color
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(255, 99, 132, 0.7)', // Hover color
                hoverBorderColor: 'rgba(255, 99, 132, 1)', // Hover border color
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Product ID',
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif"
                        },
                        color: '#333'
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 20,
                        color: '#666',
                        font: {
                            family: "'Afacad Flux', sans-serif"
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales Amount',
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif"
                        },
                        color: '#333'
                    },
                    beginAtZero: true,
                    ticks: {
                        color: '#666',
                        font: {
                            family: "'Afacad Flux', sans-serif"
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // No legend
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    titleFont: {
                        size: 12,
                        weight: 'bold',
                        family: "'Afacad Flux', sans-serif"
                    },
                    bodyFont: {
                        size: 12,
                        family: "'Afacad Flux', sans-serif"
                    },
                    borderColor: '#333',
                    borderWidth: 1
                }
            }
        }
    });

    // Event listeners for filter buttons
    document.getElementById('stock-trends-filter-week').addEventListener('click', function () {
        currentSalesData = salesDataByWeek;
        updateChartData(currentSalesData);
    });

    document.getElementById('stock-trends-filter-month').addEventListener('click', function () {
        currentSalesData = salesDataByMonth;
        updateChartData(currentSalesData);
    });

    document.getElementById('stock-trends-filter-year').addEventListener('click', function () {
        currentSalesData = salesDataByYear;
        updateChartData(currentSalesData);
    });
