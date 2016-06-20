<?php

/**
* 
*/
class AdminApiDashboardController extends AnotherClass
{
	
    public function getOverview(Request $request) {
        $orders = $this->appendDateFilter(Order::query())->select(DB::raw('count(*) as c'))->first()->c;
        $people = $this->appendDateFilter(Order::query())->select(DB::raw('count(DISTINCT(user_id)) as c'))->first()->c;
        return response()->json([
            'orders'    => $orders,
            'people'    => $people,
        ]);
    }

	public function appendDateFilter($builder, $date_field = 'created_at', Request $request = null) {
        if(!$request) {
            $request = request();
        }
        $default_from = Carbon::create(2015, 12, 1, 0, 0, 0);
        $from = $request->input('from', $default_from->max(Carbon::today()->startOfMonth()->subYear()));
        $to = $request->input('to', Carbon::today()->endOfMonth());
        return $builder->where($date_field, '>=', $from)->where($date_field, '<=', $to);
    }
}