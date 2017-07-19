<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentRollController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $conds = [];
        if($user->isInRole(['admin', 'editor', 'teacher'])){
            if ($request->input('class')) {
              array_push($conds, ['class_id', $request->input('class')]);
            }
            if ($request->input('section')) {
                array_push($conds, ['section_id', $request->input('section')]);
            }
            if ($request->input('csy')) {
                array_push($conds, ['class_section_year_id', $request->input('csy')]);
            }
            if ($request->input('year')) {
                array_push($conds, ['year_id', $request->input('year')]);
            }
            $res = \App\StudentRoll::join('class_section_year', 'class_section_year.id', 'class_section_year_id')
            ->join('classes', 'class_id', 'classes.id')
            ->join('sections', 'section_id', 'sections.id')
            ->join('years', 'year_id', 'years.id')
            ->where($conds)
            ->select([
              'student_roll.*',
              'class_id',
              'class',
              'section_id',
              'section',
              'year_id',
              'year',
              'class_section_year_id'
            ])
            ->get();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor', 'teacher'])){
            $sr = new \App\StudentRoll;
            $sr->student_id = $request->input('sid');
            $sr->class_section_year_id = $request->input('csyid');
            $sr->roll = $request->input('roll');
            $sr->save();
            return response()->json($sr);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->isInRole(['admin', 'editor', 'teacher'])){
            $sr = \App\StudentRoll::find($id);
            if($request->input('sid')){$sr->student_id = $request->input('sid');}
            if($request->input('csyid')){$sr->class_section_year_id = $request->input('csyid');}
            if($request->input('roll')){$sr->roll = $request->input('roll');}
            $sr->save();
            return response()->json($sr);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->isInRole('admin')){
            $year = \App\StudentRoll::find($id);
            $year->delete();
            return response()->json(["status" => "succeeded"]);;
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
