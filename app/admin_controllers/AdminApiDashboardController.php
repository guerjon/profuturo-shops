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
            $builder->whereIn('users.divisional_id',Input::get('divisional_id'));
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

        Log::debug('query');
        Log::debug($orders_f);

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
                    ->join('furniture_categories', 'furnitures.furniture_category_id', '=', 'furniture_categories.id')
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
                $query = FurnitureOrder::select('furniture_categories.*', DB::raw('SUM(furniture_furniture_order.quantity) AS q'))
                            ->join('furniture_furniture_order','furniture_furniture_order.furniture_order_id','=','furniture_orders.id')
                            ->join('furnitures', 'furniture_furniture_order.furniture_id', '=', 'furnitures.id')
                            ->join('furniture_categories', 'furnitures.furniture_category_id', '=', 'furniture_categories.id')
                            ->orderBy('q', 'desc')
                            ->groupBy('furniture_categories.id');        
                $this->appendDateFilter($query, 'furniture_orders.created_at','furniture_orders.user_id');            

                break;
            case 'mac_orders':
                $query = MacCategory::select('mac_categories.*', DB::raw('SUM(mac_order_mac_product.quantity) AS q'))
                    ->from('mac_order_mac_product')
                    ->join('mac_products','mac_order_mac_product.mac_product_id', '=', 'mac_products.id')
                    ->join('mac_orders', 'mac_order_mac_product.mac_order_id', '=', 'mac_orders.id')
                    ->join('mac_categories', 'mac_products.mac_category_id', '=', 'mac_categories.id')
                    ->orderBy('q', 'desc')
                    ->groupBy('mac_categories.id');
                $this->appendDateFilter($query, 'mac_orders.created_at','mac_orders.user_id');      
                break;
            case 'corporation_orders':
                $query = CorporationCategory::select('corporation_categories.*', DB::raw('SUM(corporation_order_corporation_product.quantity) AS q'))
                    ->from('corporation_order_corporation_product')
                    ->join('corporation_products', 'corporation_order_corporation_product.corp_product_id', '=', 'corporation_products.id')
                    ->join('corporation_orders', 'corporation_order_corporation_product.corp_order_id', '=', 'corporation_orders.id')
                    ->join('corporation_categories', 'corporation_products.corporation_category_id', '=', 'corporation_categories.id')
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
        $paper_type = Input::get('paper-type','orders');

        switch ($paper_type){
            case 'orders':
                $query = $this->appendDateFilter(Order::query(),'orders.created_at','orders.user_id');
                $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(orders.created_at) as month'), DB::raw('YEAR(orders.created_at) as year'))
                    ->orderBy('year')->orderBy('month')
                    ->groupBy('year')->groupBy('month');
                
                break;
            case 'furniture_orders':
                $query = $this->appendDateFilter(FurnitureOrder::query(),'furniture_orders.created_at','furniture_orders.user_id');
                $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(furniture_orders.created_at) as month'), DB::raw('YEAR(furniture_orders.created_at) as year'))
                    ->orderBy('year')->orderBy('month')
                    ->groupBy('year')->groupBy('month');

                break;
            case 'mac_orders':
                $query = $this->appendDateFilter(MacOrder::query(),'mac_orders.created_at','mac_orders.user_id');
                $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(mac_orders.created_at) as month'),DB::raw('YEAR(mac_orders.created_at) as year'))
                    ->orderBy('year')->orderBy('month')
                    ->groupBy('year')->groupBy('month');
                break;
            case 'corporation_orders':
                $query = $this->appendDateFilter(CorporationOrder::query(),'corporation_orders.created_at','corporation_orders.user_id');
                $query->select(DB::raw('count(*) as c'), DB::raw('MONTH(corporation_orders.created_at) as month'), DB::raw('YEAR(corporation_orders.created_at) as year'))
                    ->orderBy('year')->orderBy('month')
                    ->groupBy('year')->groupBy('month');
                break;
            default:
                break;
        }


        return Response::json($query->get());
    }


    public function annualMonth() {
        $paper_type = Input::get('paper-type','orders');

            switch ($paper_type) {
                case 'orders':
                    $query = $this->appendDateFilter(Order::query(),'orders.created_at','orders.user_id')
                        ->join('order_product','orders.id','=','order_product.order_id')
                        ->join('products', 'order_product.product_id', '=', 'products.id')
                        ->select(  
                            DB::raw('SUM(products.price * order_product.quantity) as c'),
                            DB::raw('MONTH(orders.created_at) as month'),
                            DB::raw('YEAR(orders.created_at) as year'))
                        ->orderBy('year')->orderBy('month')
                        ->groupBy('year')->groupBy('month');
                    break;
                case 'furniture_orders':

                    $query = $this->appendDateFilter(FurnitureOrder::query(),'furniture_orders.created_at','furniture_orders.user_id')
                        ->join('furniture_furniture_order','furniture_orders.id','=','furniture_furniture_order.furniture_order_id')
                        ->join('furnitures', 'furniture_furniture_order.furniture_id', '=', 'furnitures.id')
                        ->select(  
                            DB::raw('SUM(furnitures.unitary * furniture_furniture_order.quantity) as c'),
                            DB::raw('MONTH(furniture_orders.created_at) as month'),
                            DB::raw('YEAR(furniture_orders.created_at) as year'))
                        ->orderBy('year')->orderBy('month')
                        ->groupBy('year')->groupBy('month');
                    break;
                case 'mac_orders':
                    $query = $this->appendDateFilter(MacOrder::query(),'mac_orders.created_at','mac_orders.user_id')
                        ->join('mac_order_mac_product','mac_orders.id','=','mac_order_mac_product.mac_order_id')
                        ->join('mac_products', 'mac_order_mac_product.mac_product_id', '=', 'mac_products.id')
                        ->select(  
                            DB::raw('SUM(mac_products.price * mac_order_mac_product.quantity) as c'),
                            DB::raw('MONTH(mac_orders.created_at) as month'),
                            DB::raw('YEAR(mac_orders.created_at) as year'))
                        ->orderBy('year')->orderBy('month')
                        ->groupBy('year')->groupBy('month');                    
                    break;
                case 'corporation_orders':
                    $query = $this->appendDateFilter(CorporationOrder::query(),'corporation_orders.created_at','corporation_orders.user_id')
                        ->join('corporation_order_corporation_product','corporation_orders.id','=','corporation_order_corporation_product.corp_order_id')
                        ->join('corporation_products', 'corporation_order_corporation_product.corp_product_id', '=', 'corporation_products.id')
                        ->select(  
                            DB::raw('SUM(corporation_products.price * corporation_order_corporation_product.quantity) as c'),
                            DB::raw('MONTH(corporation_orders.created_at) as month'),
                            DB::raw('YEAR(corporation_orders.created_at) as year'))
                        ->orderBy('year')->orderBy('month')
                        ->groupBy('year')->groupBy('month');                    
                    break;
                default:
                    break;
            }

        return Response::json($query->get());
    }


    public function ordersByPeriod() {
        $paper_type = Input::get('paper-type','orders');
        $month = Input::get('month',\Carbon\Carbon::today()->month);
        $year = Input::get('year', \Carbon\Carbon::today()->year);
        
        $carbon = \Carbon\Carbon::createFromDate($year, $month, 1);

            switch ($paper_type) {
                case 'orders':
                $query = $this->appendDateFilter(Order::with('user'),'orders.created_at','orders.user_id');
                $query->where('orders.created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('orders.created_at', '<=', $carbon->endOfMonth());
                    break;
                case 'furniture_orders':
                $query = $this->appendDateFilter(FurnitureOrder::with('user'),'furniture_orders.created_at','furniture_orders.user_id');
                $query->where('furniture_orders.created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('furniture_orders.created_at', '<=', $carbon->endOfMonth());
                    break;
                case 'mac_orders':
                $query = $this->appendDateFilter(MacOrder::with('user'),'mac_orders.created_at','mac_orders.user_id');
                $query->where('mac_orders.created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('mac_orders.created_at', '<=', $carbon->endOfMonth());
                    break;
                case 'corporation_orders':
                $query = $this->appendDateFilter(CorporationOrder::with('user'),'corporation_orders.created_at','corporation_orders.user_id');
                $query->where('corporation_orders.created_at', '>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))->where('corporation_orders.created_at', '<=', $carbon->endOfMonth());
                    break;
                default:
                    # code...
                    break;
            }        
        return Response::json([
            'query' => $query->get(),
        ]);

    }

        /*
    *Regresa los productos mas solicitados
    */
    public function topProducts($category_id = null)
    {
        $query = $this->getTops($category_id);
        $query->orderBy('q', 'desc')->limit(10);
        return Response::json($query->get());   
    }

    public function topReverseProducts($category_id = null)
    {
        $query = $this->getTops($category_id);
        $query->orderBy('q')->limit(10);
        $query->limit(10);
        return Response::json($query->get());          
    }

    public function biggestAmount($category_id = null)
    {
        $query = $this->getAmounts($category_id);
        $query->orderBy('q','desc')->limit(10);
        return Response::json($query->get());             
    }

    public function smallestAmount($category_id = null)
    {
        $query = $this->getAmounts($category_id);
        $query->orderBy('q')->limit(10);

        return Response::json($query->get());            
    }

    private function getTops($category_id)
    {
        $paper_type = Input::get('paper-type','orders');

        switch ($paper_type) {
            case 'orders':
            $query = Product::select('products.*', DB::raw('SUM(order_product.quantity) AS q'),'categories.name as category' )
                ->from('order_product')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->groupBy('products.id');
                if($category_id != null) {
                    $query->where('products.category_id',$category_id);
                }
                break;
            case 'furniture_orders':
                $query = Furniture::select('furnitures.*', DB::raw('SUM(furniture_furniture_order.quantity) AS q'),'furniture_categories.name as category' )
                    ->from('furniture_furniture_order')
                    ->join('furnitures', 'furniture_furniture_order.furniture_id', '=', 'furnitures.id')
                    ->join('furniture_orders', 'furniture_furniture_order.furniture_order_id', '=', 'furniture_orders.id')
                    ->join('furniture_categories', 'furnitures.furniture_category_id', '=', 'furniture_categories.id')
                    ->groupBy('furnitures.id');
                    if($category_id != null) {
                        $query->where('furnitures.furniture_category_id',$category_id);
                    }
                break;
            case 'mac_orders':
                $query = MacProduct::select('mac_products.*', DB::raw('SUM(mac_order_mac_product.quantity) AS q'),'mac_categories.name as category' )
                    ->from('mac_order_mac_product')
                    ->join('mac_products', 'mac_order_mac_product.mac_product_id', '=', 'mac_products.id')
                    ->join('mac_orders', 'mac_order_mac_product.mac_order_id', '=', 'mac_orders.id')
                    ->join('mac_categories', 'mac_products.mac_category_id', '=', 'mac_categories.id')
                    ->groupBy('mac_products.id');
                    if($category_id != null) {
                        $query->where('mac_products.mac_category_id',$category_id);
                    }
                break;
            case 'corporation_orders':
                $query = CorporationProduct::select('corporation_products.*', DB::raw('SUM(corporation_order_corporation_product.quantity) AS q'),'corporation_categories.name as category' )
                    ->from('corporation_order_corporation_product')
                    ->join('corporation_products', 'corporation_order_corporation_product.corp_product_id', '=', 'corporation_products.id')
                    ->join('corporation_orders', 'corporation_order_corporation_product.corp_order_id', '=', 'corporation_orders.id')
                    ->join('corporation_categories', 'corporation_products.corporation_category_id', '=', 'corporation_categories.id')
                    ->groupBy('corporation_products.id');
                    if($category_id != null) {
                        $query->where('corporation_products.corporation_category_id',$category_id);
                    }                    
                break;
            default:
                # code...
                break;
        }
                
        $this->appendDateFilter($query,$paper_type.'.created_at',$paper_type.'.user_id');
        $category_type = $paper_type.'.id';

        return $query;
    }


    private function getAmounts($category_id)
    {
        $paper_type = Input::get('paper-type','orders');

        switch ($paper_type) {
            case 'orders':
                $query = Product::select('users.*','products.*',
                            DB::raw('SUM(products.price * order_product.quantity) AS q'),'regions.name as region_name')
                        ->from('order_product')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->join('categories', 'products.category_id', '=', 'categories.id');
                if($category_id != null) {
                    $query->where('products.category_id',$category_id);
                }
                break;
            case 'furniture_orders':
                $query = Furniture::select('users.*','furnitures.*',
                            DB::raw('SUM(furnitures.unitary * furniture_furniture_order.quantity) AS q'),'regions.name as region_name')
                        ->from('furniture_furniture_order')
                ->join('furnitures', 'furniture_furniture_order.furniture_id', '=', 'furnitures.id')
                ->join('furniture_orders', 'furniture_furniture_order.furniture_order_id', '=', 'furniture_orders.id')
                ->join('furniture_categories', 'furnitures.furniture_category_id', '=', 'furniture_categories.id');
                if($category_id != null) {
                    $query->where('furnitures.furniture_category_id',$category_id);
                }
                break;
            case 'mac_orders':
                $query = MacProduct::select('users.*','mac_products.*',
                            DB::raw('SUM(mac_products.price * mac_order_mac_product.quantity) AS q'),'regions.name as region_name')
                        ->from('mac_order_mac_product')
                ->join('mac_products', 'mac_order_mac_product.mac_product_id', '=', 'mac_products.id')
                ->join('mac_orders', 'mac_order_mac_product.mac_order_id', '=', 'mac_orders.id')
                ->join('mac_categories', 'mac_products.mac_category_id', '=', 'mac_categories.id');
                if($category_id != null) {
                    $query->where('mac_products.mac_category_id',$category_id);
                }
                break;
            case 'corporation_orders':
                $query = CorporationProduct::select('users.*','corporation_products.*',
                            DB::raw('SUM(corporation_products.price * corporation_order_corporation_product.quantity) AS q'),'regions.name as region_name')
                        ->from('corporation_order_corporation_product')
                ->join('corporation_products', 'corporation_order_corporation_product.corp_product_id', '=', 'corporation_products.id')
                ->join('corporation_orders', 'corporation_order_corporation_product.corp_order_id', '=', 'corporation_orders.id')
                ->join('corporation_categories', 'corporation_products.corporation_category_id', '=', 'corporation_categories.id');
                if($category_id != null) {
                    $query->where('corporation_products.corporation_category_id',$category_id);
                }
                break;
            default:
                break;
        }

        $query = $this->appendDateFilter($query,$paper_type.'.created_at',$paper_type.'.user_id');    
        $query->join('regions','users.region_id','=','regions.id')->groupBy($paper_type.'.id');
        

        return $query;
    }




}