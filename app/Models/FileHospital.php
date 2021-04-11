<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileHospital extends Model
{
    use HasFactory;

    protected $table = 'file_hospital';

    protected $fillable = ['name', 'hospital_id', 'type'];

    public function Hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
