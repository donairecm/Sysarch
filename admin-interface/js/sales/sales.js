const recentOrdersList = document.querySelector('.recent-orders-list');

const statuses = ['Complete', 'On-Process', 'Pending', 'Refund'];
const orders = Array.from({ length: Math.floor(Math.random() * (21 - 13 + 1)) + 13 }, (_, i) => ({
    productId: `AL-${String(1000 + i).slice(1)}`,
    quantity: Math.floor(Math.random() * 10) + 1,
    sale: (Math.random() * 2000 + 50).toFixed(2),
    status: statuses[Math.floor(Math.random() * statuses.length)]
}));

orders.forEach(order => {
    const orderItem = document.createElement('li');
    orderItem.className = 'recent-orders-item';
    orderItem.innerHTML = `
        <span class="product-id">${order.productId}</span>
        <span class="quantity">${order.quantity}</span>
        <span class="sale">₱${order.sale}</span>
        <span class="status">${order.status}</span>
    `;
    recentOrdersList.appendChild(orderItem);
});

// Prepare data for the chart
const statusCounts = statuses.reduce((counts, status) => {
    counts[status] = orders.filter(order => order.status === status).length;
    return counts;
}, {});

// Customize the container for better alignment
const chartContainer = document.getElementById('salesdashboardOrderStatus');
chartContainer.style.backgroundColor = '#fff';
chartContainer.style.borderRadius = '8px';
chartContainer.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
chartContainer.style.padding = '20px';
chartContainer.style.width = '100%';
chartContainer.style.height = '400px'; // Adjust height

// Render the chart
const salesdashboardOrderStatusCtx = chartContainer.getContext('2d');
new Chart(salesdashboardOrderStatusCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(statusCounts),
        datasets: [{
            data: Object.values(statusCounts),
            backgroundColor: ['#1B5E20', '#2E4A62', '#FF8F00', '#8D2828'],
            borderColor: '#ffffff',  // Border color of pie slices
            borderWidth: 0,  // Clean borders for a polished look
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',  // Move legend to bottom for better fit
                labels: {
                    font: {
                        size: 14,
                        family: "'Afacad Flux', sans-serif",  // Match dashboard font
                    },
                    color: '#555',  // Color of the legend text
                    padding: 15,
                    usePointStyle: true,  // Make legend dots circular
                },
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',  // Tooltip background color
                bodyColor: '#fff',  // Tooltip text color
                titleColor: '#fff',  // Tooltip title text color
                borderWidth: 0,
                padding: 10,
                bodyFont: {
                    size: 12,
                    family: "'Afacad Flux', sans-serif"  // Tooltip font
                }
            }
        }
    }
});
