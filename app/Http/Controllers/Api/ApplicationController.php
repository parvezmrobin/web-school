<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Application;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $forStudent = $request->input('for_student');
        $cond = [['deadline', '>=', new Carbon]];
        if ($forStudent !==null) {
            array_push($cond, ['for_student', $forStudent]);
        }
        $apps = Application::where($cond)->latest()->get();

        return response()->json($apps);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])) {
            $this->validate($request, [
                'title' => 'bail|required|max:250',
                'detail' => 'bail|required|max:65500',
                'for_student' => 'bail|required|boolean',
                'info' => 'bail|required|max:65500',
                'notice' => 'bail|required|max:250',
                'deadline' => 'bail|required|after:now',
            ]);

            $app = new Application;
            $app->user_id = $user->id;
            $app->title = $request->input('title');
            $app->detail = $request->input('detail');
            $app->for_student = $request->input('for_student');
            $app->info = $request->input('info');
            $app->notice = $request->input('notice');
            $app->deadline = $request->input('deadline');
            $app->save();
            return response()->json($app);
        }

    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $app = Application::find($id);

        if($user->isInRole('admin') || $user->id === $app->user_id){
            $app->title = $request->input('title');
            $app->detail = $request->input('detail');
            $app->for_student = $request->input('for_student');
            $app->info = $request->input('info');
            $app->notice = $request->input('notice');
            $app->deadline = $request->input('deadline');
            $app->save();
            return response()->json($app);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $app = Application::find($id);
        $user = $request->user();
        if($user->isInRole('admin' || $user->id === $app->user_id)){
            $app->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
