const inventoryCtx = document.getElementById('inventoryTurnoverChart').getContext('2d');

// Realistic inventory turnover rates for 12 months, based on 376 total items
const inventoryTurnoverData = [30, 28, 35, 40, 45, 32, 38, 37, 42, 36, 38, 35]; 

const inventoryTurnoverChart = new Chart(inventoryCtx, {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            data: inventoryTurnoverData, 
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        aspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Turnover Rate',
                    font: {
                        family: "'Afacad Flux', sans-serif"
                    }
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Months',
                    font: {
                        family: "'Afacad Flux', sans-serif"
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false  // Removed legend
            },
            title: {
                display: false  // Removed the chart title
            }
        }
    }
});
