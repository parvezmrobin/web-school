<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

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

        $res = DB::table($this->table)
        ->join('class_section_year', 'class_section_year_id', 'class_section_year.id')
        ->join('classes', 'class_id', 'classes.id')
        ->join('sections', 'section_id', 'sections.id')
        ->join('years', 'year_id', 'years.id')
        ->join('terms', 'term_id', 'terms.id')
        ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'percentage', 'class_section_year_term.id')
        ->where($conds)->orderByRaw('year_id, class_id, section_id, term_id')->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            // If already exsists return error
            $res = DB::table($this->table)
            ->where('class_section_year_id', $request->input('csy'))
            ->where('term_id', $request->input('term'))
            ->first();
            if (isset($res->id)) {
                return response()->json(["status" => "Already exsists"], 500);
            }
            // else create a new entry
            $vals = [
                'class_section_year_id' => $request->input('csy'),
                'term_id' => $request->input('term'),
                'percentage' => $request->input('percentage'),
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ];
            $id = DB::table($this->table)->insertGetId($vals);
            $res = DB::table($this->table)
            ->where('id', $id)
            ->first();
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

    public function delete(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            DB::table($this->table)->where('id', $request->input('id'))->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
