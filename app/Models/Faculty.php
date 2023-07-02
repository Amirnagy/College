<?php

namespace App\Models;


use App\Models\Department;
use App\Models\University;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Faculty extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'id',
        'name',
        'university_id',
    ];

    public function department() {
        return $this->hasMany(Department::class, 'faculty_id', 'id');
    }


    public function university() {
        return $this->belongsTo(University::class, 'university_id', 'id');
    }


}
