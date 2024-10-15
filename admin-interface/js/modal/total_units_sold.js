// Generate Random Data for 2021-2024
const totalUnitsSoldData = {};

for (let year = 2021; year <= 2024; year++) {
    totalUnitsSoldData[year] = {};
    for (let month = 1; month <= 12; month++) {
        totalUnitsSoldData[year][month] = [];
        const daysInMonth = new Date(year, month, 0).getDate(); // Get correct number of days in the month
        for (let day = 1; day <= daysInMonth; day++) {
            totalUnitsSoldData[year][month].push({
                label: `Day ${day}`,
                data: Math.floor(Math.random() * 10000) + 5000 // Random data between 5000-15000
            });
        }
    }
}

// Function to update the chart based on selection
function updateChartBasedOnSelection(year, month = null, week = null) {
    let labels = [];
    let data = [];

    if (!month && !week) {
        // Year view: display data for January to December for past years or January to October for the current year
        const currentYear = new Date().getFullYear();
        let monthsToDisplay = year == currentYear ? 10 : 12; // Show up to October if it's the current year, otherwise full year

        labels = Array.from({ length: monthsToDisplay }, (_, monthIndex) => 
            new Date(2021, monthIndex).toLocaleString('default', { month: 'long' })
        ); // Generate month names (up to October or full year)

        data = labels.map((_, monthIndex) => {
            // Summing up the total units sold for each month
            const monthNumber = monthIndex + 1; // Month number from 1-12
            return totalUnitsSoldData[year][monthNumber].reduce((sum, dayData) => sum + dayData.data, 0);
        });
    } else if (month && !week) {
        // Month view: display data for the selected month (days as labels)
        const daysInMonth = totalUnitsSoldData[year][month].length;
        labels = Array.from({ length: daysInMonth }, (_, i) => `Day ${i + 1}`);
        data = totalUnitsSoldData[year][month].map(dayData => dayData.data);
    } else if (month && week) {
        // Week view: display data for the selected week (days of the week as labels)
        labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        const weekData = totalUnitsSoldData[year][month].slice((week - 1) * 7, week * 7);
        data = weekData.map(dayData => dayData.data);
    }

    updateUnitsSoldChart(totalUnitsSoldChartModal, labels, data);
}

// Function to update dropdowns and set default data
function updateDropdowns() {
    const yearSelect = document.getElementById('filter-year');
    const monthSelect = document.getElementById('filter-month');
    const weekSelect = document.getElementById('filter-week');

    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1; // Get current month (October = 10)
    const currentDay = new Date().getDate(); // Get today's date
    const currentWeek = Math.ceil(currentDay / 7); // Calculate the current week number

    // Set default data (from January to October of the current year)
    updateChartBasedOnSelection(currentYear);  // Display data for January to October this year

    // Enable month dropdown after selecting a year
    yearSelect.addEventListener('change', function () {
        const selectedYear = parseInt(this.value);
        if (selectedYear) {
            monthSelect.disabled = false;
            monthSelect.innerHTML = '<option value="">Select Month</option>';

            const monthsToDisplay = selectedYear === currentYear ? currentMonth : 12; // Limit months to up to October if it's 2024
            for (let i = 1; i <= monthsToDisplay; i++) {
                monthSelect.innerHTML += `<option value="${i}">${new Date(2021, i - 1).toLocaleString('default', { month: 'long' })}</option>`;
            }
            // If only year is selected, display data for the whole year (or till October if current year)
            updateChartBasedOnSelection(selectedYear);
        } else {
            monthSelect.disabled = true;
            weekSelect.disabled = true;
        }
    });

    // Enable week dropdown after selecting a month
    monthSelect.addEventListener('change', function () {
        const selectedYear = parseInt(yearSelect.value);
        const selectedMonth = parseInt(this.value);
        if (selectedYear && selectedMonth) {
            weekSelect.disabled = false;
            const daysInMonth = totalUnitsSoldData[selectedYear][selectedMonth].length;
            let weeksInMonth = Math.ceil(daysInMonth / 7);

            // If it's the current year and the current month, limit the weeks to the current week
            if (selectedYear === currentYear && selectedMonth === currentMonth) {
                weeksInMonth = currentWeek;
            }

            weekSelect.innerHTML = '<option value="">Select Week</option>';
            for (let i = 1; i <= weeksInMonth; i++) {
                weekSelect.innerHTML += `<option value="${i}">Week ${i}</option>`;
            }
            // If month is selected without week, display data for the selected month
            updateChartBasedOnSelection(selectedYear, selectedMonth);
        } else {
            weekSelect.disabled = true;
        }
    });

    // Update chart after selecting a week
    weekSelect.addEventListener('change', function () {
        const selectedYear = parseInt(yearSelect.value);
        const selectedMonth = parseInt(monthSelect.value);
        const selectedWeek = parseInt(this.value);

        if (selectedYear && selectedMonth && selectedWeek) {
            // If year, month, and week are selected, display data for that week
            updateChartBasedOnSelection(selectedYear, selectedMonth, selectedWeek);
        }
    });
}

// Function to update the Total Units Sold Chart
function updateUnitsSoldChart(chart, labels, data) {
    chart.data.labels = labels;
    chart.data.datasets[0].data = data;
    chart.update();
}

// Initialize the Total Units Sold Line Chart (Modal)
const totalUnitsSoldModalCtx = document.getElementById('unitsSoldChartModal').getContext('2d');
const totalUnitsSoldChartModal = new Chart(totalUnitsSoldModalCtx, {
    type: 'line',
    data: {
        labels: [],  // Will be set dynamically
        datasets: [{
            data: [],  // Will be set dynamically
            borderColor: 'blue',
            backgroundColor: 'rgba(0, 0, 255, 0.1)',
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: 'rgba(0, 0, 255, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                enabled: true,
                callbacks: {
                    label: function (context) {
                        return `Units Sold: ${context.parsed.y.toLocaleString()}`;
                    }
                }
            },
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Time',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    }
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Units Sold',
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 14,
                        weight: 'bold'
                    }
                },
                ticks: {
                    color: 'black',
                    font: {
                        family: "'Afacad Flux', sans-serif",
                        size: 12
                    },
                    callback: function (value) {
                        return value.toLocaleString();
                    }
                },
                beginAtZero: true
            }
        },
        elements: {
            line: {
                tension: 0.4
            }
        }
    }
});

// Initialize dropdowns and set default view
updateDropdowns();
