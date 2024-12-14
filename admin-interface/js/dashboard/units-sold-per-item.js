// Generate random units sold for 376 items
const items = Array.from({ length: 376 }, (_, i) => `AL-${i.toString().padStart(3, '0')}`);
const unitsSold = Array.from({ length: 376 }, () => Math.floor(Math.random() * 500));

// Chart.js configuration
const ctx = document.getElementById('dashboardUnitsSoldperItem').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: items, // Item names as x-axis labels
        datasets: [{
            data: unitsSold, // Units sold as data points
            borderColor: 'rgb(67, 122, 22)',

            borderWidth: 1.4, // Make the lines thinner
            pointRadius: 2,
            pointHoverRadius: 8, // Increase hover radius
        }]
    },
    options: {
        maintainAspectRatio: false, // Disable maintaining aspect ratio
        responsive: true, // Enable responsiveness
        plugins: {
            legend: {
                display: false, // Remove the legend
                labels: {
                    font: {
                        family: "'Afacad Flux', sans-serif"
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `Units sold: ${context.raw}`; // Add "Units sold:" before the count
                    }
                },
                bodyFont: {
                    family: "'Afacad Flux', sans-serif" // Set tooltip font family
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    display: false,
                    maxRotation: 90,
                    minRotation: 45,
                    font: {
                        family: "'Afacad Flux', sans-serif"
                    }
                },
                grid: {
                    display: false // Remove vertical grid lines
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    display: true,
                    font: {
                        family: "'Afacad Flux', sans-serif"
                    }
                },
                grid: {
                    display: true // Keep horizontal grid lines
                }
            }
        }
    }
});
