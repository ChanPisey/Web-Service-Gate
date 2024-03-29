<?php

namespace App\Http\Controllers\Admin;

use App\CardList;
use App\User;
use Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UnitController extends VoyagerBaseController
{
    public $data = array();

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['constant'] = Config::get('constant');
    }

    public function index(Request $request)
    {
        $officersList = User::get()->pluck('username');
        return view('admin.cards.browse', compact('officersList'))->with($this->data);
    }

    /* Button Activate / Deactivate */
    public function editStatus(Request $request)
    {
        $status = CardList::where('id', '=', $request->id)->pluck('status')
            ->first();
        if ($status === 1) {
            $status_change = 0;
        } else {
            $status_change = 1;
        }
        CardList::where('id', '=', $request->id)
            ->update(array('status' => $status_change));

        return response()->json(['success' => $status_change]);
    }

    public function checkCardCode(Request $request)
    {
        if (!empty($request->card_code)) {
            if (!empty($request->id)) {
                $card_code = CardList::where('card_code', '=', $request->card_code)
                    ->where('id', '!=', $request->id)
                    ->count();
                if ($card_code == 0) {
                    echo "true";  // good to add
                } else {
                    echo "false"; //already taken
                }
            }else{
                $card_code = CardList::where('card_code', '=', $request->card_code)->count();
                if ($card_code == 0) {
                    echo "true";  // good to add
                } else {
                    echo "false"; //already taken
                }
            }
        } else {
            echo "false";
        }
    }
    public function checkCardNumber(Request $request)
    {
        if (!empty($request->card_number)) {
            if (!empty($request->id)) {
                $card = CardList::where('card_number', '=', $request->card_number)
                    ->where('id', '!=', $request->id)
                    ->count();
                if ($card == 0) {
                    echo "true";  // good to add
                } else {
                    echo "false"; //already taken
                }
            }else {
                $card_number = '';
                $guest_type = $request->guest_type;
                $press = "សារព័ត៌មាន";
                $visitor = "ភ្ញៀវ";
                if ($guest_type === $press) {
                    $card_number = "P" . $request->card_number;
                }
                if ($guest_type === $visitor) {
                    $card_number = "V" . $request->card_number;
                }
                $card = CardList::where('card_number', '=', $card_number)->count();
                if ($card == 0) {
                    echo "true";  // good to add
                } else {
                    echo "false"; //already taken
                }
            }
        } else {
            echo "false";
        }
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $guest_type = $request->guest_type;
        $card_code = $request->card_code;
        $press = "សារព័ត៌មាន";
        $visitor = "ភ្ញៀវ";
        $card_number = '';
        if ($guest_type === $press) {
            $card_number = "P" . $request->card_number;
        }
        if ($guest_type === $visitor) {
            $card_number = "V" . $request->card_number;
        }
        $gate = $request->gate;
        $active = isset($request->active) ? $request->active : 0;

        if ($request->id_select === null) {
            $rules = [
                'guest_type' => 'required',
                'card_code' => 'required|numeric|unique:guest_card_list',
                'card_number' => 'required|unique:guest_card_list',
                'gate' => 'required',
            ];
            $input = [
                'guest_type' => $request->input('guest_type'),
                'card_code' => $request->input('card_code'),
                'card_number' => $card_number,
                'gate' => $request->input('gate')
            ];

            $validator = Validator::make($input, $rules);

            if (!$validator->fails()) {
                $data_insert = [
                    'user_id' => $user_id,
                    'guest_type' => $guest_type,
                    'card_code' => $card_code,
                    'card_number' => $card_number,
                    'gate_direction' => $gate,
                    'status' => $active,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                CardList::insert($data_insert);

                return response()->json(['success' => 'Successfully!!']);
            } else {
                /* user error  */
                return response()->json([
                    'error' => $validator->errors()->all(),
                ]);
            }
        } else {
            $id = $request->id_select;
            $rules_update = [
                'guest_type' => 'required',
                'card_code' => 'required|numeric|unique:guest_card_list,card_code,' . $id,
                'card_number' => 'required|unique:guest_card_list,card_number,' . $id,
                'gate' => 'required',
            ];
            $validator_update = Validator::make($request->all(), $rules_update);
            if (!$validator_update->fails()) {
                $data_insert = [
                    'user_id' => $user_id,
                    'guest_type' => $guest_type,
                    'card_code' => $card_code,
                    'card_number' => $request->card_number,
                    'gate_direction' => $gate,
                    'status' => $active,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                CardList::where('id', '=', $id)->update($data_insert);

                return response()->json(['success' => 'Successfully!!']);
            } else {
                /* user error  */
                return response()->json([
                    'error' => $validator_update->errors()->all(),
                ]);
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $row = CardList::with('user')->find($id);

        return $row;
    }

    public function destroy(Request $request, $id)
    {
        $getCardById = CardList::with('guestList')
            ->where('id', '=', $id)
            ->first();
        $cardUsing = $getCardById->guestList->count();
        if ($cardUsing > 0) {
            return response()->json(['error' => 'Card is using! ']);
        }
        CardList::where('id', $id)->delete();

        return response()->json(['success' => 'Successfully!!']);
    }

    public function lists(Request $request)
    {
        $status = $request->status;
        $result = CardList::with('user', 'guestList')
            ->when($status != null, function ($query) use ($status) {
                $query->where('status', '=', $status);
                return $query;
            })
            ->orderBy('id', 'desc')
            ->get();
        return DataTables::of($result)
            ->addColumn('action', function ($rec) {
                $update
                    = '<button type="button" class="btn btn-warning btn-edit" data-id="'
                    . $rec->id
                    . '"><i class="icon voyager-edit"></i>កែប្រែ</button>';
                $delete
                    = '<button type="button" class="btn btn-danger btn-delete" data-id="'
                    . $rec->id
                    . '"><i class="icon voyager-trash"></i>លុប</button>';
                $str = $update . ' ' . $delete;
                return $str;
            })
            ->addColumn('status', function ($rec) {
                return $rec->status;
            })
            ->addColumn('user', function ($rec) {
                return $rec->user->username;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
