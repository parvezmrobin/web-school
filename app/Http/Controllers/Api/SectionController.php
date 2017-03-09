<?php

namespace App\Http\Controllers\Api;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->isInRole('admin')){
            return response()->json(Section::all());
        }
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
            $sec = new Section;
            $sec->section = $request->input('section');
            $sec->save();
            return response()->json($sec);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->user()->isInRole('admin')){
            $sec = Section::find($id);
            $sec->section = $request->input('section');
            $sec->save();
            return response()->json($sec);;
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->user()->isInRole('admin')){
            $sec = Section::find($id);
            $sec->delete();
            return response()->json(["status" => "succeeded"]);;
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
