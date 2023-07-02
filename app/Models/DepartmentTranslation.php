<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['id','locale','department_id','name'];
    public $timestamps = false;
}
