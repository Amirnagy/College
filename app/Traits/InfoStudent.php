<?php
namespace App\Traits;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\University;
use Illuminate\Http\Request;

trait InfoStudent{

    public function userDataUniversity($user_id,$lang)
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

    public function checkchoose(Request $request) {

        $facultydata = Faculty::select('id')->where('id',$request->faculty)
            ->with(['department'=>function ($query) use ($request)
                {
                    $query->select('id','faculty_id')->where('id',$request->department)->first();
        }])->first();

        if ($facultydata && $facultydata->department->isNotEmpty()) {
                    return true;
                } else {
                    return false;
                }
    }


    
}
