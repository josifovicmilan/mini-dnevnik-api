<?php

namespace App\Http\Controllers\Classroom;

use App\Models\Classroom;

use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Http\Controllers\Controller;
class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Classroom::class);
        return response(auth()->user()->classrooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClassroomRequest $request)
    {
        $this->authorize('create', Classroom::class);
        $classroom = auth()->user()->classrooms()->create($request->validated());

        return response()->json($classroom);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        
        $this->authorize('view', $classroom);
        return response($classroom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        if(auth()->user()->cannot('update', $classroom)){
            return response(403);
        }
       
        auth()->user()->classrooms()->update($request->validated());

        return response($classroom, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        // if(auth()->user()->cannot('update', $classroom)){
        //     return response(['error' => 'User cannot delete classroom that doesnt belong to him'], 403);
        // }   
        $classroom->delete();

        return response($classroom, 200);
    }
}
