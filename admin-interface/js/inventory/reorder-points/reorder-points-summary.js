// Total items and configuration
const reorderItemsTotal = 376;

const reorderStockCounts = [];
const reorderThresholdPoints = [];
const itemIDs = [];

// Generate random stock levels, reorder points, and unique item IDs
for (let i = 0; i < reorderItemsTotal; i++) {
    const reorderThreshold = Math.floor(Math.random() * 200) + 50; // Reorder points from 50 to 250
    const stockCount = Math.floor(reorderThreshold * (0.2 + Math.random() * 0.6)); // Stock 20-80% of reorder point
    const uniqueID = `Item-${i + 1}`;

    reorderThresholdPoints.push(reorderThreshold);
    reorderStockCounts.push(stockCount);
    itemIDs.push(uniqueID);
}

// Calculate near and below reorder point items
const belowReorderPoints = reorderThresholdPoints.map((point, index) =>
    Math.floor(point * (0.7 + Math.random() * 0.3)) // 10-30% below reorder point
);
const nearReorderPoints = reorderThresholdPoints.map((point, index) =>
    Math.floor(point * (1.1 + Math.random() * 0.3)) // 10-30% above reorder point
);

// Define datasets for reorder points, stock counts, near and below reorder points
const reorderThresholdDataset = {
    label: 'Reorder Point',
    data: reorderThresholdPoints,
    type: 'line',
    borderColor: 'rgba(255, 0, 0, 0.6)',
    borderWidth: 2,
    fill: false,
    tension: 0.4
};

const reorderStockDataset = {
    label: 'Stock Count',
    data: reorderStockCounts,
    type: 'bar',
    backgroundColor: reorderStockCounts.map((stock, index) =>
        stock < reorderThresholdPoints[index] ? '#FF4D4D' : '#007BFF'
    ),
    borderColor: '#333',
    borderWidth: 1
};

const belowReorderDataset = {
    label: 'Below Reorder Point',
    data: belowReorderPoints,
    type: 'line',
    borderColor: 'rgba(255, 99, 132, 0.6)',
    borderWidth: 1.5,
    fill: false,
    tension: 0.4
};

const nearReorderDataset = {
    label: 'Near Reorder Point',
    data: nearReorderPoints,
    type: 'line',
    borderColor: 'rgba(54, 162, 235, 0.6)',
    borderWidth: 1.5,
    fill: false,
    tension: 0.4
};

// Create the chart for reorder points and stock counts
const reorderChartCtx = document.getElementById('reorderPointsSummaryChart').getContext('2d');
const reorderSummaryChart = new Chart(reorderChartCtx, {
    data: {
        labels: itemIDs,
        datasets: [reorderStockDataset, reorderThresholdDataset, belowReorderDataset, nearReorderDataset]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Stock Count / Reorder Point'
                },
                suggestedMax: 300
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
                        const stockCount = reorderStockCounts[itemIndex];
                        const reorderPoint = reorderThresholdPoints[itemIndex];
                        const belowReorderText = stockCount < reorderPoint ? ' -- Below Reorder Point' : '';

                        return [
                            `Product ID: ${itemIDs[itemIndex]}`,
                            `Stock count: ${stockCount}`,
                            `Reorder point: ${reorderPoint}${belowReorderText}`
                        ];
                    }
                }
            },
            legend: {
                display: true
            }
        }
    }
});

// Initial chart load
reorderSummaryChart.update();
