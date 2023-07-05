<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpRest extends Model
{
    use HasFactory;


    protected $fillable=[
        'id',
        'identifier',
        'token',
        'expired',
        'no_times_attempted',
        'generated_at'
    ];

    public $timestamps = false;
}
