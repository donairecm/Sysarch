document.addEventListener('DOMContentLoaded', function () {
    const supplierDetailsCtx = document.getElementById('supplierDetailsChart').getContext('2d');

    // Sample data: Orders per supplier over 12 months (example values for 3 suppliers)
    const supplier1Orders = [20, 25, 18, 22, 30, 28, 35, 40, 38, 33, 27, 29];
    const supplier2Orders = [18, 20, 22, 25, 26, 30, 34, 37, 40, 38, 35, 32];
    const supplier3Orders = [15, 18, 16, 22, 24, 26, 28, 31, 29, 27, 25, 28];
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    new Chart(supplierDetailsCtx, {
        type: 'line',
        data: {
            labels: months, // X-axis labels (shortened month names)
            datasets: [
                {
                    label: 'Supplier 1',
                    data: supplier1Orders,
                    borderColor: '#4a90e2', // Blue color for supplier 1
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4a90e2',
                    pointBorderWidth: 2,
                    fill: false,
                    tension: 0.3, // Slight curve for a smoother line
                },
                {
                    label: 'Supplier 2',
                    data: supplier2Orders,
                    borderColor: '#4caf50', // Green color for supplier 2
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4caf50',
                    pointBorderWidth: 2,
                    fill: false,
                    tension: 0.3, // Slight curve for a smoother line
                },
                {
                    label: 'Supplier 3',
                    data: supplier3Orders,
                    borderColor: '#f44336', // Red color for supplier 3
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f44336',
                    pointBorderWidth: 2,
                    fill: false,
                    tension: 0.3, // Slight curve for a smoother line
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Disable aspect ratio to allow responsiveness
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Month',
                        color: '#666', // Softer grey text
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif", // Custom font
                        },
                    },
                    grid: {
                        display: false, // Hide vertical grid lines for cleaner look
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif", // Custom font
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Orders',
                        color: '#666', // Softer grey text
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif", // Custom font
                        },
                    },
                    grid: {
                        color: '#e0e0e0', // Lighter grid lines for subtlety
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif", // Custom font
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: false, // Hide legend to remove Supplier 1, 2, 3 labels
                },
                tooltip: {
                    backgroundColor: '#333', // Darker background for tooltip
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 6, // Slightly rounded tooltip corners
                    xPadding: 12, // More padding for readability
                    yPadding: 12,
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
