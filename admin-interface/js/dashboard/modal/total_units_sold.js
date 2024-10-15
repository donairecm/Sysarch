// Sample datasets for Total Units Sold for Week, Month, and Year
const totalUnitsSoldData = {
    week: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        data: [7000, 8000, 7500, 7800, 8200, 8500, 8700]  // Sample data for the week
    },
    month: {
        labels: Array.from({length: 30}, (_, i) => `Day ${i + 1}`),
        data: [5000, 5100, 5150, 5200, 5250, 5300, 5350, 5400, 5500, 5600, 
               5700, 5750, 5800, 5850, 5900, 6000, 6100, 6150, 6200, 6250,
               6300, 6400, 6500, 6550, 6600, 6650, 6700, 6750, 6800, 6850]  // Sample data for the month
    },
    year: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                 'July', 'August', 'September', 'October', 'November', 'December'],
        data: [50000, 51000, 50500, 52000, 53000, 52500, 54000, 55000, 54500, 56000, 57000, 56500]  // Sample data for the year
    }
};

// Function to update the Total Units Sold Chart with new data
function updateUnitsSoldChart(chart, labels, data) {
    chart.data.labels = labels;
    chart.data.datasets[0].data = data;
    chart.update();  // Re-render the chart with the new data
}

// Initialize the Total Units Sold Line Chart (Modal)
var totalUnitsSoldModalCtx = document.getElementById('unitsSoldChartModal').getContext('2d');
var totalUnitsSoldChartModal = new Chart(totalUnitsSoldModalCtx, {
    type: 'line',
    data: {
        labels: totalUnitsSoldData.year.labels,  // Default to year view
        datasets: [{
            data: totalUnitsSoldData.year.data,  // Default to year data
            borderColor: 'blue',  // Blue line
            backgroundColor: 'rgba(0, 0, 255, 0.1)',  // Light blue fill
            fill: true,  // Fill the area under the line
            pointRadius: 5,  // Rounded points
            pointBackgroundColor: 'rgba(0, 0, 255, 1)',  // Blue color for points
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
                        return `Units Sold: ${context.parsed.y.toLocaleString()}`;  // Display units sold with commas
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
                        family: "'Afacad Flux', sans-serif",  // Custom font
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
                    text: 'Units Sold',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",  // Custom font
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
                        return value.toLocaleString();  // Format y-axis values with commas
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

// Event listeners for filter buttons
document.getElementById('filter-week').addEventListener('click', () => {
    updateUnitsSoldChart(totalUnitsSoldChartModal, totalUnitsSoldData.week.labels, totalUnitsSoldData.week.data);
});

document.getElementById('filter-month').addEventListener('click', () => {
    updateUnitsSoldChart(totalUnitsSoldChartModal, totalUnitsSoldData.month.labels, totalUnitsSoldData.month.data);
});

document.getElementById('filter-year').addEventListener('click', () => {
    updateUnitsSoldChart(totalUnitsSoldChartModal, totalUnitsSoldData.year.labels, totalUnitsSoldData.year.data);
});
