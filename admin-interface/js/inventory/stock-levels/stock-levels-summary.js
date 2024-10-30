//Top selling items
document.addEventListener("DOMContentLoaded", function () {
    // Data for top selling products
    const topSellingProducts = [
      'Aluminum Sheets', 'Aluminum Pipes', 'Aluminum Coils', 'Aluminum Bars',
      'Aluminum Extrusions', 'Aluminum Rods', 'Aluminum Tubes', 'Aluminum Panels',
      'Aluminum Frames', 'Aluminum Foil Rolls', 'Aluminum Angles', 'Aluminum Plates',
      'Aluminum Sheets Thick', 'Aluminum Alloy Pipes', 'Aluminum Composite Panels',
      'Aluminum Brackets', 'Aluminum Channels', 'Aluminum Profiles', 'Aluminum Fasteners', 'Aluminum Beams'
    ];
  
    // Function to generate random integer between min and max
    function getRandomInt(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }
  
    // Function to generate "Top Selling Products"
    function generateTopSellingProducts() {
      const topSellingList = document.querySelector('.top-selling-products-list');
      topSellingList.innerHTML = ''; // Clear existing items
  
      // Header row for Top Selling Products
      const topSellingHeaderHTML = `
        <li class="top-selling-header">
          <span class="header-id">ID</span>
          <span class="header-product-name">Name</span>
          <span class="header-total-sold">Total Sold</span>
        </li>`;
      topSellingList.insertAdjacentHTML('beforeend', topSellingHeaderHTML);
  
      // Generate top 20 selling products
      topSellingProducts.forEach((product, index) => {
        const totalSold = getRandomInt(50, 500); // Random total sold between 50 and 500
  
        const topSellingHTML = `
          <li class="top-selling-item" data-id="${index + 1}" data-product="${product}" data-sold="${totalSold}">
            <span class="id">${index + 1}</span>
            <span class="product-name">${product}</span>
            <span class="total-sold">${totalSold}</span>
          </li>`;
        topSellingList.insertAdjacentHTML('beforeend', topSellingHTML);
      });
    }
  
    // Generate Top Selling Products on page load
    generateTopSellingProducts();
  
    // Optional: Regenerate the top-selling products list every minute (for demonstration purposes)
    setInterval(generateTopSellingProducts, 60000); // Refresh every 60 seconds
  });

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

// Total Stock on Hand Chart 
var totalStockOnHandCtx = document.getElementById('totalStockonHandChart').getContext('2d');

// Generate random stock on hand data that sums to 11272 for 10 months (Jan to Oct)
var targetStockOnHandTotal = 11272;
var adjustedStockOnHand = generateRandomData(targetStockOnHandTotal, 10, 500);  // Ensuring no value is below 500

var totalStockOnHandChart = new Chart(totalStockOnHandCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Jan to Oct
        datasets: [{
            label: 'Total Stock on Hand',
            data: adjustedStockOnHand,  // Randomly generated data
            backgroundColor: 'rgba(34, 139, 34, 0.5)',  // Teal area fill
            borderColor: 'rgba(34, 139, 34, 1)',  // Teal border
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
                        return `${tooltipItem.raw.toLocaleString()}`;  // Just the value, no Peso sign
                    }
                }
            }
        }
    }
});


// Total Stock in Transit Chart 
var totalStockInTransitCtx = document.getElementById('totalStockinTransitChart').getContext('2d');

// Generate random stock in transit data that sums to 239 for 10 months (Jan to Oct)
var targetStockInTransitTotal = 239;
var adjustedStockInTransit = generateRandomData(targetStockInTransitTotal, 10, 10);  // Ensuring no value is below 10

var totalStockInTransitChart = new Chart(totalStockInTransitCtx, {
  type: 'line',
  data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Jan to Oct
      datasets: [{
          label: 'Total Stock in Transit',
          data: adjustedStockInTransit,  // Randomly generated data
          backgroundColor: 'rgba(128, 0, 128, 0.5)',  // Yellow area fill
          borderColor: 'rgba(128, 0, 128, 1)',  // Yellow border
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
                      return `${tooltipItem.raw.toLocaleString()}`;  // Show the value with commas
                  }
              }
          }
      }
  }
});


// Stock Levels
const totalStockItems = 376;
const stockThresholds = {
    high: 300,
    low: 100,
    critical: 50,
    out: 0
};

// Generate random stock levels and item IDs with at least 1-5 out-of-stock items
function generateStockSummaryData() {
    const stockData = [];

    // Generate 1-5 guaranteed out-of-stock items
    const outOfStockCount = Math.floor(Math.random() * 5) + 1;
    for (let i = 0; i < outOfStockCount; i++) {
        const itemID = Math.floor(Math.random() * 10000);
        stockData.push({ id: itemID, stock: 0 }); // Stock set to 0 (out of stock)
    }

    // Generate the remaining stock items
    for (let i = outOfStockCount; i < totalStockItems; i++) {
        const itemID = Math.floor(Math.random() * 10000);
        const stockLevel = Math.floor(Math.random() * 501); // Random stock level between 0 and 500
        stockData.push({ id: itemID, stock: stockLevel });
    }

    return stockData;
}

