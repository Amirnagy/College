<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\University;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Department extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'id',
        'university_id',
        'faculty_id'
    ];

    public function faculty() {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }


    public function university() {
        return $this->belongsTo(University::class, 'university_id', 'id');
    }

}
