// Total Units Sold Line Chart 
var unitsSoldCtx = document.getElementById('unitsSoldChart').getContext('2d');
var unitsSoldChart = new Chart(unitsSoldCtx, {
    type: 'line',
    data: {
        labels: Array(12).fill(''),  // Placeholder for 12 data points
        datasets: [{
            label: '',  // No label
            data: [50000, 51000, 50500, 52000, 53000, 52500, 54000, 55000, 54500, 56000, 57000, 56500],  // Sample data
            borderColor: 'blue',  // Line color
            backgroundColor: 'rgba(0, 0, 255, 0.1)',  // Shaded area color
            fill: true,  // Fill the area under the line
            pointRadius: 0,  // No dots on the line
            borderWidth: 2  // Thickness of the line
        }]
    },
    options: {
        responsive: true,  // Responsive to the container size
        maintainAspectRatio: false,  // Allows stretching with the container
        legend: { display: false },  // No legend
        tooltips: { enabled: false },  // Disable tooltips
        scales: {
            x: { display: false },  // Completely remove x-axis
            y: { display: false }   // Completely remove y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: { enabled: false },  // Disable tooltips
        },
        elements: {
            line: {
                tension: 0.4  // Smooth out the line curves
            }
        }
    }
});

// Total Sales Line Chart 
var salesCtx = document.getElementById('salesChart').getContext('2d');
var salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: Array(12).fill(''),  // Placeholder for 12 data points
        datasets: [{
            label: '',  // No label
            data: [336413, 345000, 353000, 330000, 336000, 380000, 378000, 400000, 405000, 407000, 410000, 440000],  // Sample data
            borderColor: 'green',  // Line color
            backgroundColor: 'rgba(0, 255, 0, 0.1)',  // Shaded area color
            fill: true,  // Fill the area under the line
            pointRadius: 0,  // No dots on the line
            borderWidth: 2  // Thickness of the line
        }]
    },
    options: {
        responsive: true,  // Responsive to the container size
        maintainAspectRatio: false,  // Allows stretching with the container
        legend: { display: false },  // No legend
        tooltips: { enabled: false },  // Disable tooltips
        scales: {
            x: { display: false },  // Completely remove x-axis
            y: { display: false }   // Completely remove y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: { enabled: false },  // Disable tooltips
        },
        elements: {
            line: {
                tension: 0.4  // Smooth out the line curves
            }
        }
    }
});


// Stock Value Line Chart
var stockValueCtx = document.getElementById('stockValueChart').getContext('2d');
var stockValueChart = new Chart(stockValueCtx, {
    type: 'line',
    data: {
        labels: Array(12).fill(''),  // Placeholder for 12 data points (months or periods)
        datasets: [
            {
                label: '',  // No label for Total Stock Value
                data: [48000, 47000, 46000, 45000, 44000, 43000, 41500, 40000, 37500, 35000, 32000, 30000],  // Total stock value decreasing over time as items are sold
                borderColor: 'rgba(153, 102, 255, 1)',  // Purple line for Total Stock Value
                backgroundColor: 'rgba(153, 102, 255, 0.1)',  // Light purple fill
                fill: true,
                pointRadius: 0,  // No dots on the line
                borderWidth: 2
            },
            {
                label: '',  // No label for Sold Stock Value
                data: [2000, 4000, 6000, 8000, 10000, 13000, 16000, 20000, 25000, 30000, 35000, 40000],  // Sold stock value (steady increase over time)
                borderColor: 'rgba(75, 192, 75, 1)',  // Green line for Sold Stock Value
                backgroundColor: 'rgba(75, 192, 75, 0.1)',  // Light green fill
                fill: true,
                pointRadius: 0,
                borderWidth: 2
            },
            {
                label: '',  // No label for Idle Stock Value
                data: [46000, 43000, 43000, 42000, 41000, 37500, 34000, 31500, 27000, 23000, 19000, 15000],  // Idle stock value (gradual decrease as stock is sold)
                borderColor: 'rgba(255, 99, 132, 1)',  // Red line for Idle Stock Value
                backgroundColor: 'rgba(255, 99, 132, 0.1)',  // Light red fill
                fill: true,
                pointRadius: 0,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,  // Responsive to the container size
        maintainAspectRatio: false,  // Allows stretching with the container
        legend: { display: false },  // No legend
        tooltips: { enabled: false },  // Disable tooltips
        scales: {
            x: { display: false },  // Completely remove x-axis
            y: { display: false }   // Completely remove y-axis
        },
        plugins: {
            legend: { display: false },  // Disable legend
            tooltip: { enabled: false },  // Disable tooltips
        },
        elements: {
            line: {
                tension: 0.4  // Smooth out the line curves
            }
        }
    }
});



