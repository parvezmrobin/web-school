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
        if($request->input('year')){
            $conds['year_id'] = $request->input('year');
        }
        if ($request->input('class')) {
            $conds['class_id'] = $request->input('class');
        }
        if ($request->input('section')) {
            $conds['section_id'] = $request->input('section');
        }

        $res = DB::table('class_section_year')->where($conds)->orderBy('year_id')->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $vals = [
                'year_id' => $request->input('year'),
                'class_id' => $request->input('class'),
                'section_id' => $request->input('section')
            ];
            //If this combination already exists return that instance
            if ( ! is_null($instance = DB::table('class_section_year')->where($vals)->first()))
            {
                return $instance;
            }

            $vals = array_merge($vals, ['created_at' => new Carbon, 'updated_at' => new Carbon]);
            $id = DB::table('class_section_year')->insertGetId($vals);
            return response()->json(DB::table('class_section_year')->where('id', $id)->first());
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $conds = [];
            if($request->input('year')){
                $conds['year_id'] = $request->input('year');
            }
            if ($request->input('class')) {
                $conds['class_id'] = $request->input('class');
            }
            if ($request->input('section')) {
                $conds['section_id'] = $request->input('section');
            }

            DB::table('class_section_year')->where('id', $id)->update($conds);
            $res = DB::table('class_section_year')->where('id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function controller(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            DB::table('class_section_year')->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
