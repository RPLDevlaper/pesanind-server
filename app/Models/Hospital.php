<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $table = 'hospital';

    protected $fillable = ['name', 'phone', 'lng', 'lat', 'description'];

    public function File()
    {
        return $this->hasMany(FileHospital::class);
    }

    public function Order()
    {
        return $this->hasMany(OrderHospital::class);
    }
}
