<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
    	'uploaded_at',
    	'viewed_at',
    	'sent_at',
    	'discharged_at',
    	'created_at',
    	'updated_at',
    	'deleted_at'
    ];

    protected $fillable = [
        'path',
		'code',
		'extension',
		'status',
		'uploaded_at',
		'viewed_at',
		'sent_at',
		'discharged_at'
    ];

}
