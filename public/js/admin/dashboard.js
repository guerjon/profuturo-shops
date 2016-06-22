$(function(){

    var categoryChart,annualChart;
    var computedMonths, computedYears, computedCategories;
    
    /*
    *
    */
    function handlePaginationData(items, pages, table, paginationContainer) {
        if(pages.length == 0) {
            paginationContainer.hide();
        }else {
            paginationContainer.show();
        }
        paginationContainer.html(pages);
        var tbody = table.find('tbody');
        tbody.empty();
        for(var i=0; i< items.length; i++) {
            var item = items[i];
            var tr = $('<tr>');
            tr.append(
                $('<td>').text(numeral(item.id).format('00000'))
            ).append(
                $('<td>').text(item.user.gerencia)
            ).append(
                $('<td>').text(numeral(item.c).format('0,0.00'))
            );
            tbody.append(tr);
        }
    }


    var loadOverview = function(){
        var action = laroute.action('AdminApiDashboardController@overview');
        
        $.get(action, $('#filters-form').serialize(), function(data){
            
            $('#people').text(numeral(data.people).format('0,0'));
            $('#orders').text(numeral(data.orders).format('0,0'));
            $('#total').text(numeral(data.total).format('0,0'));
            data.avg = data.total / data.people;
            data.avg_order = data.total / data.orders;
            $('#avg').text(numeral(data.avg).format('0,0.00'));
            $('#avg-order').text(numeral(data.avg_order).format('0,0.00'));
        })
    }


    var tableTopCategories = function(categories) {
        var $tbody = $('#top-categories tbody');
        $tbody.empty();

        for(var i=0; i< categories.length && i< 5; i++) {
            var category = categories[i];

            var $tr = $('<tr>');
            $tr.append(
                $('<th>').text(category.name)
            ).append(
                $('<td>').text(numeral(category.q).format('0,0'))
            );
            $tbody.append($tr);
        }
    }

    var tableTopProducts = function(category_id) {
        $.get(laroute.action('AdminApiDashboardController@products', {
            category : category_id
        }), $('#filters-form').serialize(), function(products){
            
            var $tbody = $('#top-products tbody');
            $tbody.empty();
            for(var i=0; i< products.length && i< 5; i++) {
                var product = products[i];
                var $tr = $('<tr>');
                $tr.append(
                    $('<th>').append($('<abbr>').text(product.name).attr('title', product.name)).addClass('ellipsis')
                ).append(
                    $('<td>').text(numeral(product.q).format('0,0'))
                );
                $tbody.append($tr);
            }
        });      
    }

    var drawCategoryChart = function() {
        $('#orders-by-cat-table').parents('.panel').parent().addClass('hide');
        $('#top-categories').parents('.panel').parent().removeClass('hide');
        $.get(laroute.action('AdminApiDashboardController@categories'), $('#filters-form').serialize(), function(categories){
            computedCategories = categories;
            tableTopCategories(categories);
            tableTopProducts('');
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
                        display : false
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
            var pages = data.pages;
            var orders = data.pagination.data;
            handlePaginationData(orders, pages, $('#period-table'), $('#period-table-pagination'));
        });
    }

    var tableOrdersByCategory = function(category) {
        tableTopProducts(category.id);
        $('#orders-by-cat-table').parents('.panel').parent().removeClass('hide');
        $('#top-categories').parents('.panel').parent().addClass('hide');
        $('#orders-by-cat-title').text(category.name);
        $.get(laroute.action('AdminApiDashboardController@ordersByCategory', {
            category : category.id,
        }), $('#filters-form').serialize(), function(data){

            var pages = data.pages;
            var orders = data.pagination.data;

            handlePaginationData(orders, pages, $('#orders-by-cat-table'), $('#orders-by-cat-pagination'));
        });
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

    $('#from').datepicker({
        maxDate : new Date(),
        onSelect: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
        }
    });

    $('#to').datepicker({
        minDate : $('#from').val(),
        onSelect: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    $(document).on('click', '#period-table-pagination a', function(event){
        event.preventDefault();
        $.get($(this).attr('href'), function(data){
            var pages = data.pages;
            var orders = data.pagination.data;
            handlePaginationData(orders, pages, $('#period-table'), $('#period-table-pagination'));
        });
    });

    $(document).on('click', '#orders-by-cat-pagination a', function(event){
        event.preventDefault();
        $.get($(this).attr('href'), function(data){
            var pages = data.pages;
            var orders = data.pagination.data;
            handlePaginationData(orders, pages, $('#orders-by-cat-table'), $('#orders-by-cat-pagination'));
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
            var index = item._index;
            var month = computedMonths[index];
            var year  = computedYears[index];
            tableOrdersByPeriod(month, year);
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

});