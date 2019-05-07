<?php

namespace App\Http\Controllers;
use App;
use AppHelper;
use App\Models\Client;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use PDF;


class FinancialReportController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:report.financial_report.view', ['only' => ['index']]);
		$this->title = "Profit & Loss Account";
		$this->title2 = "Reporting";
		view()->share('title', $this->title);
		view()->share('title2', $this->title2);
	}
	public function index(Request $request) {
		view()->share('title', $this->title);
		view()->share('title2', $this->title2);
		view()->share('count', 1);
		$reportData = $this->get_index();
		view()->share('RevenueReport', $reportData);
		return view('financialreport.index');
	}
	public function get_index() {
		if (request()->all()) {
			$request['from_date'] = str_replace('__/__/____', '', request()->get('from_date'));
			$request['to_date'] = str_replace('__/__/____', '', request()->get('to_date'));
		}
		$input = request()->all();
		$input = array_except($input, array('_token', 'search'));
		$input = AppHelper::getTrimmedData($input);
		if (!isset($input['group_by']) || (isset($input['group_by']) && $input['group_by'] == 'year')) {
			$sub_pay = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('YEAR(clients.created_at) as date'), DB::raw('SUM(programs.program_fee) as total_sub_fee'))->join("programs", function ($join) {
				$join->on('programs.id', '=', 'clients.program_id');
				$join->on("programs.deleted", "=", DB::raw('"0"'));
			});
			$total_coach_session = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('YEAR(coach_schedules_booked.created_at)as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_module_completion = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('YEAR(user_module_progresses.completed_at)as date'), DB::raw('count(DISTINCT user_module_progresses.id) * (coach_module_rates.rate) as total_module_completed'))->join("user_module_progresses", function ($join) {
				$join->on('user_module_progresses.user_id', '=', 'clients.user_id');
				$join->on("user_module_progresses.completed_at", "!=", DB::raw('"null"'));
				$join->on("user_module_progresses.deleted", "=", DB::raw('"0"'));
			})->join("coach_module_rates", function ($join) {
				$join->on('user_module_progresses.module_id', '=', 'coach_module_rates.module_id');
				$join->on("coach_module_rates.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_coaching_sessions = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('YEAR(coach_schedules_booked.created_at)as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_pay_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.session_status", "=", DB::raw('"completed"'));
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
		}
		if (isset($input['group_by']) && $input['group_by'] == 'month') {
			$sub_pay = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('Month(clients.created_at) as date'), DB::raw('SUM(programs.program_fee) as total_sub_fee'))->join("programs", function ($join) {
				$join->on('programs.id', '=', 'clients.program_id');
				$join->on("programs.deleted", "=", DB::raw('"0"'));
			});
			$total_coach_session = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('Month(coach_schedules_booked.created_at)as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_module_completion = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('Month(user_module_progresses.completed_at)as date'), DB::raw('count(DISTINCT user_module_progresses.id) * (coach_module_rates.rate) as total_module_completed'))->join("user_module_progresses", function ($join) {
				$join->on('user_module_progresses.user_id', '=', 'clients.user_id');
				$join->on("user_module_progresses.completed_at", "!=", DB::raw('"null"'));
				$join->on("user_module_progresses.deleted", "=", DB::raw('"0"'));
			})->join("coach_module_rates", function ($join) {
				$join->on('user_module_progresses.module_id', '=', 'coach_module_rates.module_id');
				$join->on("coach_module_rates.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_coaching_sessions = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('Month(coach_schedules_booked.created_at)as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_pay_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.session_status", "=", DB::raw('"completed"'));
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
		}
		if (isset($input['group_by']) && $input['group_by'] == 'day') {
			$sub_pay = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('DATE_FORMAT(clients.created_at,"%d-%m-%Y") as date'), DB::raw('SUM(programs.program_fee) as total_sub_fee'))->join("programs", function ($join) {
				$join->on('programs.id', '=', 'clients.program_id');
				$join->on("programs.deleted", "=", DB::raw('"0"'));
			});
			$total_coach_session = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('DATE_FORMAT(coach_schedules_booked.created_at ,"%d-%m-%Y")as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_module_completion = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('DATE_FORMAT(user_module_progresses.completed_at,"%d-%m-%Y")as date'), DB::raw('count(DISTINCT user_module_progresses.id) * (coach_module_rates.rate) as total_module_completed'))->join("user_module_progresses", function ($join) {
				$join->on('user_module_progresses.user_id', '=', 'clients.user_id');
				$join->on("user_module_progresses.deleted", "=", DB::raw('"0"'));
				$join->on("user_module_progresses.completed_at", "!=", DB::raw('"null"'));
			})->join("coach_module_rates", function ($join) {
				$join->on('user_module_progresses.module_id', '=', 'coach_module_rates.module_id');
				$join->on("coach_module_rates.deleted", "=", DB::raw('"0"'));
			});
			$pay_total_coaching_sessions = Client::where('LPAP_initial_fee', 'paid')->select(DB::raw('DATE_FORMAT(coach_schedules_booked.created_at,"%d-%m-%Y")as date'), DB::raw('count(DISTINCT coach_schedules_booked.id) * (coaches.one_hour_session) as total_pay_scedule'))->join("coach_schedules_booked", function ($join) {
				$join->on('coach_schedules_booked.booked_user_id', '=', 'clients.user_id');
				$join->on("coach_schedules_booked.session_status", "=", DB::raw('"completed"'));
				$join->on("coach_schedules_booked.deleted", "=", DB::raw('"0"'));
			})->join("coaches", function ($join) {
				$join->on('coaches.id', '=', 'clients.coach_id');
				$join->on("coaches.deleted", "=", DB::raw('"0"'));
			});
		}
		/* Date Filtter */
		if (!empty($input['from_date'])) {
			$from_date = Carbon::createFromFormat('m/d/Y', $input['from_date'])->format('Y/m/d');
			$sub_pay->where(DB::raw("DATE_FORMAT(clients.created_at,'%Y/%m/%d')"), '>=', $from_date);
			$total_coach_session->where(DB::raw("DATE_FORMAT(coach_schedules_booked.created_at,'%Y/%m/%d')"), '>=', $from_date);
			$pay_total_module_completion->where(DB::raw("DATE_FORMAT(user_module_progresses.completed_at,'%Y/%m/%d')"), '>=', $from_date);
			$pay_total_coaching_sessions->where(DB::raw("DATE_FORMAT(coach_schedules_booked.created_at,'%Y/%m/%d')"), '>=', $from_date);
		}
		if (!empty($input['to_date'])) {
			$to_date = Carbon::createFromFormat('m/d/Y', $input['to_date'])->format('Y/m/d');
			$sub_pay->where(DB::raw("DATE_FORMAT(clients.created_at,'%Y/%m/%d')"), '<=', $to_date);
			$total_coach_session->where(DB::raw("DATE_FORMAT(coach_schedules_booked.created_at,'%Y/%m/%d')"), '<=', $to_date);
			$pay_total_module_completion->where(DB::raw("DATE_FORMAT(user_module_progresses.completed_at,'%Y/%m/%d')"), '<=', $to_date);
			$pay_total_coaching_sessions->where(DB::raw("DATE_FORMAT(coach_schedules_booked.created_at,'%Y/%m/%d')"), '<=', $to_date);
		}
		/* Group By Filtter */
		if (!isset($input['group_by']) || (isset($input['group_by']) && $input['group_by'] == 'year')) {
			$sub_pay = $sub_pay->groupBy(DB::raw('YEAR(clients.created_at)'))->get()->toArray();
			$total_coach_session = $total_coach_session->groupBy(DB::raw('YEAR(coach_schedules_booked.created_at)'))->get()->toArray();
			$pay_total_module_completion = $pay_total_module_completion->groupBy(DB::raw('YEAR(user_module_progresses.completed_at)'))->get()->toArray();
			$pay_total_coaching_sessions = $pay_total_coaching_sessions->groupBy(DB::raw('YEAR(coach_schedules_booked.created_at)'))->get()->toArray();
		}
		if (isset($input['group_by'])) {
			if ($input['group_by'] == 'month') {
				$sub_pay = $sub_pay->groupBy(DB::raw('MONTH(clients.created_at)'))->get()->toArray();
				$total_coach_session = $total_coach_session->groupBy(DB::raw('MONTH(coach_schedules_booked.created_at)'))->get()->toArray();
				$pay_total_module_completion = $pay_total_module_completion->groupBy(DB::raw('MONTH(user_module_progresses.completed_at)'))->get()->toArray();
				$pay_total_coaching_sessions = $pay_total_coaching_sessions->groupBy(DB::raw('MONTH(coach_schedules_booked.created_at)'))->get()->toArray();
			}
			if ($input['group_by'] == 'day') {
				$sub_pay = $sub_pay->groupBy(DB::raw('DATE(clients.created_at)'))->get()->toArray();
				$total_coach_session = $total_coach_session->groupBy(DB::raw('DATE(coach_schedules_booked.created_at)'))->get()->toArray();
				$pay_total_module_completion = $pay_total_module_completion->groupBy(DB::raw('DATE(user_module_progresses.completed_at)'))->get()->toArray();
				$pay_total_coaching_sessions = $pay_total_coaching_sessions->groupBy(DB::raw('DATE(coach_schedules_booked.created_at)'))->get()->toArray();
			}
			view()->share('group_by', $input['group_by']);
		}
		$reportData = $this->revice_array($sub_pay, $total_coach_session, $pay_total_module_completion, $pay_total_coaching_sessions, isset($input['group_by']) ? $input['group_by'] : 'year');
		return $reportData;
	}
	private function revice_array($sub_pay = array(), $total_coach_session = array(), $pay_total_module_completion = array(), $pay_total_coaching_sessions = array(), $groupby) {
		$result = array();
		if (!empty($sub_pay)) {
			foreach ($sub_pay as $pay_key => $pay_value) {
				if (!array_key_exists($pay_value['date'], $result)) {
					$result[$pay_value['date']] = $pay_value;
				} else {
					$result[$pay_value['date']]['total_sub_fee'] = $locker_value['total_sub_fee'];
				}
			}
		}
		if (!empty($total_coach_session)) {
			foreach ($total_coach_session as $coach_session_key => $coach_session_value) {
				if (array_key_exists($coach_session_value['date'], $result)) {
					$result[$coach_session_value['date']]['total_scedule'] = $coach_session_value['total_scedule'];
				} else {
					$result[$coach_session_value['date']] = $coach_session_value;
				}
			}
		}
		if (!empty($pay_total_module_completion)) {
			foreach ($pay_total_module_completion as $pay_total_module_completion_key => $pay_total_module_completion_value) {
				if (array_key_exists($pay_total_module_completion_value['date'], $result)) {
					$result[$pay_total_module_completion_value['date']]['total_module_completed'] = $pay_total_module_completion_value['total_module_completed'];
				} else {
					$result[$pay_total_module_completion_value['date']] = $pay_total_module_completion_value;
				}
			}
		}
		if (!empty($pay_total_coaching_sessions)) {
			foreach ($pay_total_coaching_sessions as $pay_total_coaching_sessions_key => $pay_total_coaching_sessions_value) {
				if (array_key_exists($pay_total_coaching_sessions_value['date'], $result)) {
					$result[$pay_total_coaching_sessions_value['date']]['total_pay_scedule'] = $pay_total_coaching_sessions_value['total_pay_scedule'];
				} else {
					$result[$pay_total_coaching_sessions_value['date']] = $pay_total_coaching_sessions_value;
				}
			}
		}
		if ($groupby == 'day') {
			uasort($result, function ($a, $b) {
				if ($a['date'] == $b['date']) {
					return 0;
				}
				$a_date = new Carbon($a['date']);
				$b_date = new Carbon($b['date']);
				return $a_date->gt($b_date) ? 1 : -1;
			});
		}
		return $result;

	}
	public function getPDF() {
		$reportData = $this->get_index();
		$count = 1;
		//return view('financialreport.pdf', ['RevenueReport' => $reportData, 'count' => $count, 'theme' => 'limitless.pdf']);
		$pdf = PDF::loadView('financialreport.pdf', ['RevenueReport' => $reportData, 'count' => $count, 'theme' => 'limitless.pdf']);
		$pdf->setPaper('a4');
		$pdf->setOrientation('portrait');
		$pdf->setOption('margin-top', 5);
		$pdf->setOption('margin-right', 5);
		$pdf->setOption('margin-bottom', 5);
		$pdf->setOption('margin-left', 5);
		$pdf->setOption('header-right', '');
		return $pdf->stream('financialreport.pdf');
	}



