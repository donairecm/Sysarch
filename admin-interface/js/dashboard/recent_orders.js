// Sample data for the recent orders chart
const recentOrdersData = {
    labels: ['Pending', 'On-Process', 'Complete'],
    datasets: [{
        data: [5, 8, 12], // Orders data
        backgroundColor: [
            'rgba(255, 99, 132, 0.7)', // Red for Pending
            'rgba(54, 162, 235, 0.7)', // Blue for On-Process
            'rgba(75, 192, 192, 0.7)'  // Green for Complete
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)'
        ],
        borderWidth: 2, // Thicker border for a sharper look
        borderRadius: 10, // Add rounded corners
        barPercentage: 0.5, // Narrow bars for a sleeker appearance
        hoverBackgroundColor: [
            'rgba(255, 99, 132, 0.9)', // Darker red on hover
            'rgba(54, 162, 235, 0.9)', // Darker blue on hover
            'rgba(75, 192, 192, 0.9)'  // Darker green on hover
        ],
        hoverBorderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)'
        ]
    }]
};

// Configuration for the Chart
const config = {
    type: 'bar',
    data: recentOrdersData,
    options: {
        responsive: true,
        aspectRatio: false,
        plugins: {
            legend: {
                display: false // Remove the dataset label
            },
            title: {
                display: false // Remove the chart title
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)', // Dark tooltip background
                titleFont: { size: 14, family: "'Afacad Flux', sans-serif" },
                bodyFont: { size: 12, family: "'Afacad Flux', sans-serif" },
                cornerRadius: 8,
                padding: 10
            }
        },
        scales: {
            x: {
                grid: {
                    display: false // Remove gridlines on x-axis
                },
                ticks: {
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(200, 200, 200, 0.2)' // Light gray gridlines
                },
                ticks: {
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    }
                }
            }
        }
    }
};

// Initialize the Chart with a unique variable name
const chartContext = document.getElementById('recentOrdersChart').getContext('2d');
const recentOrdersChart = new Chart(chartContext, config);
