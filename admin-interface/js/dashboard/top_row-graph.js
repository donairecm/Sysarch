// Function to generate random data that sums to a specific total with a minimum value per month
function generateRandomData(total, months, minValue) {
    let data = Array(months).fill(minValue);
    let remainingTotal = total - months * minValue;  // Deduct minimum values from total

    let randomValues = Array(months).fill(0);
    let sum = 0;

    // Randomly distribute remaining values while ensuring they sum up to the remainingTotal
    for (let i = 0; i < months - 1; i++) {
        randomValues[i] = Math.random();  // Assign random fractions to each month
        sum += randomValues[i];
    }

    // Normalize the remaining random values
    let scalingFactor = remainingTotal / sum;
    randomValues = randomValues.map(value => value * scalingFactor);

    // Add the random values to the minimum values and ensure the total is correct
    for (let i = 0; i < months - 1; i++) {
        data[i] += randomValues[i];
    }

    // Adjust the last element to match the exact total
    let currentSum = data.reduce((a, b) => a + b, 0);
    data[months - 1] += total - currentSum;  // Final adjustment for rounding differences

    // Round the data values to integers
    return data.map(value => Math.round(value));
}

// Total Units Sold Area Chart 
var unitsSoldCtx = document.getElementById('unitsSoldChart').getContext('2d');

// Generate random units sold data that sums to 157464 for 10 months (Jan to Oct)
var targetUnitsSoldTotal = 157464;
var adjustedUnitsSold = generateRandomData(targetUnitsSoldTotal, 10, 6000);  // Ensuring no value is below 6,000

var unitsSoldChart = new Chart(unitsSoldCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],
  // Jan to Oct
        datasets: [{
            label: 'Units Sold',
            data: adjustedUnitsSold,  // Randomly generated data
            backgroundColor: 'rgba(35, 117, 194, 0.5)',  // Blue area fill
            borderColor: 'rgb(0, 64, 124)',  // Dark blue border
            borderWidth: 1,  // Thin line
            pointRadius: 0,  // Invisible points
            hoverRadius: 6,  // Increase the hover radius
            hitRadius: 8,  // Make it easier to hover
            tension: 0.4,  // Smooth curves
            fill: true  // Enable area fill
        }]
    },
    options: {
        responsive: true,  
        maintainAspectRatio: false,  
        scales: {
            x: { display: false },  // Hide x-axis
            y: { display: false }   // Hide y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: {
                enabled: true,
                backgroundColor: '#333', 
                titleColor: '#fff',
                bodyColor: '#fff',
                callbacks: {
                    title: (tooltipItems) => {
                        return tooltipItems[0].label;  // Show the month
                    },
                    label: (tooltipItem) => {
                        return tooltipItem.raw.toLocaleString();  // Show the value with commas
                    }
                }
            }
        },
    }
});

// Total Sales Area Chart 
var salesCtx = document.getElementById('salesChart').getContext('2d');

// Generate random sales data that sums to 336413 for 10 months (Jan to Oct)
var targetSalesTotal = 336413;
var adjustedSales = generateRandomData(targetSalesTotal, 10, 8000);  // Ensuring no value is below 8,000

var salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Jan to Oct
        datasets: [{
            label: 'Total Sales',
            data: adjustedSales,  // Randomly generated data
            backgroundColor: 'rgba(82, 173, 29, 0.5)',  // Light green area fill
            borderColor: 'green',  
            borderWidth: 1,  // Thin line
            pointRadius: 0,  // Invisible points
            hoverRadius: 6,  // Increase the hover radius
            hitRadius: 8,  // Make it easier to hover
            tension: 0.4,  // Smooth curves
            fill: true  // Enable area fill
        }]
    },
    options: {
        responsive: true,  
        maintainAspectRatio: false,  
        scales: {
            x: { display: false },  // Hide x-axis
            y: { display: false }   // Hide y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: {
                enabled: true,
                backgroundColor: '#333', 
                titleColor: '#fff',
                bodyColor: '#fff',
                callbacks: {
                    title: (tooltipItems) => {
                        return tooltipItems[0].label;  // Show the month
                    },
                    label: (tooltipItem) => {
                        return `₱${tooltipItem.raw.toLocaleString()}`;  // Show the value with Peso sign and commas
                    }
                }
            }
        },
    }
});

