<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Classs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->isInRole('admin')){
            return response()->json(Classs::all());
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->user()->isInRole('admin')){
            $cls = new Classs;
            $cls->class = $request->input('class');
            $cls->save();
            return response()->json($cls);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Class  $class
     * @return \Illuminate\Http\Response
     */
    public function show(Classs $class)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Class  $class
     * @return \Illuminate\Http\Response
     */
    public function edit(Classs $cls)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Class  $class
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->isInRole('admin')){
            $cls = Classs::find($id);
            $cls->class = $request->input('class');
            $cls->save();
            return response()->json($cls);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Class  $class
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->isInRole('admin')){
            $cls = Classs::find($id);
            $cls->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
