<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageServiceProvider;
use App\CardImage;
use Validator;
use Auth;
use Yajra\DataTables\DataTables;

class CardImageController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
    	 return view('admin.card-image.browse');
    }

    public function store(Request $request)
    {
    	if($request->hasfile('files'))
         {
         	$images = $request->file('files');
         	$filename = $images->getClientOriginalName();
         	$galleryName = "Folder01";
         	$path = public_path().'/images/article/imagegallery/' . $galleryName;
			File::makeDirectory($path, $mode = 0777, true, true);
         	// $destinationPath = public_path('/images');
         	// $destinationPath​​ = Storage::disk('public')->put($images->getFilename().'.'.$filename,  File::get($images));
         	// $destinationPath​​ = Storage::disk('public')->put('avatars/1',  $filename);
         	$images->move($path, $filename);
         }

        $rules = [
            'files' => 'required',
        ];
        $messages = [
            'filse.required' => 'សូមបំពេញកន្លែងដែលមានសញ្ញា *',
        ];

        $validator  = Validator::make($rules, $messages);
        if (!$validator->fails()) {
            \DB::beginTransaction();
            try {
                $data_insert    = [
                    'url_image'     =>  $filename,
                    // 'user_id'     =>  $user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                CardImage::insert($data_insert);
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
        $return['title']   = 'Insert Data';
        return json_encode($return);
    }

    public function destroy(Request $request, $id)
    {
    	\DB::beginTransaction();
        try {
        	$galleryName = "Folder01";      	
            $image = CardImage::where('id',$id)->first();
            $file = $image->url_image;
            $filename = public_path().'/images/article/imagegallery/'.$galleryName.'/'.$file;
            \File::delete($filename);
            CardImage::where('id',$id)->delete();
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
    	

    public function lists(Request $request)
    {
    	$result = CardImage::query();
        return DataTables::of($result)
        ->addColumn('image', function ( $image ) {
                $url= asset('/images/article/imagegallery/Folder01/'.$image->url_image);
                return '<a href="'.$url.'"><img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" /></a>';
            })
        ->addColumn('action',function($rec){
            $update = '<button type="button" class="btn btn-success btn-edit" data-id="'.$rec->id.'">កែប្រែ</button>';

            $delete = '<button type="button" class="btn btn-danger btn-delete" data-id="'.$rec->id.'">លុប</button>';
            $str=$update.' '.$delete;
            return $str;
        })->rawColumns(['image', 'action'])->make(true);
    }

}
