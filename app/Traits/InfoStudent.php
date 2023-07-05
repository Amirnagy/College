<?php
namespace App\Traits;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\University;

trait InfoStudent{

    // function userDataUniversity($user_id,$lang)
    // {
    //     // get all info
    //     $StudentData = Student::where('user_id', $user_id)->first();

    //     // get the univeristy
    //     $university = University::select('id')->where('id', $StudentData->university_id)
    //         ->with(['translations' => function ($query) use ($lang) {
    //             $query->select('university_id', 'name')
    //                 ->where('locale',$lang)->first();
    //         }])
    //     // load relation faculty
    //         ->with(['faculty' => function($query) use ($StudentData , $lang) {
    //             $query->select('id', 'university_id')->where('id', $StudentData->faculty_id)
    //                 ->with(['translations' => function ($query) use ($lang) {
    //                     $query->select('faculty_id', 'name')
    //                         ->where('locale',$lang)->first();
    //                 }])
    //                 ->with(['department' => function ($query) use ($StudentData,$lang) {
    //                     $query->select('id', 'faculty_id')->where('id', $StudentData->department_id)
    //                         ->with(['translations' => function ($query) use ($lang) {
    //                             $query->select('department_id', 'name')
    //                                 ->where('locale',$lang)->first();
    //                         }]);
    //                 }])
    //                 ->first();
    //         }])
    //         ->first();

    //     return $university;
    // }
    function userDataUniversity($user_id,$lang)
    {
        // get all info
        $StudentData = Student::where('user_id', $user_id)->first();
        $university_id = $StudentData->university_id;
        $faculty_id = $StudentData->faculty_id;
        $department_id = $StudentData->department_id;
        // get the univeristy
        $university = University::select('id')->where('id', $university_id)
            ->with(['translations' => function ($query) use ($lang) {
                $query->select('university_id', 'name')
                    ->where('locale',$lang)->first();
            }])->first();
        $faculty = Faculty::select('id')->where('id',$faculty_id)
            ->with(['translations' => function ($query) use ($lang) {
                $query->select('faculty_id', 'name')
                    ->where('locale',$lang)->first();
            }])->first();
        $department = Department::select('id')->where('id', $department_id)
            ->with(['translations' => function ($query) use ($lang) {
                $query->select('department_id', 'name')
                    ->where('locale',$lang)->first();
            }])->first();

            $data = [
            "university_id" => $university_id,
            "univeristy" => $university->translations[0]->name,
            "faculty_id" => $faculty_id,
            "faculty" => $faculty->translations[0]->name,
            "department_id" => $department_id,
            "department" => $department->translations[0]->name,
        ];
        return $data;
    }
}
