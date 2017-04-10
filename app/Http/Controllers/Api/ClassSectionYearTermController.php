<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ClassSectionYearTermController extends Controller
{
    private $table = "class_section_year_term";

    public function index(Request $request)
    {
        $conds = [];

        if ($request->input('csy')) {
            $conds['class_section_year_id'] = $request->input('csy');
        }
        if ($request->input('term')) {
            $cond['term_id'] = $request->input('term');
        }

        $res = DB::table($table)
        ->join('classes', 'class_id', 'classes.id')
        ->join('sections', 'section_id', 'sections.id')
        ->join('years', 'year_id', 'years.id')
        ->join('terms', 'term_id', 'terms.id')
        ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'class_section_year_term.id')
        ->where($conds)->orderByRaw('year_id, class_id, section_id, term_id')->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $vals = [
                'class_section_year_id' => $request->input('csy'),
                'term_id' => $request->input('term'),
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ];
            $id = DB::table($table)->insertGetId($vals);
            $res = DB::table($table)
            ->join('classes', 'class_id', 'classes.id')
            ->join('sections', 'section_id', 'sections.id')
            ->join('years', 'year_id', 'years.id')
            ->join('terms', 'term_id', 'terms.id')
            ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'class_section_year_term.id')
            ->where('id', $id)->orderByRaw('year_id, class_id, section_id, term_id')->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $conds = [];

            if ($request->input('csy')) {
                $conds['class_section_year_id'] = $request->input('csy');
            }
            if ($request->input('term')) {
                $cond['term_id'] = $request->input('term');
            }

            DB::table($table)->where('id', $id)->update($conds);
            $res = DB::table($table)
            ->join('classes', 'class_id', 'classes.id')
            ->join('sections', 'section_id', 'sections.id')
            ->join('years', 'year_id', 'years.id')
            ->join('terms', 'term_id', 'terms.id')
            ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'class_section_year_term.id')
            ->where('id', $id)->orderByRaw('year_id, class_id, section_id, term_id')->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            DB::table($table)->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
