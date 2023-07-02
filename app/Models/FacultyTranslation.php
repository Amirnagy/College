<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['id','locale','faculty_id','name'];
    public $timestamps = false;
}
