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

// Total Inventory Value Chart 
var totalInventoryValueCtx = document.getElementById('totalInventoryValueChart').getContext('2d');

// Generate random inventory value data that sums to 500000 for 10 months (Jan to Oct)
var targetInventoryValueTotal = 500000;
var adjustedInventoryValue = generateRandomData(targetInventoryValueTotal, 10, 20000);  // Ensuring no value is below 20,000

var totalInventoryValueChart = new Chart(totalInventoryValueCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Jan to Oct
        datasets: [{
            label: 'Total Inventory Value',
            data: adjustedInventoryValue,  // Randomly generated data
            backgroundColor: 'rgba(255, 159, 64, 0.5)',  // Orange area fill
            borderColor: 'rgb(255, 99, 71)',  // Red border
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
        }
    }
});

// Total Inventory Turnover Rate Chart 
var totalInventoryTurnoverRateCtx = document.getElementById('totalInventoryTurnoverRateChart').getContext('2d');

// Generate random inventory turnover rate data (unitless) that sums to 120 for 10 months
var targetTurnoverRateTotal = 120;
var adjustedTurnoverRate = generateRandomData(targetTurnoverRateTotal, 10, 5);  // Ensuring no value is below 5

var totalInventoryTurnoverRateChart = new Chart(totalInventoryTurnoverRateCtx, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],  // Jan to Oct
        datasets: [{
            label: 'Inventory Turnover Rate',
            data: adjustedTurnoverRate,  // Randomly generated data
            backgroundColor: 'rgba(54, 162, 235, 0.5)',  // Blue area fill
            borderColor: 'rgba(54, 162, 235, 1)',  // Blue border
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
        }
    }
});


document.addEventListener("DOMContentLoaded", function () {
    // Data for the products
    const products = [
      'Aluminum Sheets', 'Aluminum Pipes', 'Aluminum Foil Rolls',
      'Aluminum Coils', 'Aluminum Bars', 'Aluminum Extrusions',
      'Aluminum Rods', 'Aluminum Wire', 'Aluminum Angles',
      'Aluminum Tubes', 'Aluminum Panels', 'Aluminum Frames'
    ];
  
    const suppliers = ['Supplier A', 'Supplier B', 'Supplier C', 'Supplier D'];
  
    // Function to generate random integer between min and max
    function getRandomInt(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }
  
    // Function to generate random date within the past year
    function getRandomDate() {
      const start = new Date();
      start.setFullYear(start.getFullYear() - 1); // 1 year ago
      const end = new Date();
      const randomTime = getRandomInt(start.getTime(), end.getTime());
      return new Date(randomTime).toISOString().split('T')[0]; // Return only the date part (YYYY-MM-DD)
    }
  
    // Function to shuffle an array
    function shuffleArray(array) {
      return array.sort(() => 0.5 - Math.random());
    }
  
    // Function to generate "Recently Restocked Items"
    function generateRestockedItems() {
      const restockList = document.querySelector('.restocked-items-list');
      restockList.innerHTML = ''; // Clear existing items
  
      // Header row
      const restockHeaderHTML = `
        <li class="restocked-header">
          <span class="header-id">ID</span>
          <span class="header-product-name">Name</span>
          <span class="header-supplier">Supplier</span>
          <span class="header-restocked-date">Date</span>
          <span class="header-restock-count">Restock Count</span>
        </li>`;
      restockList.insertAdjacentHTML('beforeend', restockHeaderHTML);
  
      // Shuffle products and generate 12 restocked items
      const shuffledProducts = shuffleArray(products).slice(0, 12);
      shuffledProducts.forEach((product, index) => {
        const restockCount = getRandomInt(10, 100); // Random restock count between 10 and 100
        const supplier = suppliers[getRandomInt(0, suppliers.length - 1)];
        const restockDate = getRandomDate();
  
        const restockHTML = `
          <li class="restocked-item" data-id="${index + 1}" data-product="${product}" data-supplier="${supplier}" data-date="${restockDate}" data-count="${restockCount}">
            <span class="id">${index + 1}</span>
            <span class="product-name">${product}</span>
            <span class="supplier">${supplier}</span>
            <span class="restocked-date">${restockDate}</span>
            <span class="restock-count">${restockCount}</span>
          </li>`;
        restockList.insertAdjacentHTML('beforeend', restockHTML);
      });
    }
  
    // Function to generate "Low Stock Alerts"
    function generateLowStockAlerts() {
      const lowStockList = document.querySelector('.low-stock-list');
      lowStockList.innerHTML = ''; // Clear existing items
  
      // Header row
      const lowStockHeaderHTML = `
        <li class="stock-header">
          <span class="header-id">ID</span>
          <span class="header-product-name">Name</span>
          <span class="header-stock-level">Stock Count</span>
          <span class="header-last-restock">Last Restock Date</span>
        </li>`;
      lowStockList.insertAdjacentHTML('beforeend', lowStockHeaderHTML);
  
      // Shuffle products and generate 21 low stock items
      const shuffledProducts = shuffleArray(products).slice(0, 21);
      shuffledProducts.forEach((product, index) => {
        const stockCount = getRandomInt(1, 10); // Random stock count between 1 and 10
        const lastRestockDate = getRandomDate();
  
        const lowStockHTML = `
          <li class="stock-item" data-id="${index + 1}" data-product="${product}" data-stock="${stockCount}" data-last-restock="${lastRestockDate}">
            <span class="id">${index + 1}</span>
            <span class="product-name">${product}</span>
            <span class="stock-level">${stockCount}</span>
            <span class="last-restock">${lastRestockDate}</span>
          </li>`;
        lowStockList.insertAdjacentHTML('beforeend', lowStockHTML);
      });
    }
  
    // Generate the lists on page load
    generateRestockedItems();
    generateLowStockAlerts();
  
    // Optional: Regenerate lists every minute (for demonstration purposes)
    setInterval(() => {
      generateRestockedItems();
      generateLowStockAlerts();
    }, 60000); // Refresh every 60 seconds
  });