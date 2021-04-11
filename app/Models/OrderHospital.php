<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHospital extends Model
{
    use HasFactory;

    protected $table = 'order_hospital';

    protected $fillable = ['user_id', 'hospital_id', 'time', 'number', 'code', 'status'];

    public function Hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
