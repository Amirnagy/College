<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['id','locale','university_id','name'];
    public $timestamps = false;
}
