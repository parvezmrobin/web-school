<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            return response()->json(Year::all());
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
        if($user->isInRole('admin')){
            $year = new Year;
            if($request->input('year')){$year->year = $request->input('year');}
            $year->save();
            return response()->json($year);
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
        if($request->user()->isInRole('admin')){
            $year = Year::find($id);
            $year->year = $request->input('year');
            $year->save();
            return response()->json($year);
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
            $year = Year::find($id);
            $year->delete();
            return response()->json(["status" => "succeeded"]);;
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
