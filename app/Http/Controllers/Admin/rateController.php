<?php

namespace App\Http\Controllers\Admin;

use App\Rate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use Yajra\DataTables\DataTables;


class rateController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
    	 return view('admin.rate.browse');
    }
	public function getEmotionPercentage($emotionAmount, $allAmount){
		$onePercent = $allAmount / 100;
		$percentat = ($emotionAmount*100)/$allAmount;
		return $percentat;
	}
    public function rateCount(Request $request){
			$model = DB::table('rate');
		if ($request->has('start') &&$request->has('end')){
			$model->whereDate('created_at','>=', $request->start);
			$model->whereDate('created_at','<=', $request->end);
        }
        $allAmount = $model->count();
        $data = $model->select(DB::raw('count(*) as total, emotion_id'))
			->groupBy('emotion_id')
			->get();
        $default = [
			['total'=>0,'emotion_id'=>1,'percentage'=>0],
			['total'=>0,'emotion_id'=>2,'percentage'=>0],
			['total'=>0,'emotion_id'=>3,'percentage'=>0],
			['total'=>0,'emotion_id'=>4,'percentage'=>0],
			['total'=>0,'emotion_id'=>5,'percentage'=>0],
        ];
        $return = [];
		foreach ($default as $r){
			foreach ($data as $row){
				if ($r['emotion_id'] == $row->emotion_id){
					$r['total'] = $row->total;
					$r['percentage'] = $this->getEmotionPercentage($row->total,$allAmount);
				}
			}
			$return[] = $r;
		}
        return $return;
    }
    protected function getLineChatData($emotion_id, $year){
		$default = [1,2,3,4,5,6,7,8,9,10,11,12];
        $data = [];
		$year = ($year==0)?date('Y'):$year;
		foreach ($default as $item){		
			$model = DB::table('rate');
			$alldata = DB::select("select emotion_id, MONTH(created_at)  from rate where year(created_at)= {$year} and MONTH(created_at) = {$item} AND emotion_id={$emotion_id}");				
			 if ($model!= null) {
				 $data[] = count($alldata);
			 } else {
			 	$data[] = 0;
			 }
		}
		return $data;
	}
    public function lineChat(Request $request){
		$default = [
//			['name'=>'មិនពេញចិត្តខ្លាំង','emotion_id'=>1,'data'=>[],'color'=>'#dd4b39'],
			['name'=>'មិនពេញចិត្ត','emotion_id'=>2,'data'=>[],'color'=>'#f39c12'],
//			['name'=>'ធម្មតា','emotion_id'=>3,'data'=>[], 'color'=>'#0073b7'],
			['name'=>'ពេញចិត្ត','emotion_id'=>4,'data'=>[], 'color'=>'#00c0ef'],
//			['name'=>'ពេញចិត្តខ្លាំង','emotion_id'=>5,'data'=>[], 'color'=>'#00a65a'],
		];
		$year = $request->year;
		$data = [];
		foreach ($default as $index => $item) {
			if ($request->has('emotions')){
				$input = $request->emotions;
				if (in_array($item['emotion_id'], $input)){
					$item['data'] = $this->getLineChatData($item['emotion_id'], $year);
					$data[] = $item;
				}
			} else{
				$item['data'] = $this->getLineChatData($item['emotion_id'], $year);
				$data[] = $item;
            }         
		}
		return $data;
	}
    protected function getLBarChartData($emotion_id, $service_id, $organization_id, $year){
		$default = [1,2,3,4,5,6,7,8,9,10,11,12];
		$data = [];
		$year = ($year==0)?date('Y'):$year;
		foreach ($default as $item){
			$alldata = DB::select("select emotion_id, MONTH(created_at),service,organization 
						from rate where year(created_at)= {$year} and MONTH(created_at) = {$item} 
						AND emotion_id={$emotion_id} AND service = {$service_id} 
						AND organization = {$organization_id}");			
			if ($alldata!= null) {
				$data[] = count($alldata);
			} else {
				$data[] = 0;
			}
		}
		return $data;
	}
    public function barChart(Request $request){
		$organization_id = $request->organization;
		$default = [
			['name'=>'ពេញចិត្ត','emotion_id'=>4, 'service_id'=>1, 'organization'=>$organization_id,'data'=>[], 'color'=>'#00c0ef'],
			['name'=>'មិនពេញចិត្ត','emotion_id'=>2, 'service_id'=>1,'organization'=>$organization_id, 'data'=>[],'color'=>'#f39c12'],
		];
		$year = $request->year;
		$service_id = ($request->service==0)?1:$request->service;
		$organization_id = $request->organization;
		$data = [];
		foreach ($default as $index => $item) {
			if ($request->has('emotions')){
				$input = $request->emotions;
				if (in_array($item['emotion_id'], $input)){
					$item['data'] = $this->getLBarChartData($item['emotion_id'],$service_id, $organization_id,$year);
					$item['service_id'] = $service_id;
					$data[] = $item;
				}
			} else{
				$item['data'] = $this->getLBarChartData($item['emotion_id'],$service_id, $organization_id,  $year);
				$data[] = $item;
			}
		}
		
		return $data;
	}
	
	// public function save(Request $request)  {		
	// 	dd($request->all());
	// 	if ($request->isMethod('put')) {
	// 		//Get the task
	// 		$model = Rate::find($request->id);
	// 		if (!$model) {
	// 			return 'Task Not Found';
	// 		}
	// 	} else {
	// 		$model = new Rate;
	// 	}
	// 	$input = $request->all();

	// 	if(DB::table("rate")->insert($input)) {
	// 		return "success";
	// 	} else {
	// 		return "Error";
	// 	}
	// }
}

?>