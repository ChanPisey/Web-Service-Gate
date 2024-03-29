<?php

namespace App\Http\Controllers\Admin;

use App\CardList;
use App\Gate;
use App\Imports\GuestsImport;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Form;
use App\CardImage;
use App\IdentifyCard;
use App\RealImage;
use App\Source;
use App\Nationality;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Support\Facades\Validator;
use Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class FormController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $institutions = Source::get();
        $guestCardList = CardList::get();
        $nationalities = Nationality::get();
        /* data for datatable column filter */
        $cardNumberList = CardList::get();
        $gates = Gate::get();
        $officersList = User::get()->pluck('username');

        return view('admin.guests.browse',
            compact('institutions', 'guestCardList', 'nationalities', 'cardNumberList', 'officersList', 'gates'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $institution = $request->input('institution_id');
        $nationality = $request->input('nationality_id');
        $name = $request->input('name');
        $card_id = $request->input('card_number');
        $gender = $request->input('gender');
        $phone_number = $request->input('phone_number');

        $rules = [
            'institution_id' => 'required',
            'nationality_id' => 'required',
            'card_number' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $data_insert_form = [
                'institution_id' => $institution,
                'nationality_id' => $nationality,
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $id_port = Form::insertGetId($data_insert_form);
            $data_insert_guest = [
                'name' => $name,
                'guest_card_id' => $card_id,
                'gender' => $gender,
                'gender' => $user_id,
                'phone' => $phone_number,
                'port_id' => $id_port,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            IdentifyCard::insertGetId($data_insert_guest);
            dd($id_port);
            return response()->json(['success' => 'Successfully!!']);
        } else {
            /* user error  */
            return response()->json([
                'error' => $validator->errors()->all(),
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        return Form::with('cardimage', 'realimage', 'identifycard')
            ->whereHas('identifycard', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })
            ->first();
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|not_in:0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $data_update = [
                'name' => $request->name,
                'gender' => $request->gender,
                'phone' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            IdentifyCard::where('port_id', $id)->update($data_update);
            return response()->json(['success' => 'Successfully!!']);
        } else {
            /* user error  */
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            // delete card image
            $cardimage = CardImage::where('port_id', $id)->first();
            $filecardimage = $cardimage->url_image;
            $cardimage_id = $cardimage->id;
            Storage::delete('public/cardImages/' . $filecardimage);
            CardImage::where('id', $cardimage_id)->delete();

            //Delete Real image
            $realimage = RealImage::where('port_id', $id)->first();
            $filerealimage = $realimage->url_image;
            $realimage_id = $realimage->id;
            Storage::delete('public/real_images/' . $filerealimage);
            RealImage::where('id', $realimage_id)->delete();

            // Delete Identify Card
            $identifycard = IdentifyCard::where('port_id', $id)->first();
            $identifycard_id = $identifycard->id;
            IdentifyCard::where('id', $identifycard_id)->delete();

            // Delete Port in out
            $port_in_out = Form::where('id', $id)->delete();

            \DB::commit();
            $return['status'] = 1;
            $return['content'] = 'Successful';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status'] = 0;
            $return['content'] = 'Unsuccessful';
        }
        $return['title'] = 'Delete Data';

        return json_encode($return);
    }

    public function import( Request $request ){
        dd(12);
        Excel::import(new GuestsImport(),request()->file('file'));
        return back();
    }
    public function lists(Request $request)
    {
        $checkInRage = $request->checkInRage;
        $checkOutRange = $request->checkOutRange;
            $result = IdentifyCard::with('point_inout.source','point_inout.nationality','point_inout.cardimage','point_inout.realimage','point_inout.user','cardList','checkinBy','checkoutBy')
                ->when($checkInRage != null || $checkOutRange != null, function ($query) use ($checkInRage,$checkOutRange) {
                    $check_in = explode("-",$checkInRage);
                    $check_out = explode("-",$checkOutRange);
                    if($checkInRage != null && $checkOutRange != null)
                    {
                        $firstCheckIn = str_replace('/', '-', $check_in[0]);
                        $secondCheckIn = str_replace('/', '-', $check_in[1]);
                        $firstCheckOut= str_replace('/', '-', $check_out[0]);
                        $secondCheckOut = str_replace('/', '-', $check_out[1]);
                        $search_date['check_in_from'] = date("Y-m-d H:i:s", strtotime($firstCheckIn));
                        $search_date['check_in_to'] = date("Y-m-d H:i:s", strtotime($secondCheckIn));

                        $search_date['check_out_from'] = date("Y-m-d H:i:s", strtotime($firstCheckOut));
                        $search_date['check_out_to'] = date("Y-m-d H:i:s", strtotime($secondCheckOut));

                        $query->whereBetween('checkin_date', [$search_date['check_in_from'], $search_date['check_in_to']])
                            ->whereBetween('checkout_date', [$search_date['check_out_from'], $search_date['check_out_to']]);
                    }elseif ($checkInRage != null && $checkOutRange == null)
                    {
                        $firstCheckIn = str_replace('/', '-', $check_in[0]);
                        $secondCheckIn = str_replace('/', '-', $check_in[1]);
                        $search_date['check_in_from'] = date("Y-m-d H:i:s", strtotime($firstCheckIn));
                        $search_date['check_in_to'] = date("Y-m-d H:i:s", strtotime($secondCheckIn));
                        $query->whereBetween('checkin_date', [$search_date['check_in_from'], $search_date['check_in_to']]);
                    }elseif ($checkOutRange != null && $checkInRage == null)
                    {
                        $firstCheckOut= str_replace('/', '-', $check_out[0]);
                        $secondCheckOut = str_replace('/', '-', $check_out[1]);
                        $search_date['check_out_from'] = date("Y-m-d H:i:s", strtotime($firstCheckOut));
                        $search_date['check_out_to'] = date("Y-m-d H:i:s", strtotime($secondCheckOut));
                        $query ->whereBetween('checkout_date', [$search_date['check_out_from'], $search_date['check_out_to']]);
                    }
                    return $query;
                })
                ->orderBy('id', 'desc')->get();
        return DataTables::of($result)
//            ->addColumn('guest_photo', function ($image) {
//                $url = asset('/storage/' . $image->point_inout->realimage->url_image);
//
//                return '<img src="' . $url . '" border="0" width="40" class="img-rounded" id="guest_photo" align="center"/>';
//            })
            ->addColumn('guest_card', function ($image) {
                if($image->point_inout->cardimage->url_image != null){
                    $url = asset('/storage/' . $image->point_inout->cardimage->url_image);
                    return '<img src="' . $url
                        . '" border="0" width="40" class="img-rounded"  id="guest_card"  align="center" />';
                }else{
                    return "ពុំមាន";
                }
            })
            ->addColumn('gate_direction', function ($rec) {
                return $rec->gate->name;
            })
            ->addColumn('guest_type', function ($rec) {
                return $rec->cardList->guest_type;
            })
            ->addColumn('status', function ($rec) {
                return  $rec->status;
            })
            ->editColumn('check_in_by', function ($rec) {
                $str = $rec->checkin_by;
                $users = User::where('id',$str)->pluck('username')->first();
                return $str == null ? 'Using' : $users;
            })
            ->editColumn('checkout_date', function ($rec) {
                $str = $rec->checkout_date;

                return $str === null ? 'ពុំមាន' : $str;
            })
            ->editColumn('check_out_by', function ($rec) {
                $str = IdentifyCard::where('id',$rec->id)->pluck('checkout_by')->first();
                $users = User::where('id',$str)->pluck('username')->first();
                return $str === null ? 'ពុំមាន' : $users;
            })
            ->editColumn('institution_id', function ($rec) {
                $str = $rec->point_inout->source->description;
                return $str;
            })
            ->editColumn('nationality_id', function ($rec) {
                $str = $rec->point_inout->nationality->nationality;

                return $str;
            })
            ->addColumn('action', function ($rec) {
                $update
                    = '<button type="button" class="btn btn-warning btn-edit" data-id="'
                    . $rec->id
                    . '"><i class="icon voyager-edit"></i>កែប្រែ</button>';
                $str = $update;

                return $str;
            })->rawColumns([ 'guest_card', 'action'])->make(true);
    }

}
