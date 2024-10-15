// Sample datasets for Stock Value in the modal (for Week, Month, and Year)
const stockValueModalData = {
    week: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        datasets: [
            {
                label: 'Total Stock Value',
                data: [46000, 45000, 44000, 43000, 42000, 41000, 40000],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.1)'
            },
            {
                label: 'Sold Stock Value',
                data: [4000, 5000, 6000, 7000, 8000, 9000, 10000],
                borderColor: 'rgba(75, 192, 75, 1)',
                backgroundColor: 'rgba(75, 192, 75, 0.1)'
            },
            {
                label: 'Idle Stock Value',
                data: [42000, 40000, 39000, 38000, 37000, 36000, 35000],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)'
            }
        ]
    },
    month: {
        labels: Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`),
        datasets: [
            {
                label: 'Total Stock Value',
                data: [46000, 45500, 45000, 44500, 44000, 43500, 43000, 42500, 42000, 41500, 41000, 40500, 40000, 39500, 39000, 38500, 38000, 37500, 37000, 36500, 36000, 35500, 35000, 34500, 34000, 33500, 33000, 32500, 32000, 31500],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.1)'
            },
            {
                label: 'Sold Stock Value',
                data: [5000, 6000, 7000, 8000, 9000, 10000, 11000, 12000, 13000, 14000, 15000, 16000, 17000, 18000, 19000, 20000, 21000, 22000, 23000, 24000, 25000, 26000, 27000, 28000, 29000, 30000, 31000, 32000, 33000, 34000],
                borderColor: 'rgba(75, 192, 75, 1)',
                backgroundColor: 'rgba(75, 192, 75, 0.1)'
            },
            {
                label: 'Idle Stock Value',
                data: [41000, 39500, 39000, 38500, 38000, 37500, 37000, 36500, 36000, 35500, 35000, 34500, 34000, 33500, 33000, 32500, 32000, 31500, 31000, 30500, 30000, 29500, 29000, 28500, 28000, 27500, 27000, 26500, 26000, 25500],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)'
            }
        ]
    },
    year: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
            {
                label: 'Total Stock Value',
                data: [46000, 45000, 44000, 43000, 42000, 41000, 40000, 39000, 38000, 37000, 36000, 35000],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.1)'
            },
            {
                label: 'Sold Stock Value',
                data: [5000, 10000, 15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 55000, 60000],
                borderColor: 'rgba(75, 192, 75, 1)',
                backgroundColor: 'rgba(75, 192, 75, 0.1)'
            },
            {
                label: 'Idle Stock Value',
                data: [41000, 40000, 39000, 38000, 37000, 36000, 35000, 34000, 33000, 32000, 31000, 30000],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)'
            }
        ]
    }
};

// Function to update the Stock Value Chart with new data (modal-specific)
function updateStockValueChart(chart, labels, datasets) {
    chart.data.labels = labels;
    chart.data.datasets = datasets.map(ds => ({
        ...ds,
        borderWidth: 2,
        pointRadius: 3,  // Add small points on the line
        fill: true,
    }));
    chart.update();  // Re-render the chart with the new data
}

// Initialize the Stock Value Line Chart (Modal-specific)
var stockValueModalCtx = document.getElementById('stockValueChartModal').getContext('2d');
var stockValueChartModal = new Chart(stockValueModalCtx, {
    type: 'line',
    data: {
        labels: stockValueModalData.year.labels,  // Default to year view
        datasets: stockValueModalData.year.datasets  // Default to year data
    },
    options: {
        responsive: true,  // Responsive to the container size
        maintainAspectRatio: false,  // Allows stretching with the container
        plugins: {
            legend: { display: true },  // Enable legend to show the labels for Total, Sold, and Idle Stock Values
            tooltip: {
                enabled: true,  // Enable tooltips to display the values
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: $${context.parsed.y.toLocaleString()}`;  // Show label with formatted value
                    }
                }
            }
        },
        elements: {
            line: { tension: 0.4 }  // Smooth out the line curves
        },
        scales: {
            x: {
                display: true,  // Show x-axis labels
                title: {
                    display: true,
                    text: 'Time Period',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Custom font
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Custom font
                        size: 12
                    }
                }
            },
            y: {
                display: true,  // Show y-axis labels
                title: {
                    display: true,
                    text: 'Stock Value ($)',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Custom font
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Custom font
                        size: 12
                    },
                    callback: function(value) {
                        return `$${value.toLocaleString()}`;  // Format y-axis values with commas and dollar sign
                    }
                }
            }
        }
    }
});

// Event listeners for filter buttons (modal-specific)
document.getElementById('stock-value-filter-week').addEventListener('click', () => {
    updateStockValueChart(stockValueChartModal, stockValueModalData.week.labels, stockValueModalData.week.datasets);
});

document.getElementById('stock-value-filter-month').addEventListener('click', () => {
    updateStockValueChart(stockValueChartModal, stockValueModalData.month.labels, stockValueModalData.month.datasets);
});

document.getElementById('stock-value-filter-year').addEventListener('click', () => {
    updateStockValueChart(stockValueChartModal, stockValueModalData.year.labels, stockValueModalData.year.datasets);
});