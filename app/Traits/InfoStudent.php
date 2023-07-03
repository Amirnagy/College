<?php
namespace App\Traits;

use App\Models\Student;
use App\Models\University;

trait InfoStudent{

    function userDataUniversity($user_id,$lang)
    {
        // get all info
        $StudentData = Student::where('user_id', $user_id)->first();

        // get the univeristy
        $university = University::select('id')->where('id', $StudentData->university_id)
            ->with(['translations' => function ($query) use ($lang) {
                $query->select('university_id', 'name')
                    ->where('locale',$lang)->first();
            }])
        // load relation faculty
            ->with(['faculty' => function($query) use ($StudentData , $lang) {
                $query->select('id', 'university_id')->where('id', $StudentData->faculty_id)
                    ->with(['translations' => function ($query) use ($lang) {
                        $query->select('faculty_id', 'name')
                            ->where('locale',$lang)->first();
                    }])


                    ->with(['department' => function ($query) use ($StudentData,$lang) {
                        $query->select('id', 'faculty_id')->where('id', $StudentData->department_id)

                            ->with(['translations' => function ($query) use ($lang) {
                                $query->select('department_id', 'name')
                                    ->where('locale',$lang)->first();
                            }]);
                    }])

                    ->first();
            }])



            ->first();
        return $university;
    }

}
