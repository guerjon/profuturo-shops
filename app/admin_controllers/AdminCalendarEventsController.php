<?

use \Carbon\Carbon;

class AdminCalendarEventsController extends AdminBaseController{

  public function index()
  {
    return View::make('admin::calendar_events.index')->withMonth($this->getMonth());
  }
 
  public function show($date)
  {
      $date = Carbon::parse($date);
      $events = $this->getEvents($date->month, $date->year, $date->day);
      return View::make('admin::calendar_events.day_index')->withEvents($events->get())->withDate($date->format('d/M/Y'));
  }

  private function getEvents($month = NULL, $year = NULL, $day = NULL)
    {
      // switch(Auth::user()->role){
      //   case 'admin':
      //     $events = ClientEvent::select('*');
      //     break;
      //   case 'manager':
      //     $execs = Auth::user()->executives()->lists('id');
      //     array_push($execs, Auth::id());
      //     $events = ClientEvent::whereIn('user_id', $execs);
      //     break;
      //   case 'executive':
      //     $events = Auth::user()->events();
      //     break;
      // }
      //
      // $events = $events->with('client');
      //

      $events = GeneralRequest::orderBy('project_date');
      if($day){
        $events->where(DB::raw('DATE(project_date)'), "{$year}-{$month}-{$day}");
      }elseif($month and $year){
        $events->where(DB::Raw('MONTH(project_date)'), $month)->where(DB::Raw('YEAR(project_date)'), $year);
      }

      return $events;
    }

  private function getMonth()
  {
    $now = Carbon::now();
    $month = Input::get('month', $now->month);
    $year = Input::get('year', $now->year);
    $date = Carbon::createFromDate($year, $month,1);

    switch(Input::get('mod', NULL)){
      case 'next':
      $date->addMonth();
      break;
      case 'prev':
      $date->subMonth();
      break;
    }
    $month = $date->month;
    $year = $date->year;


    $events = $this->getEvents($month, $year)->get();
    // echo $date->formatLocalized('%A %d %B %Y');
    // echo "\n";

    $offset = $date->dayOfWeek;
    $days = $date->daysInMonth;

    $monthResult = [
      'monthNum' => str_pad($month, 2, '0', STR_PAD_LEFT),
      'yearNum' => $year,
      'monthName' => $date->formatLocalized('%B %Y')
    ];


    $prevs = $date->subMonth()->daysInMonth;

    for($i = 0; $i < $offset; $i++){
      $monthResult[$i] = ['number' => $prevs - $offset + $i + 1,
      'enabled' => false];
    }


    for($i=$offset; $i<$days+$offset; $i++){
      $monthResult[$i] = [
      'number' => str_pad($i-$offset + 1, 2, '0', STR_PAD_LEFT),
      'enabled' => true,
      'events' => [],
      'liabilities' => [],
      ];
    }


    $i=1;

    while(!isset($monthResult[41])) {
      $monthResult[] = [
      'number' => str_pad($i++, 2, '0', STR_PAD_LEFT),
      'enabled' => false,
      ];
    }

    foreach($events as $event){
      $day = $event->project_date->day;
      $i  = $day + $offset -1;
      $monthResult[$i]['events'][] = $event;
    }

    return $monthResult;
  }
}
