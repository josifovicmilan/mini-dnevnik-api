<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\PrimarySchoolData;
use App\Http\Requests\StorePrimarySchoolRequest;
use App\Http\Requests\UpdatePrimarySchoolRequest;
use App\Http\Controllers\Controller;
class StudentPrimarySchoolDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrimarySchoolRequest $request, Student $student)
    {
        $student->primarySchool()->create($request->validated());

        return response($student->primarySchool);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrimarySchoolRequest $request, Student $student)
    {
        $student->primarySchool()->update($request->validated());

        return response($student, 201);
    }

}
