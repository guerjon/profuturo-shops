<?php

class AdminApiDashboardController extends AdminBaseController
{
	/**
    * Hace los filtros sobre $buldier, y da por default las fechas de from y to en caso de que no
    *Se haya ingresado ninguna 
    */
    public function appendDateFilter($builder, $date_field,$table) {
        
        $default_from = \Carbon\Carbon::create(2015, 12, 1, 0, 0, 0);
        $from = Input::get('from', $default_from->max(\Carbon\Carbon::today()->startOfMonth()->subYear()));
        $to = Input::get('to', \Carbon\Carbon::today()->endOfMonth());

        $builder->join('users','users.id','=',$table);
        
        if(Input::has('divisional_id')){
            $builder->whereIn('divisional_id',Input::get('divisional_id'));
        }

        if(Input::has('region_id')){
            $builder->whereIn('region_id',Input::get('region_id'));
        }

        if(Input::has('gerencia')){
            $builder->whereIn('gerencia',Input::get('gerencia'));
        }

        return $builder->where($date_field, '>=', $from)->where($date_field, '<=', $to);
    }

    /*
    *Función de inicio, inicia la consulta a Order y calcula el numero de ordenes y gerencia
    *para posteriormente calcular el promedio gastado por gerencia y por pedido
    *total es la cantidad total del monto gastado en todos los productos
    */
    public function overview() {
        $request = Input::get();
        $paper_type = Input::get('paper-type','orders');
        $gerencias_c = 0;
        $gerencias_s = 0;
        

        $orders_o = $this->appendDateFilter(Order::query(),'orders.created_at','orders.user_id')->select(DB::raw('count(*) as c'))->first()->c;
        $gerencias_c_o = $this->appendDateFilter(Order::query(),'orders.created_at','orders.user_id')->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;
        $gerencias_s_o =  DB::table('users')->where('role','user_paper')->select(DB::raw('count(DISTINCT(id)) as c'))->first()->c;

        $total_o = DB::table('orders')->join('order_product','orders.id','=','order_product.order_id')
            ->join('products','products.id','=','order_product.product_id')
            ->select(DB::raw('SUM(products.price * order_product.quantity) as total'))
            ->first()->total; 

        $orders_f = $this->appendDateFilter(FurnitureOrder::query(),'furniture_orders.created_at','furniture_orders.user_id')->select(DB::raw('count(*) as c'))->first()->c;
        $gerencias_c_f = $this->appendDateFilter(FurnitureOrder::query(),'furniture_orders.created_at','furniture_orders.user_id')->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;
        $gerencias_s_f =  DB::table('users')->where('role','user_furnitures')->select(DB::raw('count(DISTINCT(id)) as c'))->first()->c;

        $total_f = DB::table('furniture_orders')->join('furniture_furniture_order','furniture_orders.id','=','furniture_furniture_order.furniture_order_id')
                        ->join('furnitures','furnitures.id','=','furniture_furniture_order.furniture_id')
                        ->select(DB::raw('SUM(furnitures.unitary * furniture_furniture_order.quantity) as total'))
                        ->first()
                        ->total; 
        $orders_m = $this->appendDateFilter(MacOrder::query(),'mac_orders.created_at','mac_orders.user_id')->select(DB::raw('count(*) as c'))->first()->c;
        $gerencias_c_m = $this->appendDateFilter(MacOrder::query(),'mac_orders.created_at','mac_orders.user_id')->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;
        $gerencias_s_m =  DB::table('users')->where('role','user_mac')->select(DB::raw('count(DISTINCT(id)) as c'))->first()->c;

        $total_m = DB::table('mac_orders')->join('mac_order_mac_product','mac_orders.id','=','mac_order_mac_product.mac_order_id')
            ->join('mac_products','mac_products.id','=','mac_order_mac_product.mac_product_id')
            ->select(DB::raw('SUM(mac_products.price * mac_order_mac_product.quantity) as total'))
            ->first()->total; 

        $orders_c = $this->appendDateFilter(CorporationOrder::query(),'corporation_orders.created_at','corporation_orders.user_id')->select(DB::raw('count(*) as c'))->first()->c;
        $gerencias_c_c = $this->appendDateFilter(CorporationOrder::query(),'corporation_orders.created_at','corporation_orders.user_id')->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;
        $gerencias_s_c =  DB::table('users')->where('role','user_corporation')->select(DB::raw('count(DISTINCT(id)) as c'))->first()->c;

        $total_c = DB::table('corporation_orders')->join('corporation_order_corporation_product','corporation_orders.id','=','corporation_order_corporation_product.corp_order_id')
            ->join('corporation_products','corporation_products.id','=','corporation_order_corporation_product.corp_product_id')
            ->select(DB::raw('SUM(corporation_products.price * corporation_order_corporation_product.quantity) as total'))
            ->first()->total; 
                        
        switch ($paper_type) {
            case 'orders':
                $orders = $orders_o;
                $gerencias_s = $gerencias_s_o;
                $gerencias_c = $gerencias_c_o;
                $total = $total_o;
                break;

            case 'furniture_orders':
                $orders = $orders_f;
                $gerencias_s = $gerencias_s_f;
                $gerencias_c = $gerencias_c_f;
                $total = $total_f;
                break;

            case 'mac_orders':
                $orders = $orders_m;
                $gerencias_s = $gerencias_s_m;
                $gerencias_c = $gerencias_c_m;
                $total = $total_m;
                break;

            case 'corporation_orders':
                $orders = $orders_c;
                $gerencias_s = $gerencias_s_c;
                $gerencias_c = $gerencias_c_c;
                $total = $total_c;                
                break;
            
            default:
                break;
        }

        
        //people gerencias pedido
        
        //total de gerencias
        
        
        $gerencias = $gerencias_s - $gerencias_c;
        return Response::json([
            'orders'    => $orders,
            'people'    => $gerencias,
            'people_orders' => $gerencias_c,
            'total'     => $total
        ]);
    }


