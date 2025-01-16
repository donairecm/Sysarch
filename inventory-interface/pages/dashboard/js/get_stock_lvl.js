document.addEventListener('DOMContentLoaded', () => {
    const fetchStockDataForDashboard = () => {
        fetch('db_queries/fetch_stock_data.php') // Adjust the path to your PHP script
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching stock data:', data.error);
                    return;
                }

                // Extract stock levels from API response
                const stockDataDashboard = [
                    data.overstocked,
                    data.normal,
                    data.low,
                    data.critical,
                    data.outOfStock
                ];

                // Calculate total needs restocking
                const totalNeedsRestockingDashboard =
                    data.low + data.critical + data.outOfStock;

                // Update "Needs Restocking" count in the DOM
                document.querySelector('.needs-restocking').innerText = `${totalNeedsRestockingDashboard}`;

                // Determine the highest value in the dataset
                const highestValueDashboard = Math.max(...stockDataDashboard);

                // Calculate max value for y-axis
                const calculateYAxisMaxDashboard = (value) => {
                    const nextMultipleOf20 = Math.ceil(value / 20) * 20; // Round up to nearest multiple of 20
                    return nextMultipleOf20 + 20; // Add extra headroom
                };
                const yAxisMaxDashboard = calculateYAxisMaxDashboard(highestValueDashboard);

                // Update the chart data while preserving existing options and plugins
                dashboardStockLevelChart.data.datasets[0].data = stockDataDashboard;
                dashboardStockLevelChart.options.scales.y.max = yAxisMaxDashboard;
                dashboardStockLevelChart.update();
            })
            .catch(error => console.error('Error:', error));
    };

    // Create the chart with your custom configuration
    const ctxDashboard = document.getElementById('dashboardStockLevelChart').getContext('2d');
    const dashboardStockLevelChart = new Chart(ctxDashboard, {
        type: 'bar',
        data: {
            labels: ['O', 'N', 'L', 'C', 'OS'], // Abbreviated labels
            datasets: [{
                data: [], // Placeholder data; will be updated dynamically
                backgroundColor: [
                    'rgb(22, 122, 67)', // Overstocked (Green)
                    'rgb(67, 122, 22)', // Normal (Lighter Green)
                    'rgb(218, 201, 55)', // Low (Yellow)
                    'rgb(187, 92, 28)', // Critical (Amber)
                    'rgb(163, 26, 26)'  // Out of Stock (Red)
                ],
                borderWidth: 1,
                borderRadius: 10 // Keep the rounded corners
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        font: {
                            family: "'Afacad Flux', sans-serif" // Legend font family
                        }
                    }
                },
                tooltip: {
                    bodyFont: {
                        family: "'Afacad Flux', sans-serif" // Tooltip font family
                    },
                    callbacks: {
                        title: () => '', // Remove header in tooltip
                        label: (tooltipItem) => {
                            const value = tooltipItem.raw || 0;
                            const total = stockDataDashboard.reduce((sum, current) => sum + current, 0);
                            const percentage = ((value / total) * 100).toFixed(2);

                            // Map tooltip label to full description
                            const labelsMap = {
                                'O': 'Overstocked',
                                'N': 'Normal',
                                'L': 'Low',
                                'C': 'Critical',
                                'OS': 'Out of Stock'
                            };
                            const fullLabel = labelsMap[tooltipItem.label] || tooltipItem.label;

                            return `${fullLabel}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: 'rgba(39, 39, 39, 0.747)',
                    font: {
                        family: '"Baloo Paaji 2", sans-serif',
                        weight: 500,
                        size: 14
                    },
                    formatter: (value) => value // Show raw values on bars
                }
            },
            scales: {
                x: {
                    ticks: {
                        display: true, // Show stock level abbreviations
                        callback: function (value, index) {
                            return this.getLabelForValue(index); // Use abbreviated labels
                        },
                        font: {
                            family: "'Afacad Flux', sans-serif" // X-axis ticks font family
                        }
                    },
                    grid: { display: false } // Remove grid lines for x-axis
                },
                y: {
                    ticks: {
                        display: true, // Display the numeric counts on the y-axis
                        beginAtZero: true,
                        font: {
                            family: "'Afacad Flux', sans-serif" // Y-axis ticks font family
                        }
                    },
                    max: 100 // Placeholder; dynamically updated later
                }
            }
        },
        plugins: [ChartDataLabels] // Keep the Chart.js Data Labels plugin
    });

    // Fetch initial data
    fetchStockDataForDashboard();

    // Refresh every 5 seconds
    setInterval(fetchStockDataForDashboard, 5000); // 5,000 milliseconds = 5 seconds
});
