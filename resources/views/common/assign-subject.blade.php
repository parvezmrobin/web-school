<!DOCTYPE html>
@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="/css/site.css">
@endsection

@section('content')
    <div id="vm" class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="year" class="col-md-4 control-label">Year</label>
                    <div class="col-md-8">
                        <select class="form-control" id="year" v-model="year">
                            <option v-for="year in years" :value="year.year_id">@{{year.year}}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="class" class="col-md-4 control-label">Class</label>
                    <div class="col-md-8">
                        <select class="form-control" id="class" v-model="classs">
                            <option v-for="cls in classes" :value="cls.class_id">@{{cls.classs}}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="section" class="col-md-4 control-label">Section</label>
                    <div class="col-md-8">
                        <select class="form-control" id="section" v-model="section">
                            <option v-for="section in sections" :value="section.section_id">@{{section.section}}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="button" class="btn btn-primary" @click="loadStudents">
                            Load
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-horizontal">
            <div class="checkbox">
                <label><input type="checkbox" v-model="selectAll">Select All</label>
            </div>
            <div class="checkbox" v-for="std in studets">
                <label>
                    <input type="checkbox" v-model="selectedStudents">
                    @{{std.roll + ' - ' + std.first_name + ' ' + std.last_name}}
                </label>
            </div>
        </div>

        <div class="col-md-6 form-horizontal">
            <div class="checkbox">
                <label><input type="checkbox" v-model="selectAll">Select All</label>
            </div>
            <div v-for="std in subjects">
                <div class="checkbox">
                    <label class="checkbox-inline">
                        <input type="checkbox" v-model="selectedSubjects">
                        @{{subejct.subject_code + ' - ' + subject.subject}}
                    </label>
                    <label v-if="!subject.is_compulsory" class="checkbox-inline">
                    <input type="checkbox">
                    is compuslory
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <hr>
        <h2 class="text-center">Assignments</h2>
        <hr>

    </div>
@endsection
