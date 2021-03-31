<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

use App\Http\Controllers\Controller;
class ClassroomStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function index(Classroom $classroom)
    {
        return response($classroom->students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request, Classroom $classroom)
    {
       if(auth()->user()->isNot($classroom->user)){
           return response(['errors' => 'User cannot create a student for this classroom'], 403);
       }
        $student = $classroom->addStudent($request->validated() + ['classroom_id' => $classroom->id]);
        
        return response($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Classroom $classroom, Student $student)
    {
        $student->save($request->validated() + ['classroom_id' => $classroom->id]);

        return response($student, 201);
    }

}
