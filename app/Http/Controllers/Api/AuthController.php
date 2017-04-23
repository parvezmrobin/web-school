<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function transIndex(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            $conds = [];
            if ($request->input('csy')){
                array_push($conds, ['class_section_year_id',$request->input('csy')]);
            } else{
                array_push($conds, ['class_id',$request->input('cid')]);
                array_push($conds, ['section_id',$request->input('sid')]);
                array_push($conds, ['year_id',$request->input('yid')]);
            }

            $res = DB::table('transaction_auth')
            ->join('class_section_year', 'class_section_year_id', 'class_section_year.id')
            ->join('users', 'editor_id', 'users.id')
            ->where($conds)
            ->select('transaction_auth.*', 'first_name', 'last_name')
            ->get();

            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function markIndex(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            $conds = [['subject_teacher_id', $request->input('st')]];
            if ($request->input('csy')){
                array_push($conds, ['class_section_year_id',$request->input('csy')]);
            } else{
                array_push($conds, ['class_id',$request->input('cid')]);
                array_push($conds, ['section_id',$request->input('sid')]);
                array_push($conds, ['year_id',$request->input('yid')]);
            }

            $res = DB::table('mark_auth')
            ->join('subject_teacher', 'subject_teacher_id', 'subject_teacher.id')
            ->join('class_section_year', 'class_section_year_id', 'class_section_year.id')
            ->join('users', 'editor_id', 'users.id')
            ->where($conds)
            ->select('mark_auth.*', 'first_name', 'last_name')
            ->get();

            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function markAuthStore(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            $id = DB::table('mark_auth')->insertGetId([
                'subject_teacher_id' => $request->input('st'),
                'editor_id' => $request->input('editor'),
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ]);
            $res = DB::table('mark_auth')->where('id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function markAuthRemove(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            DB::table('mark_auth')
            ->where('id', $id)
            ->delete();
            return response()->json(["stuatus" => "Succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
