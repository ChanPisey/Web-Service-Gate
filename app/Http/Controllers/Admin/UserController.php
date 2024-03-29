<?php

namespace App\Http\Controllers\Admin;

use Config;
use App\User;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Voyager\VoyagerBaseController;

class UserController extends VoyagerBaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function edit(Request $request, $id)
    // {
    //     $row = User::with('role')->find($id);
    //     return $row;
    // }


}
