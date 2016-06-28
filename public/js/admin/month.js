$(function(){

    var loadOverview = function(){
        var action = laroute.action('AdminApiDashboardController@overview');
        
        $.get(action, $('#filters-form').serialize(), function(data){
            console.log(data);
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

    loadOverview();
   
});