<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;


class ClassSectionYearController extends Controller
{
    public function index(Request $request)
    {
        $conds = [];
        if ($request->input('year')) {
            array_push($conds, ['year_id', $request->input('year')]);
        }
        if ($request->input('class')) {
            array_push($conds, ['class_id', $request->input('class')]);
        }
        if ($request->input('section')) {
            array_push($conds, ['section_id', $request->input('section')]);
        }

        $res = DB::table('class_section_year')
            ->join('classes', 'class_id', 'classes.id')
            ->join('sections', 'section_id', 'sections.id')
            ->join('years', 'year_id', 'years.id')
            ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'class_section_year.id')
            ->where($conds)
            ->orderByRaw('year_id, class_id, section_id')
            ->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->isInRole('admin')) {
            $vals = [
                'year_id' => $request->input('year'),
                'class_id' => $request->input('class'),
                'section_id' => $request->input('section')
            ];
            //If this combination already exists return that instance
            if (DB::table('class_section_year')->where($vals)->count()) {
                return response()->json(["status" => "Already Exists"], 400);
            }

            $vals = array_merge($vals, ['created_at' => new Carbon, 'updated_at' => new Carbon]);
            $id = DB::table('class_section_year')->insertGetId($vals);
            $res = DB::table('class_section_year')
                ->join('classes', 'class_id', 'classes.id')
                ->join('sections', 'section_id', 'sections.id')
                ->join('years', 'year_id', 'years.id')
                ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'class_section_year.id')
                ->where('class_section_year.id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->isInRole('admin')) {
            $conds = [];
            if ($request->input('year')) {
                $conds['year_id'] = $request->input('year');
            }
            if ($request->input('class')) {
                $conds['class_id'] = $request->input('class');
            }
            if ($request->input('section')) {
                $conds['section_id'] = $request->input('section');
            }

            DB::table('class_section_year')->where('id', $id)->update($conds);
            $res = DB::table('class_section_year')
                ->join('classes', 'class_id', 'classes.id')
                ->join('sections', 'section_id', 'sections.id')
                ->join('years', 'year_id', 'years.id')
                ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'class_section_year.id')
                ->where('class_section_year.id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if ($user->isInRole('admin')) {
            DB::table('class_section_year')->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
}
