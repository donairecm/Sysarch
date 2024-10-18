document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('inventoryLevelsChart').getContext('2d');

    function generateStockData(count) {
        const stockData = [];
        const generateRandomName = () => {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const randomLetters = Array(3).fill('').map(() => letters.charAt(Math.floor(Math.random() * letters.length))).join('');
            const randomNumbers = String(Math.floor(Math.random() * 900) + 100); // 3 digit random number
            return `${randomLetters}-${randomNumbers}`;
        };

        for (let i = 1; i <= count; i++) {
            stockData.push({
                item: generateRandomName(),
                stock: Math.floor(Math.random() * 1000) // Random stock count between 0 and 1000
            });
        }
        return stockData;
    }

    const stockData = generateStockData(376);

    const stockLevelLines = {
        high: 900,
        stable: 700,
        reorder: 500,
        low: 300,
        critical: 100,
        outOfStock: 0
    };

    const countPerLevel = {
        high: stockData.filter(item => item.stock >= stockLevelLines.high).length,
        stable: stockData.filter(item => item.stock >= stockLevelLines.stable && item.stock < stockLevelLines.high).length,
        reorder: stockData.filter(item => item.stock >= stockLevelLines.reorder && item.stock < stockLevelLines.stable).length,
        low: stockData.filter(item => item.stock >= stockLevelLines.low && item.stock < stockLevelLines.reorder).length,
        critical: stockData.filter(item => item.stock >= stockLevelLines.critical && item.stock < stockLevelLines.low).length,
        outOfStock: stockData.filter(item => item.stock <= stockLevelLines.outOfStock).length
    };

    // Update counts in the stock level boxes
    document.getElementById('highCount').textContent = countPerLevel.high;
    document.getElementById('stableCount').textContent = countPerLevel.stable;
    document.getElementById('reorderCount').textContent = countPerLevel.reorder;
    document.getElementById('lowCount').textContent = countPerLevel.low;
    document.getElementById('criticalCount').textContent = countPerLevel.critical;
    document.getElementById('outOfStockCount').textContent = countPerLevel.outOfStock;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: stockData.map(data => data.item),
            datasets: [
                {
                    label: 'Stock Levels',
                    borderColor: '#4a90e2',
                    data: stockData.map(data => data.stock),
                    fill: false,
                    borderWidth: 1.2,
                    pointStyle: 'circle', // Solid circle
                    pointRadius: 1.6, // Smaller circle size
                    hitRadius: 10,
                    hoverRadius: 6, // Larger hover area
                    pointBackgroundColor: '#4a90e2', // Circle fill color
                },
                {
                    label: 'High Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.high),
                    borderColor: '#00ff6a',
                    backgroundColor: 'rgba(189, 13, 0, 0.2)',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                },
                {
                    label: 'Stable Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.stable),
                    borderColor: '#22fa29',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                },
                {
                    label: 'Reorder Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.reorder),
                    borderColor: '#379600',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                },
                {
                    label: 'Low Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.low),
                    borderColor: '#ff5e00',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                },
                {
                    label: 'Critical Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.critical),
                    borderColor: '#ff1100',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                },
                {
                    label: 'Out of Stock',
                    data: Array(stockData.length).fill(stockLevelLines.outOfStock),
                    borderColor: '#bd0d00',
                    borderWidth: 2.5,
                    hoverRadius: 6,  
                    hitRadius: 8,
                    fill: false,
                    pointRadius: 0,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false,
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 1000,
                },
            },
            plugins: {
                legend: {
                    display: false // This line hides the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                const stockDataItem = stockData[context.dataIndex];
                                return `${stockDataItem.item}: ${stockDataItem.stock} items`;
                            } else {
                                const stockLevel = Object.keys(stockLevelLines)[context.datasetIndex - 1];
                                const count = countPerLevel[stockLevel];
                                return `${stockLevel.charAt(0).toUpperCase() + stockLevel.slice(1)} Level: ${count} items`;
                            }
                        },
                        title: function() {
                            return '';
                        }
                    }
                }
            }
        }
    });
});
