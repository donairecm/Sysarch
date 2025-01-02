const inventorydashboardInventoryMovementsCtx = document.getElementById('inventorydashboardInventoryMovements').getContext('2d');

const inventorydashboardInventoryMovementsData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
        {
            label: 'Received',
            data: [50, 70, 40, 80, 60, 90, 120, 100, 70, 60, 90, 110],
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
            data: [30, 50, 60, 40, 70, 60, 100, 80, 90, 50, 70, 100],
            borderColor: 'rgb(190, 43, 43)',
            pointBackgroundColor:  'rgb(190, 43, 43)',
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
                    size: 14 // Adjust legend font size here
                },
                boxWidth: 20, // Width of the legend box 
            }
        },
        tooltip: {
            callbacks: {
                title: (tooltipItems) => {
                    const monthNames = [
                        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    return monthNames[tooltipItems[0].label];
                }
            }
        }
    },
    scales: {
        x: {
            title: {
                display: false,
                text: 'Months',
                font: {
                    family: "'Afacad Flux', sans-serif",
                    size: 14 // Adjust X-axis title font size here
                }
            },
            ticks: {
                font: {
                    size: 12 // Adjust X-axis labels font size here
                }
            },
            grid: {
                color: 'rgba(200, 200, 200, 0.2)' // Change grid color here
            }
        },
        y: {
            title: {
                display: false,
                text: 'Quantity Moved',
                font: {
                    family: "'Afacad Flux', sans-serif",
                    size: 12 // Adjust Y-axis title font size here
                }
            },
            ticks: {
                font: {
                    size: 12 // Adjust Y-axis labels font size here
                }
            },
            grid: {
                color: 'rgba(200, 200, 200, 0.2)' // Change grid color here
            },
            beginAtZero: true,
            max: 140
        }
    }
};

new Chart(inventorydashboardInventoryMovementsCtx, {
    type: 'line',
    data: inventorydashboardInventoryMovementsData,
    options: inventorydashboardInventoryMovementsOptions
});