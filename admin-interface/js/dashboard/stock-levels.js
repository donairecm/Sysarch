// Generate random values for stock levels with specific limits for out of stock
const generateStockDataForDashboard = (minTotal, maxTotal, itemCount) => {
    const stockLevels = ['Overstocked', 'Normal', 'Low', 'Critical', 'Out of Stock'];
    let randomValues = stockLevels.map(level => {
        if (level === 'Out of Stock') {
            // Generate between 2-3 and 6-8 for out of stocks
            return Math.floor(Math.random() * (8 - 3 + 1)) + 3;
        } else {
            return Math.floor(Math.random() * (maxTotal - minTotal + 1)) + minTotal;
        }
    });

    // Scale values to meet the total item count
    let total = randomValues.reduce((acc, value) => acc + value, 0);
    const scaleFactor = itemCount / total;

    // Adjust the values to ensure they meet the item count without being equal
    let adjustedValues = randomValues.map(value => Math.round(value * scaleFactor));
    total = adjustedValues.reduce((acc, value) => acc + value, 0);

    // Ensure total matches the item count by adjusting the first value if necessary
    if (total !== itemCount) {
        adjustedValues[0] += itemCount - total;
    }

    return adjustedValues;
};

// Generate stock data for dashboard
const stockDataDashboard = generateStockDataForDashboard(50, 100, 376);

// Calculate the sum of low, critical, and out of stock items
const lowStockDashboard = stockDataDashboard[2];      // Low
const criticalStockDashboard = stockDataDashboard[3]; // Critical
const outOfStockDashboard = stockDataDashboard[4];    // Out of Stock
const totalNeedsRestockingDashboard = lowStockDashboard + criticalStockDashboard + outOfStockDashboard;

// Display the total needs restocking
document.querySelector('.needs-restocking').innerText = `${totalNeedsRestockingDashboard}`;

// Map abbreviated labels to full descriptions
const labelMapDashboard = {
    'O': 'Overstocked',
    'N': 'Normal',
    'L': 'Low',
    'C': 'Critical',
    'OS': 'Out of Stock'
};

// Abbreviated labels for the x-axis
const abbreviatedLabelsDashboard = ['O', 'N', 'L', 'C', 'OS'];

// Determine the highest value in the dataset
const highestValueDashboard = Math.max(...stockDataDashboard);

// Calculate the max value for the y-axis
const calculateYAxisMaxDashboard = (value) => {
    const nextMultipleOf20 = Math.ceil(value / 20) * 20; // Round up to the nearest multiple of 20
    return nextMultipleOf20 + 20; // Add an extra 20 for headroom
};

// Dynamically calculate y-axis max value
const yAxisMaxDashboard = calculateYAxisMaxDashboard(highestValueDashboard);

// Create the chart
const ctxDashboard = document.getElementById('dashboardStockLevelChart').getContext('2d');
const dashboardStockLevelChart = new Chart(ctxDashboard, {
    type: 'bar',
    data: {
        labels: abbreviatedLabelsDashboard, // Use abbreviated labels for x-axis
        datasets: [{
            data: stockDataDashboard,
            backgroundColor: [
                'rgb(67, 122, 22)', // Overstocked (Green)
                'rgb(67, 122, 22)', // Normal (Lighter Green)
                'rgb(218, 201, 55)', // Low (Yellow)
                'rgb(187, 92, 28)', // Critical (Amber)
                'rgb(163, 26, 26)'  // Out of Stock (Red)
            ],
            borderWidth: 1,
            borderRadius: 10 // Add border radius here
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
                        family: "'Afacad Flux', sans-serif" // Change legend font family
                    }
                }
            },
            tooltip: {
                bodyFont: {
                    family: "'Afacad Flux', sans-serif" // Change tooltip font family
                },
                callbacks: {
                    title: function () {
                        // Remove the header in the tooltip
                        return ''; 
                    },
                    label: function (tooltipItem) {
                        const value = tooltipItem.raw || 0;
                        const total = stockDataDashboard.reduce((sum, current) => sum + current, 0);
                        const percentage = ((value / total) * 100).toFixed(2);

                        // Map the tooltip label to the full description
                        const abbreviatedLabel = tooltipItem.label; // This will be the key like 'O', 'N', etc.
                        const fullLabel = labelMapDashboard[abbreviatedLabel] || abbreviatedLabel;

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
                formatter: function (value) {
                    return value;
                }
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
                        family: "'Afacad Flux', sans-serif" // Change x-axis ticks font family
                    }
                },
                grid: { display: false } // Remove grid lines for x-axis
            },
            y: {
                ticks: {
                    display: true, // Display the numeric counts on the y-axis
                    beginAtZero: true,
                    font: {
                        family: "'Afacad Flux', sans-serif" // Change y-axis ticks font family
                    }
                },
                max: yAxisMaxDashboard // Set dynamically calculated max value
            }
        }
    },
    plugins: [ChartDataLabels] // Register the plugin
});
