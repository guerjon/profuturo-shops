$(function(){

    var categoryChart,annualChart,annualMonthChart;
    var computedMonths, computedYears, computedCategories;
    
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

    var tableTopProducts = function(category_id){
        $.get(laroute.action('AdminApiDashboardController@topProducts',{
            category : category_id
        }), $('#filters-form').serialize(),function(products){
                
                var $tbody = $('#top-products tbody');
                $tbody.empty();

                for(var i=0; i< products.length && i< 10; i++) {
                    var product = products[i];

                    var $tr = $('<tr>');
                    $tr.append(
                        $('<td>').text(product.category)
                    ).append(
                        $('<th>').text(product.name)
                        
                    ).append(
                        $('<td>').text(numeral(product.q).format('0,0'))
                    );
                    $tbody.append($tr);
                }           
        });
    }

    var tableTopReverseProducts = function(category_id){
        $.get(laroute.action('AdminApiDashboardController@topReverseProducts',{
            category : category_id
        }), $('#filters-form').serialize(),function(products){
                        
                var $tbody = $('#top-reverse-products tbody');
                $tbody.empty();

                for (var i = 0 ; i <10; i++) {
                    var product = products[i];
                    var $tr = $('<tr>');

                    $tr.append(
                        $('<td>').text(product.category)
                    ).append(
                        $('<th>').text(product.name)
                        
                    ).append(
                        $('<td>').text(numeral(product.q).format('0,0'))
                    );
                    $tbody.append($tr);
                };
        });
    }
    var tableBiggestAmount = function(category_id){
        $.get(laroute.action('AdminApiDashboardController@biggestAmount',{
            category : category_id
        }), $('#filters-form').serialize(),function(orders){
                var $tbody = $('#biggest-amount tbody');
                $tbody.empty();

                for (var i = 0 ; i <10; i++) {
                    var order = orders[i];
                    var $tr = $('<tr>');

                    $tr.append(
                        $('<td>').text(order.ccosto)
                    ).append(
                        $('<th>').text(order.gerencia)
                    ).append(
                        $('<th>').text(order.region_name)
                    ).append(
                        $('<td>').text(numeral(order.q).format('0,0'))
                    );
                    $tbody.append($tr);
                };
        });
    };

    var tableSmallestAmount = function(category_id){
        $.get(laroute.action('AdminApiDashboardController@smallestAmount',{
            category : category_id
        }), $('#filters-form').serialize(),function(orders){
                        
                var $tbody = $('#smallest-amount tbody');
                $tbody.empty();

                for (var i = 0 ; i <10; i++) {
                    var order = orders[i];
                    var $tr = $('<tr>');

                    $tr.append(
                        $('<td>').text(order.ccosto)
                    ).append(
                        $('<th>').text(order.gerencia)
                    ).append(
                        $('<th>').text(order.region_name)
                    ).append(
                        $('<td>').text(numeral(order.q).format('0,0'))
                    );
                    $tbody.append($tr);
                };
        });
    }

    var drawCategoryChart = function() {
        $('#orders-by-cat-table').parents('.panel').parent().addClass('hide');
        $('#top-categories').parents('.panel').parent().removeClass('hide');
        $.get(laroute.action('AdminApiDashboardController@categories'), $('#filters-form').serialize(), function(categories){
            computedCategories = categories;
            tableTopProducts('');
            tableTopReverseProducts('');
            tableBiggestAmount('');
            tableSmallestAmount('');
            var labels = [];
            var data = [];
            for(var i=0; i<categories.length; i++) {
                var category = categories[i];
                labels.push(category.name);
                data.push(parseFloat(category.q));
            }

            if(categoryChart) {
                categoryChart.destroy();
            }
            var ctx = $('#categories-overview').get(0).getContext('2d');
            categoryChart = new Chart(ctx, {
                type : 'doughnut',
                data : {
                    labels : labels,
                    datasets : [{
                        data : data,
                        backgroundColor : [
                            '#000022',
                            '#000044',
                            '#000066',
                            '#000088',
                            '#000099',
                            '#0000AA',
                        ]
                        // backgroundColor : ['#cdcdcd'],
                        // hoverBackgroundColor : ['#663366'],
                    }]
                },
                options : {
                    legend : {
                        display : true
                    }
                }
            });
        });

    }

    var tableOrdersByPeriod = function(month, year) {
        var date = moment([year, month-1, 1]).format('MMMM YYYY');
        $('#period-title').text(date);
        $.get(laroute.action('AdminApiDashboardController@ordersByPeriod', {
            month : month, 
            year : year
        }), $('#filters-form').serialize(), function(data){
            
        });
    }

    var tableOrdersByCategory = function(category) {
    
        tableTopProducts(category.id);
        tableTopReverseProducts(category.id);
        tableBiggestAmount(category.id);
        tableSmallestAmount(category.id);
    }


    

    var drawAnnualChart = function() {
        $.get(laroute.action('AdminApiDashboardController@annual'), $('#filters-form').serialize(), function(months) {
            var labels = [];
            var data = [];
            var last = {};
            computedMonths = [];
            computedYears = [];
            $.each(months, function(idx, elem){
                var month = elem.month;
                var year = elem.year;
                computedYears.push(year);
                computedMonths.push(month);
                last = {
                    month : month,
                    year : year,
                }
                var value = elem.c;
                var m = moment([year, month-1, 1]);
                labels.push(m.format('MMM'));
                data.push(value);
            });

            if(annualChart) {
                annualChart.destroy();
            }
            var ctx = $('#annual-overview').get(0).getContext('2d');
            annualChart = new Chart(ctx, {
                type : 'bar',
                options : {
                    legend : {
                        display : false
                    }
                },
                data : {
                    labels : labels,
                    datasets : [{
                        backgroundColor : '#c2c2c2',
                        borderColor : '#ACACAC',
                        borderWidth : 1,
                        hoverBackgroundColor : '#0c4aa6',
                        hoverBorderColor : '#000088',
                        data : data,
                    }]
                }
            });
            tableOrdersByPeriod(last.month, last.year);
        });
    }

    var drawAnnualMonthChart = function() {
        $.get(laroute.action('AdminApiDashboardController@annualMonth'), $('#filters-form').serialize(), function(months) {
            var labels = [];
            var data = [];
            var last = {};
            computedMonths = [];
            computedYears = [];
            $.each(months, function(idx, elem){
                var month = elem.month;
                var year = elem.year;
                computedYears.push(year);
                computedMonths.push(month);
                last = {
                    month : month,
                    year : year,
                }
                var value = elem.c;
                var m = moment([year, month-1, 1]);
                labels.push(m.format('MMM'));
                data.push(value);
            });

            if(annualMonthChart) {
                annualMonthChart.destroy();
            }
            var ctx = $('#annual-month-overview').get(0).getContext('2d');
            annualMonthChart = new Chart(ctx, {
                type : 'bar',
                options : {
                    legend : {
                        display : false
                    }
                },
                data : {
                    labels : labels,
                    datasets : [{
                        backgroundColor : '#c2c2c2',
                        borderColor : '#ACACAC',
                        borderWidth : 1,
                        hoverBackgroundColor : '#0c4aa6',
                        hoverBorderColor : '#000088',
                        data : data,
                    }]
                }
            });
            tableOrdersByPeriod(last.month, last.year);
        });
    }

    $('#from').datepicker({
        maxDate : new Date(),
        onSelect: function( selectedDate ) {
            $( "#to" ).datepicker("option", "minDate", selectedDate );
        },
        dateFormat:  'yy-mm-dd'
    });

    $('#to').datepicker({
        minDate : $('#from').val(),
        onSelect: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
        },
        dateFormat:  'yy-mm-dd'
    });

    $(document).on('click', '#period-table-pagination a', function(event){
        event.preventDefault();
        $.get($(this).attr('href'), function(data){
            var pages = data.pages;
            var orders = data.pagination.data;
        });
    });

    $(document).on('click', '#orders-by-cat-pagination a', function(event){
        event.preventDefault();
        $.get($(this).attr('href'), function(data){
            var pages = data.pages;
            var orders = data.pagination.data;
        });
    });

    
    $('#filters-form').submit(function(event) {
        loadOverview();
        drawCategoryChart();
        drawAnnualChart();
        return false;
    });


    $('#annual-overview').click(function(event) {
        if(annualChart) {
            var items = annualChart.getElementsAtEvent(event);
            if(items.length == 0) return;
            
            var item = items[0];
            //El index empezando de 0 desde hace un mes en la grafica
            var index = item._index;
            //El mes del index empezando de 0 desde hace un mes en la grafica
            var month = computedMonths[index];
            var year  = computedYears[index];
            var action = laroute.action(
                            'AdminDashboardController@overviewByMonth',
                            {'index':index,'month':month,'year':year });
            
            location.href = action;
        }
    });

    $('#annual-month-overview').click(function(event) {
        if(annualChart) {
            var items = annualChart.getElementsAtEvent(event);
            if(items.length == 0) return;
            
            var item = items[0];
            //El index empezando de 0 desde hace un mes en la grafica
            var index = item._index;
            //El mes del index empezando de 0 desde hace un mes en la grafica
            var month = computedMonths[index];
            var year  = computedYears[index];
            var action = laroute.action(
                            'AdminDashboardController@overviewByMonthAmount',
                            {'index':index,'month':month,'year':year });
        
            location.href = action;
        }
    });

    $('#categories-overview').click(function(event) {
        if(categoryChart) {
            var items = categoryChart.getElementsAtEvent(event);
            if(items.length == 0) return;
            var item = items[0];
            var index = item._index;
            var category = computedCategories[index];
            tableOrdersByCategory(category);
        }
    });



    loadOverview();
    drawCategoryChart();
    drawAnnualChart();
    drawAnnualMonthChart();


    $('.select2').select2({theme:'bootstrap','placeholder':'Todas'});

});