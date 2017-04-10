<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Response;
use App\Application;

class ResponseController extends Controller
{
    public static $perPage = 15;

    public function index(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])){
            $responses = Response::where([
                'application_id' => $request->input('application'),
                ])->offset($request->input('page') * $perPage)
                ->take($perPage)
                ->latest()->get();
                return response()->json($responses);
            }

            return response()->json(["status"=>"Unauthorized"], 403);
        }

        public function store(Request $request)
        {
            $this->validate(
                $request, [
                'first_name' => 'bail|required|max:250',
                'last_name' => 'bail|required|max:250',
                'fathers_name' => 'bail|required|max:250',
                'mothers_name' => 'bail|required|max:250',
                'sex' => 'bail|required',
                'birth_date' => 'bail|required',
                'contact' => 'bail|required',
                'address' => 'bail|required|max:250',
                'email' => 'bail|max:250',
            ]
        );

        if(Application::find($id)->for_student){
            $info = [
                'guardian_occupation' => $request->input('guardian_occupation'),
                'guardian_occupation_detail' => $request->input('guardian_occupation_detail'),
            ];
        } else {
            $info = [
                'designation_id' => $request->input('designation'),
                'qualification' => $request->input('qualification'),
            ];
        }

        $vals = $request->all();
        $resp = new Response;
        $resp->first_name = $vals['first_name'];
        $resp->last_name = $vals['last_name'];
        $resp->fathers_name = $vals['fathers_name'];
        $resp->mothers_name = $vals['mothers_name'];
        $resp->sex_id = $vals['sex'];
        $resp->birth_date = $vals['birth_date'];
        $resp->address = $vals['address'];
        $resp->email = $vals['email'];
        $resp->contact_no = $vals['contact'];
        $resp->info = $info;
        $resp->save();

        return response()->json($resp);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])){
            Response::where('id', $id)->delete();
            return response()->json(['status' => 'succeeded']);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