/* Export Csv Report */

public function getCsv(){
	$data = $this->getReportData();

	$this->exportExcelOrCsv($data['data'],$data['row'],'csv');
}

/* Export Xls Report */

public function getXls() {
	$data = $this->getReportData();

	$this->exportExcelOrCsv($data['data'],$data['row'],'xls');
}

public function exportExcelOrCsv($data,$row,$type)
{
	\Excel::create('Financial Report', function($excel) use($data,$row) {

		    $excel->sheet('Sheet1', function($sheet) use($data,$row) {
		    	    //dd($data);
				    $count=sizeof($data);
				    $count1=sizeof($data[0]);

				    if($count==12)
					{
				            $data0 = array(
					        array('Sales', $data[0][0],$data[1][0],$data[2][0],$data[3][0],$data[4][0],$data[5][0],$data[6][0],$data[7][0],$data[8][0],$data[9][0],$data[10][0],$data[11][0]),

				            array('Subscription Payment', $data[0][1],$data[1][1],$data[2][1],$data[3][1],$data[4][1],$data[5][1],$data[6][1],$data[7][1],$data[8][1],$data[9][1],$data[10][1],$data[11][1]) ,

							array( 'Coaching session', $data[0][2],$data[1][2],$data[2][2],$data[3][2],$data[4][2],$data[5][2],$data[6][2],$data[7][2],$data[8][2],$data[9][2],$data[10][2],$data[11][2]) ,

							array( 'Total sales',$data[0][3],$data[1][3],$data[2][3],$data[3][3],$data[4][3],$data[5][3],$data[6][3],$data[7][3],$data[8][3],$data[9][3],$data[10][3],$data[11][3]) ,

							array('Cost of Sales',$data[0][4],$data[1][4],$data[2][4],$data[3][4],$data[4][4],$data[5][4],$data[6][4],$data[7][4],$data[8][4],$data[9][4],$data[10][4],$data[11][4]) ,

							array('Subscription Payment', $data[0][5],$data[1][5],$data[2][5],$data[3][5],$data[4][5],$data[5][5],$data[6][5],$data[7][5],$data[8][5],$data[9][5],$data[10][5],$data[11][5]) ,

							array('Coaching session',  $data[0][6],$data[1][6],$data[2][6],$data[3][6],$data[4][6],$data[5][6],$data[6][6],$data[7][6],$data[8][6],$data[9][6],$data[10][6],$data[11][6]) ,

							array( 'Total sales', $data[0][7],$data[1][7],$data[2][7],$data[3][7],$data[4][7],$data[5][7],$data[6][7],$data[7][7],$data[8][7],$data[9][7],$data[10][7],$data[11][7]) ,

							array( 'Gross Profit',  $data[0][8],$data[1][8],$data[2][8],$data[3][8],$data[4][8],$data[5][8],$data[6][8],$data[7][8],$data[8][8],$data[9][8],$data[10][8],$data[11][8]) ,

							);

					}
					else if($count==2)
					{
					    for($i=0;$i<$count-1;$i++)
					    {

					            $data0 = array(
								  array('Sales', $data[$i][0],$data[$i+1][0]) ,
								  array('Subscription Payment', $data[$i][1],$data[$i+1][1]) ,
								   array( 'Coaching session', $data[$i][2],$data[$i+1][2]) ,
								    array( 'Total sales',$data[$i][3],$data[$i+1][3]) ,
								     array('Sales',$data[$i][4],$data[$i+1][4]) ,
								      array('Subscription Payment', $data[$i][5],$data[$i+1][5]) ,
								       array('Coaching session', $data[$i][6],$data[$i+1][6]) ,
								        array( 'Total sales',$data[$i][7],$data[$i+1][7]) ,
								         array( 'Gross Profit', $data[$i][8],$data[$i+1][8]) ,

								);
					    }
					}
					else
					{
						$data0=$data;
					}
			        //       for($i=0;$i<$count;$i++)
					// {
					// 	for($j=0;$j<$count1;$j++)
					// 	{
					//     	$data2[]=array($data0[$i],$data[$i][$j],$data[$i+1][$j]);
					// 	}
					// }

					$p = 2;
					for($x=1;$x<=$row;$x++)
					{
					 	if($x!=1) {  $p = $p + 14;  }
					 	$sheet->setBorder('A'.$p, 'thin');
					 	//$sheet->mergeCells('A'.$p.':C'.$p);
					}
					$sheet->fromArray($data0);

			});

		})->export($type);
}

