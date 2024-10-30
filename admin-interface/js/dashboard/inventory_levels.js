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
        low: 300
    };

    const countPerLevel = {
        high: stockData.filter(item => item.stock >= stockLevelLines.high).length,
        low: stockData.filter(item => item.stock < stockLevelLines.low).length
    };

    // Update counts in the stock level boxes
    document.getElementById('highCount').textContent = countPerLevel.high;
    document.getElementById('lowCount').textContent = countPerLevel.low;

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
                    pointStyle: 'circle',
                    pointRadius: 1.6,
                    hitRadius: 10,
                    hoverRadius: 6,
                    pointBackgroundColor: '#4a90e2',
                },
                {
                    label: 'High Stock Level',
                    data: Array(stockData.length).fill(stockLevelLines.high),
                    borderColor: '#00ff6a',
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
                    display: false // Hides the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                const stockDataItem = stockData[context.dataIndex];
                                return `${stockDataItem.item}: ${stockDataItem.stock} items`;
                            } else {
                                const stockLevel = context.datasetIndex === 1 ? 'High' : 'Low';
                                const count = countPerLevel[stockLevel.toLowerCase()];
                                return `${stockLevel} Stock Level: ${count} items`;
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