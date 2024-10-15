document.addEventListener('DOMContentLoaded', function () {
    const inventoryLevelsCtx = document.getElementById('inventoryLevelsChart').getContext('2d');

    // Generate random stock data for 376 items
    const stockData = [];
    const labels = [];
    for (let i = 1; i <= 376; i++) {
        labels.push(i); // Use numbers 1 through 376 for item labels
        stockData.push(Math.floor(Math.random() * 1000)); // Random stock count between 0 and 1000
    }

    const lowStockLevel = 200;
    const highStockLevel = 800;

    new Chart(inventoryLevelsCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Stock Count',
                    data: stockData,
                    borderColor: '#4a90e2', // Softer blue color for the main line
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 1, // Smaller points for cleaner appearance
                },
                {
                    label: 'Low Stock Level',
                    data: Array(376).fill(lowStockLevel),
                    borderColor: '#f44336', // Softer red for low stock
                    borderDash: [5, 5],
                    borderWidth: 1,
                    fill: false,
                },
                {
                    label: 'High Stock Level',
                    data: Array(376).fill(highStockLevel),
                    borderColor: '#4caf50', // Softer green for high stock
                    borderDash: [5, 5],
                    borderWidth: 1,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: {
                    tension: 0.3, // Smooth out the line a bit for aesthetics
                },
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Item Number',
                        color: '#666', // Softer grey text
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif", // Apply the custom font
                        },
                    },
                    grid: {
                        display: false, // Hide vertical grid lines for cleaner look
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif", // Apply the custom font
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Stock Count',
                        color: '#666', // Softer grey text
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif", // Apply the custom font
                        },
                    },
                    grid: {
                        color: '#e0e0e0', // Lighter grid lines for subtlety
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif", // Apply the custom font
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: false, // Hide legend to make it cleaner
                },
                tooltip: {
                    backgroundColor: '#333', // Darker background for tooltip
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 4, // Rounded tooltip corners
                    xPadding: 10, // More padding for readability
                    yPadding: 10,
                    titleFont: {
                        family: "'Afacad Flux', sans-serif", // Apply custom font to tooltip title
                    },
                    bodyFont: {
                        family: "'Afacad Flux', sans-serif", // Apply custom font to tooltip body
                    },
                },
            },
        },
    });
});
