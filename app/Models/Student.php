<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['id',
                           'user_id',
                           'university_id',
                           'faculty_id',
                           'department_id'];



}
