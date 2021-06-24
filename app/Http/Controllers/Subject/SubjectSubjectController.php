<?php

namespace App\Http\Controllers\Subject;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectSubjectController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject1, Subject $subject2)
    {
        
        $subject1->updatePosition($subject2, Classroom::find($request->classroom_id)->first()->id);

        return response(['message' => 'Uspesno zamenjena mesta'], 201);
    }

}
