// Function to convert percentage string to a float
function parsePercent(value) {
    return parseFloat(value.replace('%', ''));
}

// Doughnut Chart for Units Sold (Chart 1)
const unitsSoldVal = parseInt(document.querySelector('.units-sold .val').innerText.replace(',', ''));
const unitsSoldAdded = parsePercent(document.querySelector('.units-sold .percent').innerText);

const ctx1 = document.getElementById('doughnutChart1').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: [['Recently', 'sold units'], ['Units', 'sold']],
        datasets: [{
            data: [unitsSoldVal * (unitsSoldAdded / 100), unitsSoldVal ],
            backgroundColor: ['#5be400', '#1281a3'], // added | total
            borderColor: 'transparent',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        layout: {
            padding: {
                top:5,
                bottom: 5,
                left: 5,
                right: 5
            }
           },
           plugins: {
            legend: {
                display: false, // Disables the legend (labels)
                labels: {
                    font: {
                        size: 16, // Font size for labels
                        family: "'Afacad Flux', sans-serif", // Use custom font
                    },
                    color: '#000', // Font color for labels
                }
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const value = tooltipItem.raw; // Accessing the raw data value
                        return value.toLocaleString(undefined, { 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 0 
                        }); 
                    }
                },
                backgroundColor: '#333', // Tooltip background color
                titleFont: {
                    size: 10, // Smaller title font size
                },
                bodyFont: {
                    size: 10, // Smaller body font size
                },
                padding: 6, // Smaller padding inside tooltips
                cornerRadius: 3, // Reduce tooltip corner radius
                
            }
        }
    }
});

// Doughnut Chart for Total Sales (Chart 2)
const totalSalesVal = parseFloat(document.querySelector('.total-sales .val').innerText.replace(/[^0-9.-]+/g, ""));
const totalSalesAdded = parsePercent(document.querySelector('.total-sales .percent').innerText);

const ctx2 = document.getElementById('doughnutChart2').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: [['Sales', 'made',], ['Total', 'Sales']],
        datasets: [{
            data: [totalSalesVal * (totalSalesAdded / 100), totalSalesVal],
            backgroundColor: ['#5be400', '#1281a3'],
            borderColor: 'transparent',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        layout: {
            padding: {
                top:5,
                bottom: 5,
                left: 5,
                right: 5
            }
           },
        plugins: {
            legend: {
                display: false, // Disables the legend (labels)
                labels: {
                    font: {
                        size: 16, // Font size for labels
                        family: "'Afacad Flux', sans-serif", // Use custom font
                    },
                    color: '#000', // Font color for labels
                }
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const value = tooltipItem.raw; // Accessing the raw data value
                        return '₱' + value.toLocaleString(undefined, { 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 0 
                        }); // Add peso sign to the value and remove decimals
                    }
                },
                backgroundColor: '#333', // Tooltip background color
                titleFont: {
                    size: 10, // Smaller title font size
                },
                bodyFont: {
                    size: 10, // Smaller body font size
                },
                padding: 6, // Smaller padding inside tooltips
                cornerRadius: 3, // Reduce tooltip corner radius
                
            }
        }
    }
});


// Doughnut Chart for Stock Value (Chart 3)
const stockValueVal = parseFloat(document.querySelector('.stock-value .val').innerText.replace(/[^0-9.-]+/g, ""));
const outgoingStock = parsePercent(document.querySelector('.stock-value .outgoing').innerText);
const incomingStock = parsePercent(document.querySelector('.stock-value .incoming').innerText);

const ctx3 = document.getElementById('doughnutChart3').getContext('2d');
new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: ['Incoming', 'Outgoing', ['Stock', 'value']],
        datasets: [{
            data: [stockValueVal * (incomingStock / 100), stockValueVal * (outgoingStock / 100), stockValueVal],
            backgroundColor: ['#5be400', '#c71f1f', '#1281a3'],
            borderColor: 'transparent',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        layout: {
            padding: {
                top:5,
                bottom: 5,
                left: 5,
                right: 5
            }
           },
           plugins: {
            legend: {
                display: false, // Disables the legend (labels)
                labels: {
                    font: {
                        size: 16, // Font size for labels
                        family: "'Afacad Flux', sans-serif", // Use custom font
                    },
                    color: '#000', // Font color for labels
                }
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const value = tooltipItem.raw; // Accessing the raw data value
                        return '₱' + value.toLocaleString(undefined, { 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 0 
                        }); // Add peso sign to the value and remove decimals
                    }
                },
                backgroundColor: '#333', // Tooltip background color
                titleFont: {
                    size: 10, // Smaller title font size
                },
                bodyFont: {
                    size: 10, // Smaller body font size
                },
                padding: 6, // Smaller padding inside tooltips
                cornerRadius: 3, // Reduce tooltip corner radius
                
            }
        }
    }
});


