document.addEventListener('DOMContentLoaded', function () {
    const inventoryLevelsCtx = document.getElementById('inventoryLevelsChartModal').getContext('2d');

    // Generate random stock data for 365 items (1 year)
    const stockDataYear = [];
    const labelsYear = [];
    for (let i = 1; i <= 365; i++) {
        labelsYear.push(i); // Numbers for item labels
        stockDataYear.push(Math.floor(Math.random() * 1000)); // Random stock count between 0 and 1000
    }

    const lowStockLevel = 200;
    const highStockLevel = 800;

    // Initial Chart Configuration
    const inventoryChart = new Chart(inventoryLevelsCtx, {
        type: 'line',
        data: {
            labels: labelsYear,
            datasets: [
                {
                    label: 'Stock Count',
                    data: stockDataYear,
                    borderColor: '#4a90e2',
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 1,
                },
                {
                    label: 'Low Stock Level',
                    data: Array(365).fill(lowStockLevel),
                    borderColor: '#f44336',
                    borderDash: [5, 5],
                    borderWidth: 1,
                    fill: false,
                },
                {
                    label: 'High Stock Level',
                    data: Array(365).fill(highStockLevel),
                    borderColor: '#4caf50',
                    borderDash: [5, 5],
                    borderWidth: 1,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: {
                    tension: 0.3,
                },
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Item Number',
                        color: '#666',
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif",
                        },
                    },
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif",
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Stock Count',
                        color: '#666',
                        font: {
                            size: 14,
                            family: "'Afacad Flux', sans-serif",
                        },
                    },
                    grid: {
                        color: '#e0e0e0',
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10,
                            family: "'Afacad Flux', sans-serif",
                        },
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 4,
                    xPadding: 10,
                    yPadding: 10,
                    titleFont: {
                        family: "'Afacad Flux', sans-serif",
                    },
                    bodyFont: {
                        family: "'Afacad Flux', sans-serif",
                    },
                },
            },
        },
    });

    // Filter Button Event Listeners
    document.getElementById('inventory-levels-filter-week').addEventListener('click', function() {
        updateChart(7);
    });

    document.getElementById('inventory-levels-filter-month').addEventListener('click', function() {
        updateChart(30);
    });

    document.getElementById('inventory-levels-filter-year').addEventListener('click', function() {
        updateChart(365);
    });

    // Function to update chart data based on selected filter
    function updateChart(days) {
        const filteredLabels = labelsYear.slice(0, days);
        const filteredData = stockDataYear.slice(0, days);

        inventoryChart.data.labels = filteredLabels;
        inventoryChart.data.datasets[0].data = filteredData; // Update Stock Count
        inventoryChart.data.datasets[1].data = Array(days).fill(lowStockLevel); // Update Low Stock Level
        inventoryChart.data.datasets[2].data = Array(days).fill(highStockLevel); // Update High Stock Level

        inventoryChart.update(); // Redraw chart
    }
});