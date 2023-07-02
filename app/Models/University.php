<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class University extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    public $translatedAttributes = ['name'];
    protected $fillable = ['id','name'];


    public function faculty() {
        return $this->hasMany(Faculty::class, 'university_id', 'id');
    }

    public function department() {
        return $this->hasMany(Department::class, 'university_id', 'id');
    }

}
