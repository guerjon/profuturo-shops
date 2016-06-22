<?php

class AdminApiDashboardController extends AdminBaseController
{
	
    public function overview() {
        $request = Input::get();
        $orders = $this->appendDateFilter(Order::query())->select(DB::raw('count(*) as c'))->first()->c;
        $people = $this->appendDateFilter(Order::query())->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;

        $total = DB::table('orders')->join('order_product','orders.id','=','order_product.order_id')
                    ->join('products','products.id','=','order_product.product_id')
                    ->select(DB::raw('SUM(products.price * order_product.quantity) as total'))
                    ->first()->total; 
        
        return Response::json([
            'orders'    => $orders,
            'people'    => $people,
            'total'     => $total
        ]);
    }

	public function appendDateFilter($builder, $date_field = 'created_at') {
        
        $default_from = \Carbon\Carbon::create(2015, 12, 1, 0, 0, 0);
        $from = Input::get('from', $default_from->max(\Carbon\Carbon::today()->startOfMonth()->subYear()));
        $to = Input::get('to', \Carbon\Carbon::today()->endOfMonth());
        return $builder->where($date_field, '>=', $from)->where($date_field, '<=', $to);
    }

     public function products() {

        $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'))
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q', 'desc')
            ->groupBy('products.id');
        $this->appendDateFilter($query, 'orders.created_at');
        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }
        $query->limit(15);
        return Response::json($query->get());   
    }


    public function categories() {
        $query = Category::select('categories.*', DB::raw('SUM(order_product.quantity) AS q'))->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', '<>', 'cart')
            ->orderBy('q', 'desc')
            ->groupBy('categories.id');
        $this->appendDateFilter($query, 'orders.created_at');
        return Response::json($query->get());
    }


    public function annual() {
        $query = $this->appendDateFilter(Order::where('orders.status', '<>', 'cart'));
        $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'))
            ->orderBy('year')->orderBy('month')
            ->groupBy('year')->groupBy('month');
        return Response::json($query->get());
    }

    public function ordersByPeriod() {
        $query = $this->appendDateFilter(Order::with('user'))->where('orders.status', '<>', 'cart');
        $month = Input::get('month',\Carbon\Carbon::today()->month);
        $year = Input::get('year', \Carbon\Carbon::today()->year);
        $carbon = \Carbon\Carbon::createFromDate($year, $month, 1);
        $query->where('created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('created_at', '<=', $carbon->endOfMonth());
        $pagination = $query->simplePaginate(10);
        $pages = $pagination->appends(Input::all())->links();

        return Response::json([
            'pagination' => $pagination->toArray(),
            'pages' => $pages
        ]);

    }

    public function ordersByCategory() {
        $query = $this->appendDateFilter(Order::with('user'), 'orders.created_at')->where('orders.status', '<>', 'cart');
        $category = Input::get('category');
        $query->select('orders.*')->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.id', '=', $category);
        
        $pagination = $query->simplePaginate(5);
        $pages = $pagination->appends(Input::all())->links();
        
        return Response::json([
            'pagination' => $pagination->toArray(),
            'pages' => $pages
        ]);

    }


}