// Stock data for all items
const stockSummaryData = generateStockSummaryData();

// Prepare data for the chart
const stockSummaryDataset = {
    label: 'Stock Level per Item',
    data: stockSummaryData.map(data => data.stock),
    fill: false,
    borderColor: 'rgba(0, 123, 255, 0.6)',
    borderWidth: 1,
    pointRadius: 3,
    hoverRadius: 6,
    pointBorderRadius: 4,  // Adding rounded border radius for points
    pointBackgroundColor: stockSummaryData.map(data => {
        if (data.stock <= stockThresholds.out) return '#FF4D4D'; // Bright red for out of stock
        if (data.stock <= stockThresholds.critical) return '#FF9900'; // Orange for critical stock
        if (data.stock <= stockThresholds.low) return '#FFCC00'; // Yellow for low stock
        if (data.stock >= stockThresholds.high) return '#28A745'; // Green for stable/high stock
        return '#007BFF'; // Blue for normal stock
    })
};

// Create chart with threshold lines
const stockSummaryCtx = document.getElementById('stockLevelsSummaryChart').getContext('2d');
const stockSummaryChart = new Chart(stockSummaryCtx, {
    type: 'line',
    data: {
        labels: Array(totalStockItems).fill(''), // Hide x-axis labels
        datasets: [stockSummaryDataset]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    display: true, // Display only the numbers on the y-axis
                },
                title: {
                    display: false, // Remove the 'Stock Level' label
                },
                suggestedMax: 500,
                grid: {
                    color: function(context) {
                        if (context.tick.value === stockThresholds.out) return '#FF4D4D'; // Red
                        if (context.tick.value === stockThresholds.critical) return '#FF9900'; // Orange
                        if (context.tick.value === stockThresholds.low) return '#FFCC00'; // Yellow
                        if (context.tick.value === stockThresholds.high) return '#28A745'; // Green
                        return 'rgba(0, 0, 0, 0.1)';
                    }
                }
            },
            x: {
                display: false // Hide x-axis labels
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const itemIndex = tooltipItem.dataIndex;
                        const itemID = stockSummaryData[itemIndex].id;
                        const stockLevel = tooltipItem.raw;
                        let stockStatus = '';
                        
                        if (stockLevel <= stockThresholds.out) stockStatus = '---- Out of Stock ----';
                        else if (stockLevel <= stockThresholds.critical) stockStatus = '--- Critical Level ---';
                        else if (stockLevel <= stockThresholds.low) stockStatus = '----- Low Level -----';
                        else if (stockLevel >= stockThresholds.high) stockStatus = '---- High Level ----';
                        else stockStatus = '--- Normal Level ---';
                    
                        return [
                            stockStatus,               // Line 1
                            `Product ID: ${itemID}`,    // Line 2
                            `Stock count: ${stockLevel}` // Line 3
                        ];
                    }
                }
            },
            legend: {
                display: false
            }
        }
    }
});

// Track active filter (only one filter active at a time)
let activeFilter = null;

// Filter data based on active filter
function filterStockData() {
    if (!activeFilter) return stockSummaryData; // If no filter is active, show all data

    return stockSummaryData.filter(data => {
        if (activeFilter === 'high' && data.stock >= stockThresholds.high) return true;
        if (activeFilter === 'normal' && data.stock > stockThresholds.low && data.stock < stockThresholds.high) return true;
        if (activeFilter === 'low' && data.stock <= stockThresholds.low && data.stock > stockThresholds.critical) return true;
        if (activeFilter === 'critical' && data.stock <= stockThresholds.critical && data.stock > stockThresholds.out) return true;
        if (activeFilter === 'out' && data.stock <= stockThresholds.out) return true;
        return false;
    });
}

// Update chart with filtered data
function updateChart() {
    const filteredData = filterStockData();
    stockSummaryChart.data.labels = filteredData.map(() => ''); // Hide labels
    stockSummaryChart.data.datasets[0].data = filteredData.map(data => data.stock);
    stockSummaryChart.data.datasets[0].pointBackgroundColor = filteredData.map(data => {
        if (data.stock <= stockThresholds.out) return '#FF4D4D'; // Bright red for out of stock
        if (data.stock <= stockThresholds.critical) return '#FF9900'; // Orange for critical stock
        if (data.stock <= stockThresholds.low) return '#FFCC00'; // Yellow for low stock
        if (data.stock >= stockThresholds.high) return '#28A745'; // Green for stable/high stock
        return '#007BFF'; // Blue for normal stock
    });
    stockSummaryChart.update();
}

// Add event listeners to filter buttons
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const threshold = this.getAttribute('data-threshold');
        if (activeFilter === threshold) {
            activeFilter = null;
        } else {
            activeFilter = threshold;
        }
        updateChart();
    });
});

// Initial chart load (show all data by default)
updateChart();
