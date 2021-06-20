<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'document',
        'id_specialty',
    ];

    public function specialty(){
        return $this->belongsTo('App\Models\Specialty', 'id_specialty', 'id');
    }

    public function appointments(){
        return $this->hasMany('App\Models\Appointment', 'id_doctor', 'id');
    }

}
