// Get the context of the canvas element
const ctx = document.getElementById('salesTrendsChart').getContext('2d');

// Generate random sales data for 376 products
const numProducts = 376;
const productIds = Array.from({ length: numProducts }, (_, i) => (i + 1).toString());
const salesData = Array.from({ length: numProducts }, () => Math.floor(Math.random() * 1000) + 50); // Random sales between 50 and 1050

// Calculate thresholds for top 20% and bottom 20%
const sortedSalesData = [...salesData].sort((a, b) => b - a);
const top20PercentThreshold = sortedSalesData[Math.floor(numProducts * 0.2) - 1];
const bottom20PercentThreshold = sortedSalesData[Math.floor(numProducts * 0.8)];

// Create the Chart.js bar chart
const salesTrendsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: productIds, // Use product IDs as labels
        datasets: [{
            data: salesData, // The random sales data
            backgroundColor: salesData.map(sale => {
                if (sale >= top20PercentThreshold) {
                    return 'rgba(75, 192, 75, 0.7)'; // Green for top 20%
                } else if (sale <= bottom20PercentThreshold) {
                    return 'rgba(255, 99, 132, 0.7)'; // Red for bottom 20%
                } else {
                    return 'rgba(54, 162, 235, 0.7)'; // Default blue for middle 60%
                }
            }),
            borderColor: salesData.map(sale => {
                if (sale >= top20PercentThreshold) {
                    return 'rgba(75, 192, 75, 1)'; // Green border for top 20%
                } else if (sale <= bottom20PercentThreshold) {
                    return 'rgba(255, 99, 132, 1)'; // Red border for bottom 20%
                } else {
                    return 'rgba(54, 162, 235, 1)'; // Default blue border for middle 60%
                }
            }),
            borderWidth: 1,
            hoverBackgroundColor: 'rgba(255, 99, 132, 0.7)', // Color on hover
            hoverBorderColor: 'rgba(255, 99, 132, 1)',
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
                        family: "'Afacad Flux', sans-serif" // Custom font family
                    },
                    color: '#333'
                },
                ticks: {
                    autoSkip: true,
                    maxTicksLimit: 20, // Limit number of ticks for readability
                    color: '#666',
                    font: {
                        family: "'Afacad Flux', sans-serif" // Custom font family
                    }
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Sales Amount',
                    font: {
                        size: 14,
                        family: "'Afacad Flux', sans-serif" // Custom font family
                    },
                    color: '#333'
                },
                beginAtZero: true,
                ticks: {
                    color: '#666',
                    font: {
                        family: "'Afacad Flux', sans-serif" // Custom font family
                    }
                }
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false // Remove the 'Sales Data' label at the top
            },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleColor: '#fff',
                bodyColor: '#fff',
                titleFont: {
                    size: 12,
                    weight: 'bold',
                    family: "'Afacad Flux', sans-serif" // Custom font for tooltip title
                },
                bodyFont: {
                    size: 12,
                    family: "'Afacad Flux', sans-serif" // Custom font for tooltip body
                },
                borderColor: '#333',
                borderWidth: 1
            }
        }
    }
});
