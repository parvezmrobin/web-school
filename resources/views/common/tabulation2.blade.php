@extends('layouts.app')

@section('content')
    <div class="row">
        <h2 class="text-center">Tabulation</h2>
        <hr>
        <form method="get" action="tabulation2" class="col-md-8 col-md-offset-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="year" class="col-md-4 control-label">Year</label>
                    <div class="col-md-8">
                        <select class="form-control" id="year" name="year">
                            @foreach(\App\Year::all() as $year)
                                <option value="{{$year->id}}">{{$year->year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="class" class="col-md-4 control-label">Class</label>
                    <div class="col-md-8">
                        <select class="form-control" id="class" name="class">
                            @foreach(\App\Classs::all() as $cls)
                                <option value="{{$cls->id}}">{{$cls->class}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="section" class="col-md-4 control-label">Section</label>
                    <div class="col-md-8">
                        <select class="form-control" id="section" name="section">
                            @foreach(\App\Section::all() as $section)
                                <option value="{{$section->id}}">{{$section->section}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <input type="submit" class="btn btn-primary" value="Load">
                    </div>
                </div>

            </div>
        </form>

        <!--Tabulation Starts-->
        @if(isset($classSectionYear) && isset($classSectionYear->id))
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th rowspan="3">New Roll</th>
                        <th rowspan="3">Old Roll</th>
                        <th rowspan="3" style="min-width:150px">Name</th>
                        @foreach($classSectionYear->subjectTeachers as $subjectTeacher)
                            <th colspan="{{$classSectionYear->classSectionYearTerms()->count() * 3 + 1}}">
                                {{$subjectTeacher->subject->subject}}
                            </th>
                        @endforeach
                        <th rowspan="3">GPA</th>
                    </tr>
                    <tr>
                        @foreach($classSectionYear->subjectTeachers as $subjectTeacher)
                            @foreach($classSectionYear->classSectionYearTerms as $classSectionYearTerm)
                                <th colspan="3">
                                    {{$classSectionYearTerm->term->term .
                                    ' (' . $classSectionYearTerm->percentage . '%)'}}
                                </th>
                            @endforeach
                            <th rowspan="2">Subject Grade</th>

                        @endforeach
                    </tr>
                    <tr>
                        @foreach($classSectionYear->subjectTeachers as $subjectTeacher)
                            @foreach($classSectionYear->classSectionYearTerms as $classSectionYearTerms)
                                <th>Mark</th>
                                <th>Grade</th>
                                <th>Point</th>
                            @endforeach
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classSectionYear->studentRolls as $studentRoll)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$studentRoll->roll}}</td>
                            <td>{{$studentRoll->student->user->first_name + $studentRoll->student->user->last_name}}</td>
                            @foreach($studentRoll->subjectTeacherStudents as $subjectTeacherStudent)
                                @foreach($subjectTeacherStudent->marks as $mark)
                                    <td>
                                        @if($mark->mark == -2)
                                            N/A
                                        @elseif($mark->mark == -1)
                                            A
                                        @else
                                            {{$mark->mark}}
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                            <td>GPA</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            No data found
    @endif
    <!--Tabulation Ends-->
    </div>
@endsection