<?php

use \Carbon\Carbon as Carbon;

class AdminDashboardController  extends AdminBaseController {
	
	public function stationery()
	{
		return View::make('admin::dashboard/stationery');
	}

    public function overviewByMonth($index,$month,$year)
    {
      	$carbon = Carbon::createFromDate($year, $month, 1);
       
       	$top_products = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'))
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q', 'desc')
            ->groupBy('order_product.product_id')
            ->limit(10)->get();
           
       	$top_reverse_products = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'))
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q')
            ->groupBy('order_product.product_id')
            ->limit(10)->get();

        $biggest_amounts = Product::select('users.*','products.*',
                                 DB::raw('SUM(products.price * order_product.quantity) AS q'),
                                 'regions.name as region_name' 
                                 )
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q','desc')
            ->groupBy('orders.id')
            ->limit(10)
            ->get();
        
        $smallest_amounts = Product::select('users.*','products.*',
                                 DB::raw('SUM(products.price * order_product.quantity) AS q'),
                                 'regions.name as region_name' 
                                 )
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q')
            ->groupBy('orders.id')
            ->limit(10)
            ->get();

        $all_orders = Order::select('*',
            DB::raw('SUM(products.price * order_product.quantity) AS m'),
            DB::raw('count(DISTINCT(user_id)) as q'))
                ->join('order_product', 'order_product.order_id', '=', 'orders.id')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->join('users','orders.user_id','=','users.id')
                ->join('regions','users.region_id','=','regions.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
                ->where('orders.created_at', '<=', $carbon->endOfMonth())
                ->orderBy('ccosto')
                ->groupBy('orders.id')
                ->get();

        return View::make('admin::dashboard/month')
        	->withTopProducts($top_products)
        	->withTopReverseProducts($top_reverse_products)
        	->withBiggestAmounts($biggest_amounts)
        	->withSmallestAmounts($smallest_amounts)
            ->withAllOrders($all_orders);

    }

    public function overviewByMonthAmount($index,$month,$year)
    {
    	$carbon = Carbon::createFromDate($year, $month, 1);
        
       

        return View::make('admin::dashboard/month_amount');
    }

}
