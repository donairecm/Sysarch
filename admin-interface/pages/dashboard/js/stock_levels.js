document.addEventListener("DOMContentLoaded", async () => {
    try {
        // Fetch data from the database
        const response = await fetch("db_queries/fetch_stock_data.php"); // Replace with the correct path to your PHP script
        const data = await response.json();

        if (!data || data.error) {
            console.error(data.error || "No data received");
            return;
        }

        // Assign stock levels directly from the fetched JSON response
        const stockLevels = {
            Overstocked: data.overstocked || 0,
            Normal: data.normal || 0,
            Low: data.low || 0,
            Critical: data.critical || 0,
            "Out of Stock": data.outOfStock || 0
        };

        // Prepare data for the chart
        const stockDataDashboard = [
            stockLevels.Overstocked,
            stockLevels.Normal,
            stockLevels.Low,
            stockLevels.Critical,
            stockLevels["Out of Stock"]
        ];

        // Calculate the sum of low, critical, and out-of-stock items
        const totalNeedsRestockingDashboard =
            stockLevels.Low + stockLevels.Critical + stockLevels["Out of Stock"];

        // Update the "needs-restocking" element
        document.querySelector(".needs-restocking").innerText = `${totalNeedsRestockingDashboard}`;

        // Chart.js Data and Settings
        const ctxDashboard = document
            .getElementById("dashboardStockLevelChart")
            .getContext("2d");
        const dashboardStockLevelChart = new Chart(ctxDashboard, {
            type: "bar",
            data: {
                labels: ["O", "N", "L", "C", "OS"], // Abbreviated labels
                datasets: [
                    {
                        data: stockDataDashboard,
                        backgroundColor: [
                            "rgb(22, 122, 67)", // Overstocked (Green)
                            "rgb(67, 122, 22)", // Normal (Lighter Green)
                            "rgb(218, 201, 55)", // Low (Yellow)
                            "rgb(187, 92, 28)", // Critical (Amber)
                            "rgb(163, 26, 26)" // Out of Stock (Red)
                        ],
                        borderWidth: 1,
                        borderRadius: 10 // Add border radius here
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            font: {
                                family: "'Afacad Flux', sans-serif" // Change legend font family
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            family: "'Afacad Flux', sans-serif" // Change tooltip font family
                        },
                        callbacks: {
                            title: function () {
                                return ""; // Remove the header in the tooltip
                            },
                            label: function (tooltipItem) {
                                const value = tooltipItem.raw || 0;
                                const total = stockDataDashboard.reduce(
                                    (sum, current) => sum + current,
                                    0
                                );
                                const percentage = (
                                    (value / total) *
                                    100
                                ).toFixed(2);

                                const labelMapDashboard = {
                                    O: "Overstocked",
                                    N: "Normal",
                                    L: "Low",
                                    C: "Critical",
                                    OS: "Out of Stock"
                                };

                                const abbreviatedLabel = tooltipItem.label;
                                const fullLabel =
                                    labelMapDashboard[abbreviatedLabel] ||
                                    abbreviatedLabel;

                                return `${fullLabel}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: "end",
                        align: "end",
                        color: "rgba(39, 39, 39, 0.747)",
                        font: {
                            family: '"Baloo Paaji 2", sans-serif',
                            weight: 500,
                            size: 14
                        },
                        formatter: function (value) {
                            return value;
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            display: true,
                            callback: function (value, index) {
                                return this.getLabelForValue(index);
                            },
                            font: {
                                family: "'Afacad Flux', sans-serif"
                            }
                        },
                        grid: { display: false }
                    },
                    y: {
                        ticks: {
                            display: true,
                            beginAtZero: true,
                            font: {
                                family: "'Afacad Flux', sans-serif"
                            }
                        },
                        max: Math.ceil(
                            Math.max(...stockDataDashboard) / 20
                        ) * 20 + 20 // Dynamically calculate max value for y-axis
                    }
                }
            },
            plugins: [ChartDataLabels] // Register the plugin
        });
    } catch (error) {
        console.error("Error fetching or displaying data:", error);
    }
});
