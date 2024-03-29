<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\IdentifyCard;
use Validator;
use Auth;
use Yajra\DataTables\DataTables;

class IdentifyCardController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
    	 return view('admin.identify-card.browse');
    }
}
