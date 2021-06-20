<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'id_doctor',
        'id_patient',
        'date',
        'hour',
    ];

    public function patient(){
        return $this->belongsTo('App\Models\Patient', 'id_patient', 'id');
    }

    public function doctor(){
        return $this->belongsTo('App\Models\Doctor', 'id_doctor', 'id');
    }
}
