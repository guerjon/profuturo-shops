<?php

use \Carbon\Carbon as Carbon;

class AdminDashboardController  extends AdminBaseController {

	
	public function stationery()
	{
		return View::make('admin::dashboard/stationery');
	}

    private function orders()
    {
        $query = Order::join('order_product','orders.id','=','order_product.order_id')
                    ->join('products','products.id','=','order_product.product_id')
                    ->join('users','users.id','=','orders.user_id')
                    ->join('categories','categories.id','=','products.category_id');

        return $query;
    }

    private function furnitureOrders()
    {
        $query = FurnitureOrder::join('furniture_furniture_order','furniture_furniture_order.furniture_order_id','=','furniture_orders.id')
                    ->join('furnitures','furnitures.id','=','furniture_furniture_order.furniture_id')
                    ->join('users','users.id','=','furniture_orders.user_id')
                    ->join('furniture_categories','furniture_categories.id','=','furnitures.furniture_category_id');
        return $query;
    }

    private function macOrders()
    {
        $query = MacOrder::join('mac_order_mac_product','mac_orders.id','=','mac_order_mac_product.mac_order_id')
                    ->join('mac_products','mac_products.id','=','mac_order_mac_product.mac_product_id')
                    ->join('users','users.id','=','mac_orders.user_id')
                    ->join('mac_categories','mac_categories.id','=','mac_products.mac_category_id');
        return $query;
    }

    private function corporationOrders()
    {
        $query = CorporationOrder::join('corporation_order_corporation_product','corporation_orders.id','=','corporation_order_corporation_product.corp_order_id')
            ->join('corporation_products','corporation_products.id','=','corporation_order_corporation_product.corp_product_id')
            ->join('users','users.id','=','corporation_orders.user_id')
            ->join('corporation_categories','corporation_categories.id','=','corporation_products.corporation_category_id');
        return $query;
    }

    public function select($query,$products,$order_product)
    {
        $price = Input::get('paper-type') == 'furniture_orders' ? 'unitary' : 'price';

        $top_products = $query['top_products']->select($products.'.*', DB::raw('SUM('.$order_product.'.quantity) AS q'));
        $top_reverse_products = $query['top_reverse_products']->select($products.'.*', DB::raw('SUM('.$order_product.'.quantity) AS q'));
        $biggest_amounts = $query['biggest_amounts']->select(
            'users.*',
            $products.'.*',
            DB::raw('SUM('.$products.'.'.$price.' * '.$order_product.'.quantity) AS q'),
            'regions.name as region_name');
        $smallest_amounts = $query['smallest_amounts']->select('users.*',$products.'.*',
                                 DB::raw('SUM('.$products.'.'.$price.' * '.$order_product.'.quantity) AS q'),
                                 'regions.name as region_name' 
                                 );

        $all_orders = $query['all_orders']->select('*',
            DB::raw('SUM('.$products.'.'.$price.' * '.$order_product.'.quantity) AS m'),
            DB::raw('count(DISTINCT(user_id)) as q'));
    
        return ['top_products' => $top_products,
                'top_reverse_products' => $top_reverse_products,
                'biggest_amounts' => $biggest_amounts,
                'smallest_amounts' => $smallest_amounts,
                'all_orders' => $all_orders];
    }

