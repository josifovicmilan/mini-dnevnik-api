<?php

namespace App\Http\Controllers\Student;

use App\Models\Mark;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Resources\StudentResource;
use App\Http\Requests\UpdateMarkRequest;
use App\Http\Requests\StoreManyMarksRequest;

class StudentMarkController extends Controller
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
        return new StudentResource($student->load('marks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarkRequest $request, Student $student)
    {
        $this->authorize('update', $student);

        $data = $request->validated();

        $student->grade($data['subject_id'], $data['mark'], $data['degree']);

        return new StudentResource($student->load('marks'));
    }

    public function storeMany(StoreManyMarksRequest $request, Student $student){
       
        $this->authorize('update', $student);
        
        $marks = collect($request->validated())->map(function($item){
            return (object)$item;
        });
        $student->gradeMany($marks);
        return response($student->marks);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student, Mark $mark)
    {
        //
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarkRequest $request, Student $student, Mark $mark)
    {
        $this->authorize('update', $student);

        $data = $request->validated();

        $student->updateGrade($data['subject_id'], $data['mark'], $data['degree']);

        return response($student->marks, 201);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @param  \App\Models\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student, Mark $mark)
    {
        //
    }
}
