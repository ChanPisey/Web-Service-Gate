<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\RealImage;
use Validator;
use Auth;
use Yajra\DataTables\DataTables;

class RealImageController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
    	 return view('admin.real-image.browse');
    }
    public function lists(Request $request)
    {
    	$result = RealImage::query();
        return DataTables::of($result)
        ->addColumn('action',function($rec){
            $update = '<button type="button" class="btn btn-success btn-edit" data-id="'.$rec->id.'">កែប្រែ</button>';

            $delete = '<button type="button" class="btn btn-danger btn-delete" data-id="'.$rec->id.'">លុប</button>';
            $str=$update.' '.$delete;
            return $str;
        })->make(true);
    }
}