    public function overviewByMonth($index,$month,$year)
    {
        $carbon = Carbon::createFromDate($year, $month, 1); 
        
        $query = $this->query($month,$year);

        return View::make('admin::dashboard/month')
        	->withTopProducts($query['top_products']->get())
        	->withTopReverseProducts($query['top_reverse_products']->get())
        	->withBiggestAmounts($query['biggest_amounts']->get())
        	->withSmallestAmounts($query['smallest_amounts']->get())
            ->withAllOrders($query['all_orders']->get())
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
        $paper_type = Input::get('paper-type','orders');
        
        switch ($paper_type) {
            case 'orders':
                
                $products = $this->orders()
                    ->groupBy('order_product.product_id')
                    ->limit(10);

                $amounts = $this->orders()
                    ->join('regions','users.region_id','=','regions.id')
                    ->groupBy('orders.id')
                    ->limit(10);

                break;
            case 'furniture_orders':
                $products = $this->furnitureOrders()
                    ->groupBy('furniture_furniture_order.furniture_id')
                    ->limit(10);

                $amounts = $this->furnitureOrders()
                    ->join('regions','users.region_id','=','regions.id')
                    ->groupBy('furniture_orders.id')
                    ->limit(10);
             
                break;
            case 'mac_orders':
                $products = $this->macOrders()
                    ->groupBy('mac_order_mac_product.mac_product_id')
                    ->limit(10);

                $amounts = $this->macOrders()
                    ->join('regions','users.region_id','=','regions.id')
                    ->groupBy('mac_orders.id')
                    ->limit(10);
                
                break;
            case 'corporation_orders':
                $products = $this->corporationOrders()
                    ->groupBy('corporation_order_corporation_product.corp_product_id')
                    ->limit(10);

                $amounts = $this->corporationOrders()
                    ->join('regions','users.region_id','=','regions.id')
                    ->groupBy('corporation_orders.id')
                    ->limit(10);
            
                break;
            default:
                break;
        }

        $biggest_amounts = clone $amounts;
        $smallest_amounts = clone $amounts;
        $top_reverse_products = clone $products;

        $query = ['top_products' => $this->filters($products->orderBy('q','desc'),$month,$year),
                'top_reverse_products' => $this->filters($top_reverse_products->orderBy('q'),$month,$year),
                'biggest_amounts' => $this->filters($biggest_amounts->orderBy('q','desc'),$month,$year),
                'smallest_amounts' => $this->filters($smallest_amounts->orderBy('q'),$month,$year),
                'all_orders' => $this->filters($amounts->orderBy('users.ccosto'),$month,$year)];    

        switch ($paper_type) {
            case 'orders':
                $this->select($query,'products','order_product');
                break;
            case 'furniture_orders':
                $this->select($query,'furnitures','furniture_furniture_order');
                break;
            case 'mac_orders':
                $this->select($query,'mac_products','mac_order_mac_product');
                break;
            case 'corporation_orders':
                $this->select($query,'corporation_products','corporation_order_corporation_product');
                break;
            default:
                break;
        }

        return $query;
    }

    private function filters($builder,$month,$year)
    {   

        $carbon = Carbon::createFromDate($year, $month, 1);
        $paper_type = Input::get('paper-type','orders');
        
        if(Input::has('divisional_id')){
            $builder->whereIn('users.divisional_id',Input::get('divisional_id'));
        }

        if(Input::has('region_id')){
            $builder->whereIn('region_id',Input::get('region_id'));
        }

        if(Input::has('gerencia')){
            $builder->whereIn('gerencia',Input::get('gerencia'));
        }

        $builder->where($paper_type.'.created_at','>=', $carbon->startOfMonth()->format('Y-m-d H:i:s'))
            ->where($paper_type.'.created_at', '<=', $carbon->endOfMonth());

        return $builder;
    }

    public function productsByMonth()
    {
      return View::make('admin::dashboard/products_by_month');
    }

    public function showManagements()
    {
        if(Input::has('from')){
            Session::put('input',Input::all());
            $input = Session::get('input');
        }else{
            $input = Session::get('input');
        }
            
        $paper_type = $input['paper-type'];
        $to = \Carbon\Carbon::createFromFormat('Y-m-d',$input['to'])->addDay()->format('Y-m-d');

        switch ($paper_type) {
            case 'orders':
                $helper = ['users.id','order_product','price','products'];
                $orders = $this->orders();
                break;
            case 'furniture_orders':
                $helper = ['users.id','furniture_furniture_order','unitary','furnitures'];
                $orders = $this->furnitureOrders();
                break;
            case 'mac_orders':
                $helper = ['users.id','mac_order_mac_product','price','mac_products'];
                $orders = $this->macOrders();
                break;

            case 'corporation_orders':
                $helper = ['users.id','corporation_order_corporation_product','price','corporation_products'];
                $orders = $this->corporationOrders();
                break;
            
            default:
                break;
        }

        $orders = $this->filtersToShow($input,$orders);

        $orders->where($paper_type.'.created_at','>=',$input['from'])
                ->where($paper_type.'.created_at','<=',$to);


        $orders->orderBy($helper[0])->groupBy($helper[0])->select('users.*');

        return View::make('admin::dashboard/management_orders')
            ->withUsers($orders->paginate(20));
        
    }