// Function to generate random stock values with given min and max constraints
function generateRandomStockValues(minValue, maxValue, totalLimit, months) {
    let values = [];
    let sum = 0;

    for (let i = 0; i < months - 1; i++) {
        let value = Math.random() * (maxValue - minValue) + minValue;  // Random value between minValue and maxValue
        sum += value;
        values.push(Math.round(value));
    }

    // Adjust the last value to ensure the total doesn't exceed the limit
    let remainingValue = totalLimit - sum;
    values.push(Math.round(Math.max(minValue, Math.min(remainingValue, maxValue))));  // Ensure it's within min-max range

    return values;
}

// Stock Value Area Chart
var stockValueCtx = document.getElementById('stockValueChart').getContext('2d');

// Generate random stock data with min between 32412-42312 and max at 74261 for 10 months
var maxStockValue = 74261;
var minStockValue = 32412;
var targetStockTotal = 74261;  // Total should not exceed this
var stockValues = generateRandomStockValues(minStockValue, maxStockValue, targetStockTotal, 10);

var stockValueChart = new Chart(stockValueCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Labels for Jan to Oct
        datasets: [{
            label: 'Stock Value',
            data: stockValues,  // Randomized stock value data
            backgroundColor: 'rgba(153, 102, 255, 0.5)',  // Purple area fill
            borderColor: 'rgba(153, 102, 255, 1)',  
            borderWidth: 1,
            pointRadius: 0,  // Invisible points
            hoverRadius: 6,  // Increase the hover radius
            hitRadius: 8,  // Make it easier to hover
            tension: 0.4,  // Smooth curves
            fill: true  // Enable area fill
        }]
    },
    options: {
        responsive: true,  // Responsive to the container size
        maintainAspectRatio: false,  // Allows stretching with the container
        scales: {
            x: { display: false },  // Hide x-axis
            y: { display: false }   // Hide y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: {
                enabled: true,
                backgroundColor: '#333', 
                titleColor: '#fff',
                bodyColor: '#fff',
                callbacks: {
                    title: (tooltipItems) => {
                        return tooltipItems[0].label;  // Show the month
                    },
                    label: (tooltipItem) => {
                        return `₱${tooltipItem.raw.toLocaleString()}`;  // Show the value with Peso sign and commas
                    }
                }
            }
        }
    }
});

// Function to generate random inventory turnover values with given constraints
function generateRandomInventoryTurnover(minValue, maxValue, months) {
    return Array.from({ length: months }, () =>
        Math.random() * (maxValue - minValue) + minValue
    ).map(value => Math.round(value * 100) / 100); // Round to two decimal places
}

// Inventory Turnover Chart
var inventoryTurnoverCtx = document.getElementById('inventoryTurnoverChart').getContext('2d');

// Generate random inventory turnover data with min at 0.5 and max at 5 for 10 months
var minTurnover = 0.5;
var maxTurnover = 5.0;
var inventoryTurnoverValues = generateRandomInventoryTurnover(minTurnover, maxTurnover, 10);

var inventoryTurnoverChart = new Chart(inventoryTurnoverCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'], // Jan to Oct
        datasets: [{
            label: 'Inventory Turnover',
            data: inventoryTurnoverValues, // Randomized inventory turnover data
            backgroundColor: 'rgba(255, 159, 64, 0.5)', // Orange area fill
            borderColor: 'rgba(255, 159, 64, 1)', // Orange border
            borderWidth: 1,
            pointRadius: 0, // Invisible points
            hoverRadius: 6, // Increase the hover radius
            hitRadius: 8, // Make it easier to hover
            tension: 0.4, // Smooth curves
            fill: true // Enable area fill
        }]
    },
    options: {
        responsive: true, // Responsive to the container size
        maintainAspectRatio: false, // Allows stretching with the container
        scales: {
            x: { display: false }, // Hide x-axis
            y: { display: false } // Hide y-axis
        },
        plugins: {
            legend: { display: false }, // Disable legend
            tooltip: {
                enabled: true,
                backgroundColor: '#333',
                titleColor: '#fff',
                bodyColor: '#fff',
                callbacks: {
                    title: (tooltipItems) => {
                        return tooltipItems[0].label; // Show the month
                    },
                    label: (tooltipItem) => {
                        return `Turnover: ${tooltipItem.raw.toFixed(2)}`; // Show turnover with 2 decimal places
                    }
                }
            }
        }
    }
});
