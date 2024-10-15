document.addEventListener('DOMContentLoaded', function () {
    const inventoryTurnoverCtx = document.getElementById('inventoryTurnoverChart').getContext('2d');

    // Sample data: inventory turnover for 12 months (example values)
    const turnoverData = [3.5, 4.0, 2.8, 3.7, 4.5, 5.2, 4.9, 3.3, 4.7, 4.1, 5.0, 3.9];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Define the thresholds for good (green) and bad (red) turnover
    const goodTurnoverThreshold = 4.5; // Example: above 4.5 is considered good
    const badTurnoverThreshold = 3.0;  // Example: below 3.0 is considered bad

    new Chart(inventoryTurnoverCtx, {
        type: 'line',
        data: {
            labels: months, // X-axis labels (shortened month names)
            datasets: [
                {
                    label: 'Turnover Ratio',
                    data: turnoverData, // Y-axis data
                    borderColor: '#4a90e2', // Softer blue color for the main line
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 3, // Smaller points for cleaner appearance
                },
                {
                    label: 'Bad Turnover Threshold',
                    data: Array(12).fill(badTurnoverThreshold), // Red dashed line for bad turnover
                    borderColor: '#f44336', // Softer red
                    borderDash: [5, 5], // Dashed line
                    borderWidth: 1,
                    fill: false,
                },
                {
                    label: 'Good Turnover Threshold',
                    data: Array(12).fill(goodTurnoverThreshold), // Green dashed line for good turnover
                    borderColor: '#4caf50', // Softer green
                    borderDash: [5, 5], // Dashed line
                    borderWidth: 1,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Disable aspect ratio to allow responsiveness
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
                        text: 'Month',
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
                        text: 'Turnover Ratio',
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
                    display: false, // Hide legend to keep it cleaner
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