    public function showOrders()
    {
        //Solo from por que queremos saber si es la primera vez que entra o es por paginación
        if(Input::has('from')){
            Session::put('input',Input::all());
            $input = Session::get('input');
        }else{
            $input = Session::get('input');
        }
            
        $paper_type = $input['paper-type'];
        $to = \Carbon\Carbon::createFromFormat('Y-m-d',$input['to'])->addDay()->format('Y-m-d');

        switch ($paper_type) {
            case 'orders':
                $helper = ['orders.id','order_product','price','products'];
                $orders = $this->orders();
                break;
            case 'furniture_orders':
                $helper = ['furniture_orders.id','furniture_furniture_order','unitary','furnitures'];
                $orders = $this->furnitureOrders();
                break;
            case 'mac_orders':
                $helper = ['mac_orders.id','mac_order_mac_product','price','mac_products'];
                $orders = $this->macOrders();
                break;

            case 'corporation_orders':
                $helper = ['corporation_orders.id','corporation_order_corporation_product','price','corporation_products'];
                $orders = $this->corporationOrders();
                break;
            
            default:
                break;
        }

        $orders = $this->filtersToShow($input,$orders);

        $orders->where($paper_type.'.created_at','>=',$input['from'])
                ->where($paper_type.'.created_at','<=',$to);

        $orders->orderBy($helper[0])->groupBy($helper[0])
            ->select('users.ccosto','gerencia',$helper[0],
                        DB::raw('SUM('.$helper[1].'.quantity) as quantity'),
                        DB::raw($helper[1].'.quantity * '.$helper[3].'.'.$helper[2].' as total')
            );

        return View::make('admin::dashboard/show_orders')
            ->withOrders($orders->paginate(20));
    }

    public function showManagementsWithout()
    {
        if(Input::has('from')){
            Session::put('input',Input::all());
            $input = Session::get('input');
        }else{
            $input = Session::get('input');
        }

        $to = \Carbon\Carbon::createFromFormat('Y-m-d',$input['to'])->addDay()->format('Y-m-d');
        $paper_type = Input::get('paper-type','orders');

        switch ($paper_type) {
            case 'orders':
                $helper = ['orders.id','order_product','price','products','orders','orders.created_at'];
                break;
            case 'furniture_orders':
                $helper = ['furniture_orders.id','furniture_furniture_order','unitary','furnitures','furnitureOrders','furniture_orders.created_at'];
                break;
            case 'mac_orders':
                $helper = ['mac_orders.id','mac_order_mac_product','price','mac_products','macOrders','mac_orders.created_at'];
                break;

            case 'corporation_orders':
                $helper = ['corporation_orders.id','corporation_order_corporation_product','price','corporation_products','corporationOrders','corporation_orders.created_at'];
                break;
            
            default:
                break;
        }
        $query = User::doesntHave($helper[4])->orderBy('ccosto')->select('users.*');

        $query = $this->filtersToShow($input,$query);
      
        $query->whereDoesntHave($helper[4],function($q) use($helper,$input){
          $q->where($helper[5],'>=',$input['to']); 
        });
      
        $query->whereDoesntHave($helper[4],function($q) use($helper,$to,$input){
          $q->where($helper[5],'<=',$input['from']); 
        });
          

        return View::make('admin::dashboard/show_managements_without')
            ->withUsers($query->paginate(20));

    }

    private function filtersToShow($input,$orders){
        
        if(array_key_exists('divisional_id',$input)){
            $orders->whereIn('users.divisional_id',$input['divisional_id']);
        }

        if(array_key_exists('region_id', $input)){
            $orders->whereIn('region_id',$input['region_id']);
        }

        if(array_key_exists('gerencia', $input)){
            $orders->whereIn('gerencia',$input['gerencia']);
        }

        return $orders;
    }

}
