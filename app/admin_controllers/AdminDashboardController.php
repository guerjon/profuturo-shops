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
        $query = $this->query($month,$year);
        $top_products = $query['top_products']->select('products.*', DB::raw('SUM(order_product.quantity) AS q'))->get();
        $top_reverse_products = $query['top_reverse_products']->select('products.*', DB::raw('SUM(order_product.quantity) AS q'))->get();
        $biggest_amounts = $query['biggest_amounts']->select(
            'users.*',
            'products.*',
            DB::raw('SUM(products.price * order_product.quantity) AS q'),
            'regions.name as region_name')->get();
        $smallest_amounts = $query['smallest_amounts']->select('users.*','products.*',
                                 DB::raw('SUM(products.price * order_product.quantity) AS q'),
                                 'regions.name as region_name' 
                                 )->get();
        $all_orders = $query['all_orders']->select('*',
            DB::raw('SUM(products.price * order_product.quantity) AS m'),
            DB::raw('count(DISTINCT(user_id)) as q'))->get();
 
        return View::make('admin::dashboard/month')
        	->withTopProducts($top_products)
        	->withTopReverseProducts($top_reverse_products)
        	->withBiggestAmounts($biggest_amounts)
        	->withSmallestAmounts($smallest_amounts)
            ->withAllOrders($all_orders)
            ->withFrom($carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->withTo($carbon->endOfMonth());

    }

    public function overviewByMonthAmount($index,$month,$year)
    {
    	$carbon = Carbon::createFromDate($year, $month, 1);
        return View::make('admin::dashboard/month_amount');
    }


    private function query($month,$year)
    {
        $carbon = Carbon::createFromDate($year, $month, 1);

        $top_products = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q', 'desc')
            ->groupBy('order_product.product_id')
            ->limit(10);
           
        $top_reverse_products = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q')
            ->groupBy('order_product.product_id')
            ->limit(10);

        $biggest_amounts = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q','desc')
            ->groupBy('orders.id')
            ->limit(10);
        
        $smallest_amounts = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->orderBy('q')
            ->groupBy('orders.id')
            ->limit(10);

        $all_orders = Order::join('order_product', 'order_product.order_id', '=', 'orders.id')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->join('users','orders.user_id','=','users.id')
                ->join('regions','users.region_id','=','regions.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
                ->where('orders.created_at', '<=', $carbon->endOfMonth())
                ->orderBy('ccosto')
                ->groupBy('orders.id');

        return ['top_products' => $top_products,
                'top_reverse_products' => $top_products,
                'biggest_amounts' => $biggest_amounts,
                'smallest_amounts' => $smallest_amounts,
                'all_orders' => $all_orders];
    }

}
