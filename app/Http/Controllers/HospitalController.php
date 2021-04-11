<?php

namespace App\Http\Controllers;

use App\Models\FileHospital;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HospitalController extends Controller
{
    public $data;
    public $dimen;
    public $path;
    public $type;

    public function __construct(Hospital $data)
    {
        $this->data = $data;
        $this->dimen = 750;
        $this->path = public_path().'/img/hospital/';
        $this->type = 'Hospital';
    }

    public function index(Request $request)
    {
        $query = $this->data->query();
        if($request->get('name') && $request->get('name') != null) {
            $data = $query->where('name', 'LIKE', '%'.$request->get('name').'%');
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
            $data = $this->data->create($request->all());
            return $this->onSuccess($this->type, $data, 'Created');
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
            $files = FileHospital::where('hospital_id', $id)->get();
            foreach($files as $file) {
                if(File::exists($this->path.$file->name)) {
                    unlink($this->path.$file->name);
                }
                $fileHospital = FileHospital::find($file->id);
                $fileHospital->delete();
            }
            $destroy = $this->data->destroy($id);
            return $this->onSuccess($this->type, $data, 'Destroyed');
        } catch (\Exception $e) {
            return $this->onError($e);
        }
    }
}
