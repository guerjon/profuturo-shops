$(function(){

    var loadOverview = function(){
        var action = laroute.action('AdminApiDashboardController@overview');
        
        $.get(action, $('#filters-form').serialize(), function(data){
            
            $('#people').text(numeral(data.people).format('0,0'));
            $('#people-orders').text(numeral(data.people_orders).format('0,0'));
            $('#orders').text(numeral(data.orders).format('0,0'));
            $('#total').text(numeral(data.total).format('0,0'));
            data.avg = data.total / data.people;
            data.avg_order = data.total / data.orders;
            $('#avg').text(numeral(data.avg).format('0,0.00'));
            $('#avg-order').text(numeral(data.avg_order).format('0,0.00'));
        })
    }

    


   
    var drawCharts = function(){
        
        var action = laroute.action('AdminApiDashboardController@productsByMonth');
        
        $.get(action,$('#filters-form').serialize(), function(datos){
            var products = datos.products;
            var furnitures = datos.furnitures;
            var mac_products = datos.mac_products;
            var corporation_products = datos.corporation_products;
        
            drawProductsChart(products,'products');
            //drawProductsChart(furnitures,'furnitures');
            //drawProductsChart(mac_products,'mac_products');
           // drawProductsChart(corporation_products,'corporation_products');
        });


            // lineChartData = {}; //declare an object
            // lineChartData.labels = []; //add 'labels' element to object (X axis)
            // lineChartData.datasets = []; //add 'datasets' array element to object

            // for (line = 0; line < 4; line++) {
            //     y = [];
            //     lineChartData.datasets.push({}); //create a new line dataset
            //     dataset = lineChartData.datasets[line]
            //     dataset.fillColor = "rgba(0,0,0,0)";
            //     dataset.strokeColor = "rgba(200,200,200,1)";
            //     dataset.data = []; //contains the 'Y; axis data

            //     for (x = 0; x < 10; x++) {
            //         y.push(line + x); //push some data aka generate 4 distinct separate lines
            //         if (line === 0)
            //             lineChartData.labels.push(x); //adds x axis labels
            //     } //for x

            //     lineChartData.datasets[line].data = y; //send new line data to dataset
            // } //for line

            
            
            //myLineChart = new  Chart(ctx,{type : 'line',data:{lineChartData.labels,lineChartData.datasets}});


    }

    function drawProductsChart(products,graph){
        console.log(products);

        var data = {};
        data.labels = [];
        data.datasets = [];

        var months = ["relleno","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];



        for(var i=0; i < 100; i++) {
            console.log(products[i]);
            //console.log(products[i].month);

            // var product = products[i];
            // console.log(months[]);
            // data.labels.push(months[product[i].month]);
            // data.datasets.push({
            //     label: "Productos",
            //     fill: false,
            //     lineTension: 0.1,
            //     backgroundColor: "rgba(75,192,192,0.4)",
            //     borderColor: "rgba(75,192,192,1)",
            //     borderCapStyle: 'butt',
            //     borderDash: [],
            //     borderDashOffset: 0.0,
            //     borderJoinStyle: 'miter',
            //     pointBorderColor: "rgba(75,192,192,1)",
            //     pointBackgroundColor: "#fff",
            //     pointBorderWidth: 1,
            //     pointHoverRadius: 5,
            //     pointHoverBackgroundColor: "rgba(75,192,192,1)",
            //     pointHoverBorderColor: "rgba(220,220,220,1)",
            //     pointHoverBorderWidth: 2,
            //     pointRadius: 1,
            //     pointHitRadius: 10,
            //     data: [65, 59, 80, 81, 56, 55, 40],                
            //});
        }
        //parseFloat(product.c)

        // ctx = document.getElementById("products").getContext("2d");

        // var data = {
        //     labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio"],
        //     datasets: [
        //         {
        //             label: "Producto 1",
        //             fill: false,
        //             lineTension: 0.1,
        //             backgroundColor: "rgba(75,192,192,0.4)",
        //             borderColor: "rgba(75,192,192,1)",
        //             borderCapStyle: 'butt',
        //             borderDash: [],
        //             borderDashOffset: 0.0,
        //             borderJoinStyle: 'miter',
        //             pointBorderColor: "rgba(75,192,192,1)",
        //             pointBackgroundColor: "#fff",
        //             pointBorderWidth: 1,
        //             pointHoverRadius: 5,
        //             pointHoverBackgroundColor: "rgba(75,192,192,1)",
        //             pointHoverBorderColor: "rgba(220,220,220,1)",
        //             pointHoverBorderWidth: 2,
        //             pointRadius: 1,
        //             pointHitRadius: 10,
        //             data: [65, 59, 80, 81, 56, 55, 40],
        //         },                
        //         {
        //             label: "Producto 2",
        //             fill: false,
        //             lineTension: 0.1,
        //             backgroundColor: "rgba(75,192,192,0.4)",
        //             borderColor: "rgba(75,192,192,1)",
        //             borderCapStyle: 'butt',
        //             borderDash: [],
        //             borderDashOffset: 0.0,
        //             borderJoinStyle: 'miter',
        //             pointBorderColor: "rgba(75,192,192,1)",
        //             pointBackgroundColor: "#fff",
        //             pointBorderWidth: 1,
        //             pointHoverRadius: 5,
        //             pointHoverBackgroundColor: "rgba(75,192,192,1)",
        //             pointHoverBorderColor: "rgba(220,220,220,1)",
        //             pointHoverBorderWidth: 2,
        //             pointRadius: 1,
        //             pointHitRadius: 10,
        //             data: [10, 6, 30, 81, 25, 33, 13],
        //         }
        // //     ],

        // };

        // var myLineChart = new Chart(ctx, {
        //     type: 'line',
        //     data: data,
        //     options:{
        //                 scales: {
        //                     xAxes: [{
        //                         display: true
        //                     }]
        //                 }
        //             }
        // });
    }

    function function_name (argument) {
        // body...
    }

    loadOverview();
    drawCharts();

});