public function getReportData(){

		$reportData = $this->get_index();
		$input = request()->all();
		$input = array_except($input, array('_token', 'search'));
		$input = AppHelper::getTrimmedData($input);

		if(isset($input['group_by']) && $input['group_by'] == 'month')
	    {

                foreach(config('srtpl.months') as $mnth_key=>$Month_value)
				{
							if($mnth_key > 1){	$data[] = array('');array('');	}

							$data1[] = $Month_value;
							$month = config('srtpl.months');

                            if(array_key_exists($mnth_key,$reportData))
                            {

				       				$total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0;

		                            /* First Table Calculation */
		                            $total_sub_fee = isset($reportData[$mnth_key]['total_sub_fee']) ? $reportData[$mnth_key]['total_sub_fee'] : 0;
		                            $total_scedule = isset($reportData[$mnth_key]['total_scedule']) ? $reportData[$mnth_key]['total_scedule'] : 0;
		                            $total_payment_received = $total_sub_fee + $total_scedule;

		                            /* Second Table Calculation */
		                            $total_module_completed = isset($reportData[$mnth_key]['total_module_completed']) ? $reportData[$mnth_key]['total_module_completed'] : 0;
		                            $total_pay_scedule = isset($reportData[$mnth_key]['total_pay_scedule']) ? $reportData[$mnth_key]['total_pay_scedule'] : 0;
		                            $total_payment_to_coach = $total_module_completed + $total_pay_scedule;


		                            	if($total_payment_received == 0)
		                                {
			                                 $ratio_of_subscription_payments = 0;
			                                 $ratio_of_coaching_session = 0;
			                                 $total_ratio_of_sales =  0;

			                                 if($total_payment_to_coach ==0)
			                                 {
			                                      $ratio_of_total_module_completed = 0;
			                                      $ratio_of_total_pay_scedule = 0;
			                                      $total_ratio_of_coast_sales = 0;
			                                 }
			                                 else{
			                                      $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_to_coach;
			                                      $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_to_coach;
			                                      $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
			                                 }

		                                }
	                            		else{
			                                $ratio_of_subscription_payments = ($total_sub_fee * 100) / $total_payment_received;
			                                $ratio_of_coaching_session = ($total_scedule * 100) / $total_payment_received;
			                                $total_ratio_of_sales =  $ratio_of_subscription_payments + $ratio_of_coaching_session;

			                                /* For Second Table Ratio */
			                                $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_received;
			                                $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_received;
			                                $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
		                                }

				                    $total_net_revenue= $total_payment_received - $total_payment_to_coach;
				                    $total_net_ratio = $total_ratio_of_sales - $total_ratio_of_coast_sales;

									$data2[] = number_format($total_sub_fee,2);
									$data3[] =number_format($total_scedule,2);
									$data4[] = number_format($total_payment_received,2);
									$data5[] = '';
									$data6[] = number_format($total_module_completed,2);
									$data7[] = number_format($total_pay_scedule,2);
									$data8[] = number_format($total_payment_to_coach,2);
									$data9[] = number_format($total_net_revenue,2);

							}
			           	else{

									$data2[] = number_format(0,2);
									$data3[] = number_format(0,2);
									$data4[] = number_format(0,2);
									$data5[] = '';
									$data6[] = number_format(0,2);
									$data7[] = number_format(0,2);
									$data8[] = number_format(0,2);
									$data9[] = number_format(0,2);
						}
                }

				$size=count($data1);
				for($i=0;$i<$size;$i++)
				{
					$row1[]=array($data1[$i],$data2[$i],$data3[$i],$data4[$i],$data5[$i],$data6[$i],$data7[$i],$data8[$i],$data9[$i]);
				}
                	$value = [
							'data' => $row1,
							'row' => 12,
							];
        }
        else{

              	$count = 0;

              	foreach($reportData as $report_key=>$report_value)
              	{
              		 $count++;
                     $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0;

                     /* First Table Calculation */

                    $total_sub_fee = isset($report_value['total_sub_fee']) ? $report_value['total_sub_fee'] : 0;
                    $total_scedule = isset($report_value['total_scedule']) ? $report_value['total_scedule'] : 0;
                    $total_payment_received = $total_sub_fee + $total_scedule;

                    /* Second Table Calculation */
                    $total_module_completed = isset($report_value['total_module_completed']) ? $report_value['total_module_completed'] : 0;
                    $total_pay_scedule = isset($report_value['total_pay_scedule']) ? $report_value['total_pay_scedule'] : 0;
                    $total_payment_to_coach = $total_module_completed + $total_pay_scedule;


                        if($total_payment_received == 0)
                        {
                              $ratio_of_subscription_payments = 0;
                              $ratio_of_coaching_session = 0;
                              $total_ratio_of_sales =  0;

                                 if($total_payment_to_coach ==0)
                                 {
	                                      $ratio_of_total_module_completed = 0;
	                                      $ratio_of_total_pay_scedule = 0;
	                                      $total_ratio_of_coast_sales = 0;
                                 }
                                  else{
	                                      $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_to_coach;
	                                      $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_to_coach;
	                                      $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
                                 }

                        }
                        else
                        {
                                $ratio_of_subscription_payments = ($total_sub_fee * 100) / $total_payment_received;
                                $ratio_of_coaching_session = ($total_scedule * 100) / $total_payment_received;
                                $total_ratio_of_sales =  $ratio_of_subscription_payments + $ratio_of_coaching_session;

                                /* For Second Table Ratio */
                                $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_received;
                                $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_received;
                                $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
                        }

                        $total_net_revenue= $total_payment_received - $total_payment_to_coach;
                        $total_net_ratio = $total_ratio_of_sales - $total_ratio_of_coast_sales;


						$data1[] = $report_key;
						$data2[] = number_format($total_sub_fee,2);
						$data3[] = number_format($total_scedule,2);
						$data4[] = number_format($total_payment_received,2);

						$data5[] = '';
						$data6[] = number_format($total_module_completed,2);
						$data7[] = number_format($total_pay_scedule,2);
						$data8[] = number_format($total_payment_to_coach,2);
					    $data9[] = number_format($total_net_revenue,2);
				}
				$size=count($data1);

				for($i=0;$i<$size;$i++)
				{
					$row1[]=array($data1[$i],$data2[$i],$data3[$i],$data4[$i],$data5[$i],$data6[$i],$data7[$i],$data8[$i],$data9[$i]);
				}

					$value = [
							'data' => $row1,
							'row' => count($reportData),
							];

			}

		return $value;

	}


}
