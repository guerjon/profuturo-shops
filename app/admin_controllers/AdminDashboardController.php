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
        $paper_type = Input::get('paper-type','orders');

        switch ($paper_type) {
            case 'orders':
                # code...
                break;

            case 'furniture_orders':
                # code...
                break;

            case 'mac_orders':
                # code...
                break;

            case 'corporation_orders':
                # code...
                break;
            
            default:
                break;
        }


        
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

        $products = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
            ->groupBy('order_product.product_id')
            ->limit(10);
        

        $amounts = Product::from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where('orders.created_at', '<=', $carbon->endOfMonth())
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

        return ['top_products' => $this->filters($products->orderBy('q', 'desc')),
                'top_reverse_products' => $this->filters(clone $products->orderBy('q')),
                'biggest_amounts' => $this->filters($amounts->orderBy('q','desc')),
                'smallest_amounts' => $this->filters(clone $amounts->orderBy('q')),
                'all_orders' => $this->filters($all_orders)];
    }

    public function filters($builder)
    {   
        
        if(Input::has('divisional_id')){
            $builder->whereIn('users.divisional_id',Input::get('divisional_id'));
        }

        if(Input::has('region_id')){
            $builder->whereIn('region_id',Input::get('region_id'));
        }

        if(Input::has('gerencia')){
            $builder->whereIn('gerencia',Input::get('gerencia'));
        }

        return $builder;

    }
}
