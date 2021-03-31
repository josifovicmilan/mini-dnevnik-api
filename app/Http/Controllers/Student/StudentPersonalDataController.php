<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonalDataRequest;
use App\Http\Requests\UpdateStudentPersonalDataRequest;

class StudentPersonalDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student)
    {
        $this->authorize('update', $student);
        return response($student->personalData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonalDataRequest $request, Student $student)
    {
        $this->authorize('update', $student);
        $student->savePersonalData($request->validated());

        return response($student->personalData);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentPersonalDataRequest $request, Student $student)
    {
        $this->authorize('update', $student);

        $student->updatePersonalData($request->validated());

        return response($student->personalData, 201);
    }


}
