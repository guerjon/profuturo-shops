<?php

class AdminApiDashboardController extends AdminBaseController
{
	/**
    * Hace los filtros sobre $buldier, y da por default las fechas de from y to en caso de que no
    *Se haya ingresado ninguna 
    */
    public function appendDateFilter($builder, $date_field = 'created_at') {
        
        $default_from = \Carbon\Carbon::create(2015, 12, 1, 0, 0, 0);
        $from = Input::get('from', $default_from->max(\Carbon\Carbon::today()->startOfMonth()->subYear()));
        $to = Input::get('to', \Carbon\Carbon::today()->endOfMonth());

        
        if(Input::has('divisional_id')){
            $builder->whereIn('divisional_id',Input::get('divisional_id'));
        }

        if(Input::has('region_id'))
            $builder->whereIn('region_id',Input::get('region_id'));

        if(Input::has('gerencia'))
            $builder->whereIn('region_id',Input::get('region_id'));

        return $builder->where($date_field, '>=', $from)->where($date_field, '<=', $to);
    }

    /*
    *Función de inicio, inicia la consulta a Order y calcula el numero de ordenes y gerencia
    *para posteriormente calcular el promedio gastado por gerencia y por pedido
    *total es la cantidad total del monto gastado en todos los productos
    */
    public function overview() {
        $request = Input::get();
        $orders = $this->appendDateFilter(Order::query(),'orders.created_at')->select(DB::raw('count(*) as c'))->first()->c;
        //people gerencias  pedido
        $gerencias_c = $this
                        ->appendDateFilter(Order::query())
                        ->whereHas('user',function($q){
                            $q->where('role','user_paper');
                        })
                        ->select(DB::raw('count(DISTINCT(user_id)) as c'))
                        ->first()
                        ->c;
        //total de gerencias
        $gerencias_s =  DB::table('users')
                            ->where('role','user_paper')
                            ->select(DB::raw('count(DISTINCT(id)) as c'))
                            ->first()
                            ->c;
    
        $gerencias = $gerencias_s - $gerencias_c;

        $total = DB::table('orders')->join('order_product','orders.id','=','order_product.order_id')
                    ->join('products','products.id','=','order_product.product_id')
                    ->select(DB::raw('SUM(products.price * order_product.quantity) as total'))
                    ->first()->total; 
        
        return Response::json([
            'orders'    => $orders,
            'people'    => $gerencias,
            'people_orders' => $gerencias_c,
            'total'     => $total
        ]);
    }


     public function products() {

        $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'),'categories.name as category')
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
        
        return Response::json([
            'query' => $query->get(),
        ]);

    }

    public function ordersByCategory() {
        $query = $this->appendDateFilter(Order::with('user'), 'orders.created_at')->where('orders.status', '<>', 'cart');
        $category = Input::get('category');

        $query->select(DB::raw('orders.*,SUM(products.price * order_product.quantity) as c'))
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('order_product.order_id')
            ->where('categories.id', '=', $category);
        
        return Response::json([
            'query' => $query()->get(),
        ]);
    }
    /*
    *Regresa los productos mas solicitados
    */
    public function topProducts()
    {
       $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'),'categories.name as category' )
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
        $query->limit(10);
        return Response::json($query->get());   
    }

    public function topReverseProducts()
    {
        $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'),'categories.name as category' )
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q')
            ->groupBy('products.id');
        $this->appendDateFilter($query, 'orders.created_at');
        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }
        $query->limit(10);
        return Response::json($query->get());          
    }

    public function biggestAmount()
    {
        
        $query = Product::select('users.*','products.*',
                                 DB::raw('SUM(products.price * order_product.quantity) AS q'),
                                 'regions.name as region_name' 
                                 )
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q','desc')
            ->groupBy('orders.id');

        $this->appendDateFilter($query, 'orders.created_at');

        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }

        $query->limit(10);
        return Response::json($query->get());             
    }

    public function smallestAmount()
    {
        $query = Product::select('users.*','products.*',
                                 DB::raw('SUM(products.price * order_product.quantity) AS q'),
                                 'regions.name as region_name' 
                                 )
            ->from('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('users','orders.user_id','=','users.id')
            ->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q')
            ->groupBy('orders.id');

        $this->appendDateFilter($query, 'orders.created_at');

        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }

        $query->limit(10);
        return Response::json($query->get());            
    }

    public function annualMonth() {
        $query = $this->appendDateFilter(Order::where('orders.status', '<>', 'cart'),'orders.created_at')
                    ->join('order_product','orders.id','=','order_product.order_id')
                    ->join('products', 'order_product.product_id', '=', 'products.id')
                    ->select(
                        DB::raw('SUM(products.price * order_product.quantity) as c'),
                        DB::raw('MONTH(orders.created_at) as month'),
                        DB::raw('YEAR(orders.created_at) as year')
                    )

            ->orderBy('year')->orderBy('month')
            ->groupBy('year')->groupBy('month');
        return Response::json($query->get());
    }


}