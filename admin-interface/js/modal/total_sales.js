const totalSalesData = {
    week: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        data: [48000, 49000, 47000, 50000, 51000, 53000, 54000]  // Sample data for the week
    },
    month: {
        labels: Array.from({length: 30}, (_, i) => `Day ${i + 1}`),
        data: [330000, 331000, 332000, 333000, 334000, 335000, 336000, 337000, 338000, 339000,
               340000, 341000, 342000, 343000, 344000, 345000, 346000, 347000, 348000, 349000,
               350000, 351000, 352000, 353000, 354000, 355000, 356000, 357000, 358000, 359000]  // Sample data for the month
    },
    year: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                 'July', 'August', 'September', 'October', 'November', 'December'],
        data: [336413, 345000, 353000, 330000, 336000, 380000, 378000, 400000, 405000, 407000, 410000, 440000]  // Sample data for the year
    }
};

// Function to update the Total Sales Chart with new data
function updateSalesChart(chart, labels, data) {
    chart.data.labels = labels;
    chart.data.datasets[0].data = data;
    chart.update();  // Re-render the chart with the new data
}

// Initialize the Total Sales Line Chart (Modal)
var totalSalesModalCtx = document.getElementById('totalSalesChartModal').getContext('2d');
var totalSalesChartModal = new Chart(totalSalesModalCtx, {
    type: 'line',
    data: {
        labels: totalSalesData.year.labels,  // Default to year view
        datasets: [{
            data: totalSalesData.year.data,  // Default to year data
            borderColor: 'green',  // Green line
            backgroundColor: 'rgba(0, 255, 0, 0.1)',  // Light green fill
            fill: true,  // Fill the area under the line
            pointRadius: 5,  // Rounded points
            pointBackgroundColor: 'rgba(0, 255, 0, 1)',  // Green color for points
            borderWidth: 2  // Line thickness
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                enabled: true,
                callbacks: {
                    label: function(context) {
                        return `Total Sales: $${context.parsed.y.toLocaleString()}`;  // Display sales amount in tooltip
                    }
                }
            },
            legend: {
                display: false  // Disable legend
            }
        },
        scales: {
            x: {
                display: true,  // Show x-axis
                title: {
                    display: true,
                    text: 'Time',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Apply custom font
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',  // Color of the tick labels
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    }
                }
            },
            y: {
                display: true,  // Show y-axis
                title: {
                    display: true,
                    text: 'Total Sales ($)',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Apply custom font
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',  // Color of the tick labels
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    },
                    callback: function(value) {
                        return `$${value.toLocaleString()}`;  // Format y-axis values with commas and dollar sign
                    }
                },
                beginAtZero: true  // Start y-axis from 0
            }
        },
        elements: {
            line: {
                tension: 0.4  // Smooth out the line curves
            }
        }
    }
});

// Event listeners for filter buttons (with updated IDs)
document.getElementById('sales-filter-week').addEventListener('click', () => {
    updateSalesChart(totalSalesChartModal, totalSalesData.week.labels, totalSalesData.week.data);
});

document.getElementById('sales-filter-month').addEventListener('click', () => {
    updateSalesChart(totalSalesChartModal, totalSalesData.month.labels, totalSalesData.month.data);
});

document.getElementById('sales-filter-year').addEventListener('click', () => {
    updateSalesChart(totalSalesChartModal, totalSalesData.year.labels, totalSalesData.year.data);
});