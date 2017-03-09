<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

use App\AggregateImpose;
use App\AggregatePayment;
use App\IndividualTransaction;

class TransactionController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $perPage = $request->input('pp');
            if(!$perPage)
                $perPage = 15;

            $conds = [];
            if($request->input('class')){
                $conds['class_section_year.class_id'] = $request->input('class');
            }
            if($request->input('section')){
                $conds['class_section_year.section_id'] = $request->input('section');
            }
            if ($request->input('sr_id')) {
                $conds['student_roll_id'] = $request->input('sr_id');
            }

            if($request->input('from')){
                $from = $request->input('from');
            }else {
                $from = Carbon::minValue();
            }
            if($request->input('to')){
                $to = $request->input('to');
            }else {
                $to = Cabon::maxValue();
            }

            if($request->input('receivers[]')){
                $receivers = $request->input('receivers[]');
            }else {
                $receivers = [];
            }


            $selects = [
                'class',
                'section',
                'year',
                'class_section_year_id',
                'imposer_id',
                'receiver_id',
                'ammount',
                'student_roll_id',
                'aggregate_payments. created_at as created_at',
                'aggregate_payments.updated_at as updated_at',
            ];

            $agrregate_payments = DB::table('aggregate_payments')
                ->join('aggregate_imposes', 'aggregate_imposes.id', 'aggregate_payments.aggregate_impose_id')
                ->join('class_section_year', 'class_section_year.id', 'aggregate_imposes.class_section_year_id')
                ->where($conds)
                ->whereBetween('aggregate_payments.created_at', [$from, $to])
                ->whereIn('aggregate_payments.receiver_id', $receivers)
                ->select($selects)
                ->latest()->get();

            $individual_transactions = DB::table('individual_transactions')
                ->join('student_role', 'student_role.id', 'individual_transactions.student_roll_id')
                ->join('class_section_year', 'class_section_year.id', 'student_role.class_section_year_id')
                ->where($conds)
                ->whereBetween('created_at', [$from, $to])
                ->whereIn('receiver_id', $receivers)
                ->select($selects)
                ->latest()->get();

            $offset = $request->input('offset');
            $res = $aggregate_payments
                    ->combine($individual_transactions)
                    ->sortByDesc('updated_at')
                    ->slice($offset*$perPage, $perPage);
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function recentAggregateImposes(Request $request, $num = 5)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $imposes = DB::table('aggregate_imposes')
                ->latest()
                ->take($num)
                ->get();
            return response()->json($imposes);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function history(Request $request, $perPage = 15)
    {
        if(Auth::user()->isInRole('student')){
            $studentId = \App\Student::where('user_id', $request->user()->id)
                ->first()->id;
        }else if(Auth::user()->isInRole(['admin', 'editor'])){
            $studentId = $request->input('student');
        }else {
            return response()->json(["status"=>"Unauthorized"], 403);
        }

        $res = DB::table('individual_transactions')
            ->join('student_roll', 'individual_transactions.student_roll_id', 'student_roll.id')
            ->where('student_id', $studentId)
            ->latest()
            ->offset($request->input('offset') * $perPage)
            ->take($perPage)
            ->get();
    }

    public function aggregateImpose(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $ai = new aggregateImpose;
            $ai->class_section_year_id = $request->input('csy');
            $ai->transaction_type_id = $request->input('type');
            $ai->detail = $request->input('detail');
            $ai->ammount = $request->input('ammount');
            $ai->imposer_id = $user->id;
            $ai->save();

            return response()->json($ai);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function updateAggregateImpose(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $ai = AggregateImpose::find($id);
            $ai->class_section_year_id = $request->input('csy');
            $ai->transaction_type_id = $request->input('type');
            $ai->detail = $request->input('detail');
            $ai->ammount = $request->input('ammount');
            $ai->save();
            return response()->json($ai);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function removeAggregateImpose(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $ai = AggregateImpose::find($id);
            $ai->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function aggregatePay(Request $request)
    {
        $user = $request->user();
        $csy = AggregateImpose::find($request->input('ai'))->classSectionYear;

        if ($user->can('transact', $csy)) {
            $ap = new AggregatePayment;
            $ap->aggregate_impose_id = $request->input('ai');
            $ap->student_roll_id = $request->input('sr_id');
            $ap->receiver_id = $user->id;
            $ap->save();

            return response()->json($ap);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function removeAggregatePay(Request $request, $id)
    {
        $ap = AggregatePayment::find($id);
        $user = $request->user();
        if($user->isInRole('admin') || $user->id === $ap->receiver_id){
            $ap->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function individualImpose(Request $request)
    {
        $csy = \App\StudentRole::find($request->input('sr_id'))->classSectionYear;
        $user = $request->user();

        if ($user->can('transact', $csy)) {
            $it = new IndividualTransaction;
            $it->student_roll_id = $request->input('sr_id');
            $it->transaction_type_id = $request->input('type');
            $it->detail = $request->input('detail');
            $it->ammount = $request->input('ammount');
            $it->is_paid = 0;
            $it->imposer_id = $user->id;
            $it->save();

            return response()->json($it);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function individualPay(Request $request, $id)
    {
        $it = IndividualTransaction::find($id);
        $csy = $id->studentRoll->classSectionYear;
        $user = $request->user();

        if ($user->can('transact', $csy)) {

            $it->is_paid = 1;
            $it->receiver_id = $user->id;
            $it->save();

            return response()->json($id);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function individualUnpay(Request $request, $id)
    {
        $it = IndividualTransaction::find($id);
        $csy = $id->studentRoll->classSectionYear;
        $user = $request->user();

        if ($user->can('transact', $csy)) {

            $it->is_paid = 0;
            $it->receiver_id = NULL;
            $it->save();

            return response()->json($id);
        }

        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
