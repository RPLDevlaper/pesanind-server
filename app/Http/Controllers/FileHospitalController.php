<?php

namespace App\Http\Controllers;

use App\Models\FileHospital;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class FileHospitalController extends Controller
{

    public $path;
    public $type;
    public $dimen;
    public $data;

    public function __construct(FileHospital $data)
    {
        $this->data = $data;
        $this->path = public_path().'/img/hospital/';
        $this->dimen = 750;
        $this->type = 'File Hospital';
    }

    public function index(Request $request)
    {
        $query = $this->data->query();
        if($request->get('name') != null && $request->get('name')) {
            $data = $query->where('name', $request->get('name'));
        }
        if($request->get('hospital') != null && $request->get('hospital')) {
            $name = $request->get('hospital');
            $data = $this->data->with('Hospital')->whereHas('Hospital', function($query) use ($name) {
                $query->where('name', 'LIKE', '%'.$name.'%');
            });
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
            $data = new FileHospital();
            $hospital = Hospital::find($request->hospital);
            $file = $request->file('picture');
            $fileName = str_replace(' ', '_', $hospital->name).time().'-'.uniqid().'.'.$file->extension();
            $img = Image::make($file->path());
            if(!File::isDirectory($this->path)) {
                File::makeDirectory($this->path, 0777, true);
            }
            $img->resize($this->dimen, $this->dimen, function($constraint) {
                $constraint->aspectRatio();
            })->save($this->path.$fileName);
            $data->name = $fileName;
            $data->type = "File/".$file->extension();
            $data->hospital_id = $request->hospital;
            $data->save();
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
            $data = FileHospital::find($id);
            $hospital = Hospital::find($request->hospital);
            $file = $request->file('picture');
            $fileName = str_replace(' ', '_', $hospital->name).time().'-'.uniqid().'.'.$file->extension();
            $img = Image::make($file->path());
            if(!File::isDirectory($this->path)) {
                File::makeDirectory($this->path, 0777, true);
            }
            if(File::exists($this->path.$data->name)) {
                unlink($this->path.$data->name);
            }
            $img->resize($this->dimen, $this->dimen, function($constraint) {
                $constraint->aspectRatio();
            })->save($this->path.$fileName);
            $data->name = $fileName;
            $data->type = "File/".$file->extension();
            $data->save();
            return $this->onSuccess($this->type, $data, 'Updated');
        } catch (\Exception $e) {
            return $this->onError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->data->find($id);
            if(File::exists($this->path.$data->name)) {
                unlink($this->path.$data->name);
            }
            $destroy = $this->data->destroy($id);
            return $this->onSuccess($this->type, $data, 'Deleted');
        } catch (\Exception $e) {
            return $this->onError($e);
        }
    }
}
