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

        $res = DB::table($table)->where($conds)->orderBy('term_id')->get();
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
            $res = DB::table($table)->where('id', $id);
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
            $res = DB::table($table)->where('id', $id)->first();
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
