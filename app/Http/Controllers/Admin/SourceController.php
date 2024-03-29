<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Source;
use Validator;
use Auth;
use Yajra\DataTables\DataTables;
class SourceController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        return view('admin.source.browse');
    }
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $description = $request->input('description');
        $rules = [
            'description' => 'required',
        ];
        $messages = [
            'description.required' => 'សូមបំពេញកន្លែងដែលមានសញ្ញា *',
        ];

        $validator  = Validator::make($rules, $messages);
        if (!$validator->fails()) {
            \DB::beginTransaction();
            try {
                $data_insert    = [
                    'description'     =>  $description,
                    'user_id'     =>  $user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                Source::insert($data_insert);
                \DB::commit();
                $return['status']   = 1;
                $return['content']  = 'Successful';
            } catch (Exception $e) {
                \DB::rollBack();
                $return['status']   = 0;
                $return['content']  = 'Unsuccessful';
            }
        }else{
            $return['status'] = response()->json($validator->messages(), 200);
        }
        $return['title']        = 'Insert Data';
        return json_encode($return);
    }

    public function edit(Request $request,$id)
    {
        return Source::find($id);
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $description = $request->input('description');
        $validator = Validator::make($request->all(), [
            'description'    => 'required',
        ]);
        if (!$validator->fails()) {
            \DB::beginTransaction();
            try {
                $data_update    = [
                    'description'     =>$description,
                    'user_id'  =>  $user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                Source::where('id',$id)->update($data_update);
                \DB::commit();
                $return['status']   = 1;
                $return['content']  = 'Successful';
            } catch (Exception $e) {
                \DB::rollBack();
                $return['status']   = 0;
                $return['content']  = 'Unsuccessful'.$e->getMessage();;
            }
        }else{
            $return['status']   = 0;
        }
        $return['title']        = 'Update Data';
        return json_encode($return);
    }

    public function destroy(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            Source::where('id',$id)->delete();
            \DB::commit();
            $return['status']   = 1;
            $return['content']  = 'Successful';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status']   = 0;
            $return['content']  = 'Unsuccessful';
        }
        $return['title']        = 'Delete Data';
        return json_encode($return);
    }

    public function lists(){
        $result = Source::with('users')->get();
        return DataTables::of($result)
        ->editColumn('user_id', function ($rec) {
            $str = $rec->users->username;
            return $str;
        })
        ->addColumn('action',function($rec){
            $update = '<button type="button" class="btn btn-success btn-edit" data-id="'.$rec->id.'"><i class="icon voyager-edit"></i>Edit</button>';

//            $delete = '<button type="button" class="btn btn-danger btn-delete" data-id="'.$rec->id.'"><i class="icon voyager-trash"></i>Delete</button>';
//            $str=$update.' '.$delete;
            $str=$update;
            return $str;
        })->make(true);
    }
}
