// Stock Levels
const totalInventoryItems = 376;
const inventoryThresholds = {
    high: 300,
    low: 100,
    critical: 50,
    out: 0
};

// Generate random stock levels and item IDs with at least 1-5 out-of-stock items
function generateInventoryData() {
    const inventoryData = [];

    // Generate 1-5 guaranteed out-of-stock items
    const outOfStockCount = Math.floor(Math.random() * 5) + 1;
    for (let i = 0; i < outOfStockCount; i++) {
        const itemID = Math.floor(Math.random() * 10000);
        inventoryData.push({ id: itemID, stock: 0 }); // Stock set to 0 (out of stock)
    }

    // Generate the remaining stock items
    for (let i = outOfStockCount; i < totalInventoryItems; i++) {
        const itemID = Math.floor(Math.random() * 10000);
        const stockLevel = Math.floor(Math.random() * 501); // Random stock level between 0 and 500
        inventoryData.push({ id: itemID, stock: stockLevel });
    }

    return inventoryData;
}

// Inventory data for all items
const inventoryData = generateInventoryData();

// Prepare data for the chart
const inventoryDataset = {
    label: 'Inventory Level per Item',
    data: inventoryData.map(data => data.stock),
    fill: false,
    borderColor: 'rgba(0, 123, 255, 0.6)',
    borderWidth: 1,
    pointRadius: 3,
    hoverRadius: 6,
    pointBorderRadius: 4,
    pointBackgroundColor: inventoryData.map(data => {
        if (data.stock <= inventoryThresholds.out) return '#FF4D4D';
        if (data.stock <= inventoryThresholds.critical) return '#FF9900';
        if (data.stock <= inventoryThresholds.low) return '#FFCC00';
        if (data.stock >= inventoryThresholds.high) return '#28A745';
        return '#007BFF';
    })
};

// Create chart with threshold lines
const inventoryCtx = document.getElementById('inventoryLevelsChartNew').getContext('2d');
const inventoryChart = new Chart(inventoryCtx, {
    type: 'line',
    data: {
        labels: Array(totalInventoryItems).fill(''),
        datasets: [inventoryDataset]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    display: true,
                },
                suggestedMax: 500,
                grid: {
                    color: function(context) {
                        if (context.tick.value === inventoryThresholds.out) return '#FF4D4D';
                        if (context.tick.value === inventoryThresholds.critical) return '#FF9900';
                        if (context.tick.value === inventoryThresholds.low) return '#FFCC00';
                        if (context.tick.value === inventoryThresholds.high) return '#28A745';
                        return 'rgba(0, 0, 0, 0.1)';
                    }
                }
            },
            x: {
                display: false
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const itemIndex = tooltipItem.dataIndex;
                        const itemID = inventoryData[itemIndex].id;
                        const stockLevel = tooltipItem.raw;
                        let stockStatus = '';

                        if (stockLevel <= inventoryThresholds.out) stockStatus = '---- Out of Stock ----';
                        else if (stockLevel <= inventoryThresholds.critical) stockStatus = '--- Critical Level ---';
                        else if (stockLevel <= inventoryThresholds.low) stockStatus = '----- Low Level -----';
                        else if (stockLevel >= inventoryThresholds.high) stockStatus = '---- High Level ----';
                        else stockStatus = '--- Normal Level ---';

                        return [
                            stockStatus,
                            `Product ID: ${itemID}`,
                            `Stock count: ${stockLevel}`
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

// Track active filter
let activeInventoryFilter = null;

// Filter data based on active filter
function filterInventoryData() {
    if (!activeInventoryFilter) return inventoryData;

    return inventoryData.filter(data => {
        if (activeInventoryFilter === 'high' && data.stock >= inventoryThresholds.high) return true;
        if (activeInventoryFilter === 'normal' && data.stock > inventoryThresholds.low && data.stock < inventoryThresholds.high) return true;
        if (activeInventoryFilter === 'low' && data.stock <= inventoryThresholds.low && data.stock > inventoryThresholds.critical) return true;
        if (activeInventoryFilter === 'critical' && data.stock <= inventoryThresholds.critical && data.stock > inventoryThresholds.out) return true;
        if (activeInventoryFilter === 'out' && data.stock <= inventoryThresholds.out) return true;
        return false;
    });
}

// Update chart with filtered data
function updateInventoryChart() {
    const filteredData = filterInventoryData();
    inventoryChart.data.labels = filteredData.map(() => '');
    inventoryChart.data.datasets[0].data = filteredData.map(data => data.stock);
    inventoryChart.data.datasets[0].pointBackgroundColor = filteredData.map(data => {
        if (data.stock <= inventoryThresholds.out) return '#FF4D4D';
        if (data.stock <= inventoryThresholds.critical) return '#FF9900';
        if (data.stock <= inventoryThresholds.low) return '#FFCC00';
        if (data.stock >= inventoryThresholds.high) return '#28A745';
        return '#007BFF';
    });
    inventoryChart.update();
}

// Add event listeners to filter buttons
document.querySelectorAll('.inventory-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const threshold = this.getAttribute('data-threshold');
        if (activeInventoryFilter === threshold) {
            activeInventoryFilter = null;
        } else {
            activeInventoryFilter = threshold;
        }
        updateInventoryChart();
    });
});

// Initial chart load
updateInventoryChart();
