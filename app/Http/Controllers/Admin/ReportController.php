<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class ReportController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('admin.reports.browse');
    }

}
