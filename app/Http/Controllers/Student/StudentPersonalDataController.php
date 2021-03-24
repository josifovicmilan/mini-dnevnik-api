<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\PersonalData;
use Illuminate\Http\Request;
use App\Http\Requests\StorePersonalDataRequest;
use App\Http\Controllers\Controller;
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
        $student->personalData()->create($request->validated());

        return response($student);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @param  \App\Models\PersonalData  $personalData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student, PersonalData $personalData)
    {
        //
    }


}