    public function products() {

        $paper_type =Input::get('paper_type','orders');
        
        switch ($paper_type) {
            case 'orders':
                $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'),'categories.name as category')
                    ->from('order_product')
                    ->join('products', 'order_product.product_id', '=', 'products.id')
                    ->join('orders', 'order_product.order_id', '=', 'orders.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('products.id');
                $this->appendDateFilter($query, 'orders.created_at','orders.user_id');

                break;

            case 'furniture_orders':
                $query = Furniture::select('furnitures.*', DB::raw('SUM(furniture_orders.quantity) AS q'),'furniture_categories.name as category')
                    ->from('furniture_orders')
                    ->join('furnitures', 'furniture_orders.furniture_id', '=', 'furnitures.id')
                    ->join('furniture_orders', 'furniture_furniture_order.furniture_order_id', '=', 'furniture_orders.id')
                    ->join('furniture_categories', 'furnitures.category_id', '=', 'furniture_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('furnitures.id');
                $this->appendDateFilter($query, 'furniture_orders.created_at','furniture_orders.user_id');                
                break;

            case 'mac_orders':
                $query = MacProduct::select('mac_products.*', DB::raw('SUM(mac_order_mac_product.quantity) AS q'),'mac_categories.name as category')
                    ->from('mac_order_mac_product')
                    ->join('mac_products', 'mac_order_mac_product.mac_product_id', '=', 'mac_products.id')
                    ->join('mac_orders', 'mac_order_mac_product.mac_order_id', '=', 'mac_orders.id')
                    ->join('mac_categories', 'mac_products.category_id', '=', 'mac_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('mac_products.id');
                $this->appendDateFilter($query, 'mac_orders.created_at','mac_orders.user_id');                
                break;

            case 'corporation_orders':
                $query = CorporationProduct::select('corporation_products.*', DB::raw('SUM(corporation_order_corporation_product.quantity) AS q'),'categories.name as category')
                    ->from('corporation_order_corporation_product')
                    ->join('corporation_products', 'corporation_order_corporation_product.corp_product_id', '=', 'corporation_products.id')
                    ->join('corporation_orders', 'corporation_order_corporation_product.order_id', '=', 'corporation_orders.id')
                    ->join('corporation_categories', 'corporation_products.category_id', '=', 'corporation_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('corporation_products.id');
                $this->appendDateFilter($query, 'corporation_orders.created_at','corporation_orders.user_id');                
                break;
            default:
                break;
        }

        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }

        $query->limit(15);
        return Response::json($query->get());   
    }

    public function categories() {
        $paper_type = Input::get('paper-type','orders');
        Log::debug('--------------------');
        Log::debug($paper_type);
        
        switch ($paper_type) {
            case 'orders':
                $query = Category::select('categories.*', DB::raw('SUM(order_product.quantity) AS q'))
                    ->from('order_product')
                    ->join('products', 'order_product.product_id', '=', 'products.id')
                    ->join('orders', 'order_product.order_id', '=', 'orders.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('categories.id');
                $this->appendDateFilter($query, 'orders.created_at','orders.user_id');            
                break;
            case 'furniture_orders':
                $query = FurnitureCategory::select('furniture_categories.*', DB::raw('SUM(furniture_furniture_order.quantity) AS q'))
                    ->from('furniture_furniture_order')
                    ->join('furnitures', 'furniture_furniture_order.product_id', '=', 'furnitures.id')
                    ->join('furniture_orders', 'furniture_furniture_order.order_id', '=', 'furnitures.id')
                    ->join('furniture_categories', 'furnitures.category_id', '=', 'furniture_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('furniture_categories.id');
                $this->appendDateFilter($query, 'furniture_orders.created_at','furniture_orders.user_id');            
                break;
            case 'mac_orders':
                $query = MacCategory::select('mac_categories.*', DB::raw('SUM(mac_order_mac_product.quantity) AS q'))
                    ->from('mac_order_mac_product')
                    ->join('mac_products','mac_order_mac_product.product_id', '=', 'mac_products.id')
                    ->join('mac_orders', 'mac_order_mac_product.order_id', '=', 'mac_orders.id')
                    ->join('mac_categories', 'mac_products.mac_category_id', '=', 'mac_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('mac_categories.id');
                $this->appendDateFilter($query, 'mac_orders.created_at','mac_orders.user_id');      
                break;
            case 'corporation_orders':
                $query = CorporationCategory::select('corporation_categories.*', DB::raw('SUM(corp_order_corp_product.quantity) AS q'))
                    ->from('corp_order_corp_product')
                    ->join('corporation_products', 'corp_order_corp_product.product_id', '=', 'corporation_products.id')
                    ->join('corporation_orders', 'corp_order_corp_product.order_id', '=', 'corporation_orders.id')
                    ->join('corporation_categories', 'corporation_products.category_id', '=', 'corporation_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('corporation_categories.id');
                $this->appendDateFilter($query, 'corporation_orders.created_at','corporation_orders.user_id');
                break;
            default:
                break;
        }

        return Response::json($query->get());
    }


    public function annual() {
        $query = $this->appendDateFilter(Order::where('orders.status', '<>', 'cart'),'orders.created_at','orders.user_id');
        $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(orders.created_at) as month'), DB::raw('YEAR(orders.created_at) as year'))
            ->orderBy('year')->orderBy('month')
            ->groupBy('year')->groupBy('month');
        return Response::json($query->get());
    }

    public function ordersByPeriod() {
        $query = $this->appendDateFilter(Order::with('user'),'orders.created_at','orders.user_id')->where('orders.status', '<>', 'cart');
        $month = Input::get('month',\Carbon\Carbon::today()->month);
        $year = Input::get('year', \Carbon\Carbon::today()->year);
        $carbon = \Carbon\Carbon::createFromDate($year, $month, 1);
        $query->where('orders.created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('orders.created_at', '<=', $carbon->endOfMonth());
        
        return Response::json([
            'query' => $query->get(),
        ]);

    }

    public function ordersByCategory() {
        $query = $this->appendDateFilter(Order::with('user'), 'orders.created_at','orders.user_id')->where('orders.status', '<>', 'cart');
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
        $this->appendDateFilter($query, 'orders.created_at','orders.user_id');
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
        $this->appendDateFilter($query, 'orders.created_at','orders.user_id');
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
            ->join('orders', 'order_product.order_id', '=', 'orders.id');
            
            $this->appendDateFilter($query, 'orders.created_at','orders.user_id');
            
            $query->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q','desc')
            ->groupBy('orders.id');

        

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
            ->join('orders', 'order_product.order_id', '=', 'orders.id');

            $this->appendDateFilter($query, 'orders.created_at','orders.user_id');
            
            $query->join('regions','users.region_id','=','regions.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('q')
            ->groupBy('orders.id');

        

        if(Input::has('category')) {
            $query->where('categories.id', Input::get('category'));
        }

        $query->limit(10);
        return Response::json($query->get());            
    }

    public function annualMonth() {
        $query = $this->appendDateFilter(Order::where('orders.status', '<>', 'cart'),'orders.created_at','orders.user_id')
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