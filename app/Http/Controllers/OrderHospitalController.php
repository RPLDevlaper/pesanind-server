<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\OrderHospital;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderHospitalController extends Controller
{

    public $type;
    public $data;

    public function __construct(OrderHospital $data)
    {
        $this->data = $data;
        $this->type = 'Order Hospital';
    }

    public function index(Request $request)
    {
        $query = $this->data->query();
        if($request->get('userId') && $request->get('userId') != null) {
            $user = User::find($request->get('userId'));
            $data = $query->with('User')->whereHas('User', function($query) use ($user) {
                $query->where('id', $user->id)->get();
            });
        }
        if($request->get('hospitalId') && $request->get('hospitalId') != null) {
            $hospital = Hospital::find($request->get('hospitalId'));
            $data = $query->with('Hospital')->whereHas('Hospital', function($query) use ($hospital) {
                $query->where('id', $hospital->id)->get();
            });
        }
        if($request->get('code') && $request->get('code') != null) {
            $data = $query->where('code', $request->get('code'));
        }
        if($request->get('pagination') && $request->get('pagination') != null) {
            $data = $query->paginate($request->get('pagination'));
        } else {
            $data = $query->get();
        }
        return $this->onSuccess($this->type, $data, 'Founded');
    }

    public function store(Request $request)
    {
        try {
            $data = new OrderHospital();
            $data->user_id = Auth::id();
            $data->hospital_id = $request->hospital;
            $data->time = $request->date .' '. $request->time;
            $orders = OrderHospital::where('created_at', '>=', Carbon::today())->orderBy('id', 'DESC')->get();
            $user = User::find(Auth::id());
            if(count($orders) > 0) {
                $data->number = $orders[0]->number + 1;
            } else {
                $data->number = 1;
            }
            $data->code = substr($user->username, 0, 4).'-'.Str::random(4);
            $data->save();
            return $this->onSuccess($this->type, $data, 'Stored');
        } catch (\Exception $e) {
            return $this->onError($e);            
        }
    }

    public function show($id)
    {
        $data = $this->data->find($id);
        return $this->onSuccess($this->type, $data, 'Founded');
    }

    public function update(Request $request, $id)
    {
        try {
            $update = $this->data->where('id', $id)->update($request->except('_method', '_token'));
            $data = $this->data->find($id);
            return $this->onSuccess($this->type, $data, 'Updated');
        } catch (\Exception $e) {
            return $this->onError($e);            
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->data->find($id);
            $destroy = $this->data->destroy($id);
            return $this->onSuccess($this->type, $data, 'Destroyed');
        } catch (\Exception $e) {
            return $this->onError($e);
        }
    }
}
