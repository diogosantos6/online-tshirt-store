document.addEventListener("DOMContentLoaded", function () {
    // Closed orders per month
    try {
        var ordersClosedPerMonth = JSON.parse(document.getElementById('jsonClosedOrdersPerMonth').value);
        ordersClosedPerMonth = removerMesesNaoPassados(ordersClosedPerMonth);
        var ctx_ordersClosedPerMonth = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient_ordersClosedPerMonth = ctx_ordersClosedPerMonth.createLinearGradient(0, 0, 0, 225);
        gradient_ordersClosedPerMonth.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient_ordersClosedPerMonth.addColorStop(1, "rgba(215, 227, 244, 0)");
        var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        createGraph("chartjs-dashboard-line", gradient_ordersClosedPerMonth, ordersClosedPerMonth, "line", "Encomendas", labels);
    } catch (e) {
        console.log(e);
    }

    // Orders per month
    try {
        var ordersPerMonth = JSON.parse(document.getElementById('jsonOrdersPerMonth').value);
        removerMesesNaoPassados(ordersPerMonth);
        var ctx_ordersPerMonth = document.getElementById("line-chart").getContext("2d");
        var gradient_ordersPerMonth = ctx_ordersPerMonth.createLinearGradient(0, 0, 0, 225);
        gradient_ordersPerMonth.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient_ordersPerMonth.addColorStop(1, "rgba(215, 227, 244, 1)");
        var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        createGraph("line-chart", gradient_ordersPerMonth, ordersPerMonth, "bar", "Encomendas", labels);
    } catch (e) {
        console.log(e);
    }

    // Revenue per month
    try {
        var revenuePerMonth = JSON.parse(document.getElementById('jsonRevenuePerMonth').value);
        removerMesesNaoPassados(revenuePerMonth);
        var ctx_revenuePerMonth = document.getElementById("line-chart2").getContext("2d");
        var gradient_revenuePerMonth = ctx_revenuePerMonth.createLinearGradient(0, 0, 0, 225);
        gradient_revenuePerMonth.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient_revenuePerMonth.addColorStop(1, "rgba(215, 227, 244, 0)");
        var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        createGraph("line-chart2", gradient_revenuePerMonth, revenuePerMonth, "line", "Receita", labels);
    } catch (e) {
        console.log(e);
    }

    // Top 10 Sold T-Shirts per month
    try {
        var topTShirts = JSON.parse(document.getElementById('jsonMostSoldTshirtImagesPerMonth').value);

        var tShirtNames = topTShirts.map(function (item) {
            return item.name;
        });

        var quantitiesSold = topTShirts.map(function (item) {
            return item.total_quantity_sold;
        });

        var ctx_topTShirts = document.getElementById("chartjs-top-tshirts").getContext("2d");
        var gradient_topTShirts = ctx_topTShirts.createLinearGradient(0, 0, 0, 225);
        gradient_topTShirts.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient_topTShirts.addColorStop(1, "rgba(215, 227, 244, 1)");
        var stepSize = 50;
        createGraph("chartjs-top-tshirts", gradient_topTShirts, quantitiesSold, "bar", "Most Sold T-Shirts", tShirtNames, stepSize);
    } catch (e) {
        console.log(e);
    }

    // Top 10 most sold colors per month
    try {
        var topColors = JSON.parse(document.getElementById('jsonMostSoldColorsPerMonth').value);

        var colorNames = topColors.map(function (item) {
            return item.name;
        });

        var quantitiesSold = topColors.map(function (item) {
            return item.total_sold;
        });

        var ctx_topColors = document.getElementById("chartjs-top-colors").getContext("2d");
        var gradient_topColors = ctx_topColors.createLinearGradient(0, 0, 0, 225);
        gradient_topColors.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient_topColors.addColorStop(1, "rgba(215, 227, 244, 1)");
        var stepSize = 50;
        createGraph("chartjs-top-colors", gradient_topColors, quantitiesSold, "bar", "Most Sold Colors", colorNames, stepSize);
    } catch (e) {
        console.log(e);
    }

    // Top 10 most sold categories per month
    try {
        var topCategories = JSON.parse(document.getElementById('bestSellingCategoriesPerMonth').value);

        var categoryNames = topCategories.map(function (item) {
            return item.category_name;
        });

        var quantitiesSold = topCategories.map(function (item) {
            return item.total_sold;
        });

        var ctx_topCategories = document.getElementById("chartjs-top-categories").getContext("2d");
        var gradient_topCategories = ctx_topCategories.createLinearGradient(0, 0, 0, 225);
        gradient_topCategories.addColorStop(0, "#0057a3");
        gradient_topCategories.addColorStop(1, "rgba(0, 87, 163, 0.7)");
        var stepSize = 1000;
        createGraph("chartjs-top-categories", gradient_topCategories, quantitiesSold, "bar", "Quantidade", categoryNames, stepSize);
    } catch (e) {
        console.log(e);
    }

    // Proporção de categorias
    try {
        var tshirt_imagesPerCategory = JSON.parse(document.getElementById('tshirt_imagesPerCategory').value);

        var categoryNames = tshirt_imagesPerCategory.map(function (item) {
            return item.name;
        });

        var tshirt_count = tshirt_imagesPerCategory.map(function (item) {
            return item.tshirt_count;
        });

        createPieGraph("chartjs-dashboard-pie-category", tshirt_count, categoryNames);
    } catch (e) {
        console.log(e);
    }
});

function createPieGraph(elementId, graphData, labels) {
        // Pie chart
        var colors = [
            "#0057a3",
            "rgba(0, 87, 163, 0.9)",
            "rgba(0, 87, 163, 0.8)",
            "rgba(0, 87, 163, 0.7)",
            "rgba(0, 87, 163, 0.6)",
            "rgba(0, 87, 163, 0.5)",
            "rgba(0, 87, 163, 0.4)",
            "rgba(0, 87, 163, 0.3)",
            "rgba(0, 87, 163, 0.2)",
            "rgba(0, 87, 163, 0.15)"
          ];

        new Chart(document.getElementById(elementId), {
            type: "pie",
            data: {
                labels: labels,
                datasets: [{
                    data: graphData,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: "transparent"
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                }
            }
        });
}


function createGraph(elementId, gradient, graphData, graphType, graphLabel, labels, stepSize = 100) {
    new Chart(document.getElementById(elementId), {
        type: graphType,
        data: {
            labels: labels,
            datasets: [{
                label: graphLabel,
                fill: true,
                backgroundColor: gradient,
                borderColor: window.theme.primary,
                data: graphData
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                intersect: false
            },
            hover: {
                intersect: true
            },
            plugins: {
                filler: {
                    propagate: false
                }
            },
            scales: {
                xAxes: [{
                    reverse: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: stepSize
                    },
                    display: true,
                    borderDash: [3, 3],
                    gridLines: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }]
            }
        }
    });
}

function removerMesesNaoPassados(content) {
    // Remover os meses que ainda não passaram
    var currentYear = new Date().getFullYear();
    if (document.getElementById('year').value == currentYear) {
        var currentMonth = new Date().getMonth() + 1;
        content = content.slice(0, currentMonth);
    }
    return content;
}
