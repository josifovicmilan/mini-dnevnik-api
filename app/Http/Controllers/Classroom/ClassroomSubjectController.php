<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\ClassroomResource;


class ClassroomSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function index(Classroom $classroom)
    {
        $this->authorize('update', $classroom);
        return new ClassroomResource($classroom->load('subjects.positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Classroom $classroom)
    {
        $this->authorize('update', $classroom);
        
        $subject = Subject::find($request->subject["id"]);
        $classroom->addSubject($subject);

        return response($subject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom, Subject $subject)
    {
        $this->authorize('update', $classroom);
        $classroom->removeSubject($subject);

        return response($classroom);
    }
}
