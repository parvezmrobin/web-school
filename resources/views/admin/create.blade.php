@extends('layouts.app')

@section('style')
    <style media="screen">
        .form-control, .btn, .panel, h2 {
            border-radius: 1px;
        }

        h2 {
            margin-top: 0px;
        }

        .panel-default > .panel-heading {
            color: teal;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="vm" v-cloak xmlns:v-on="http://www.w3.org/1999/xhtml">
        <h2 class="text-center text-info">@{{status === '' ? 'Creation Center' : status}}</h2>
        <hr>
        <div class="col-md-8 col-md-offset-2">

            {{-- Year Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Year</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="number" name="year" placeholder="Year" class="form-control" v-model="year">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createYear" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>

            {{-- Class Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Class</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="class" placeholder="Class" class="form-control" v-model="classs">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createClass" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>

            {{-- Section Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Section</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="section" placeholder="Section" class="form-control" v-model="section">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createSection" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>

            {{-- Term Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Term</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="term" placeholder="Term" class="form-control" v-model="term">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createTerm" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>

            {{-- Subject Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Subject</h2>
                <div class="form-group panel-body">
                    <div class="col-md-2">
                        <input type="text" name="code" placeholder="Code" class="form-control" v-model="subjectCode">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="subject" placeholder="Name" class="form-control" v-model="subjectName">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="mark" placeholder="Mark" class="form-control" v-model="subjectMark">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createSubject" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>

            {{-- Portion Panel --}}
            <div class="panel panel-default">
                <h2 class="panel-heading">Create Subject Portion</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="portion" placeholder="Subject Portion" class="form-control"
                               v-model="portion">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createPortion" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function createVue(obj) {
            return new Vue(obj);
        }

        var app = createVue({
            el: '#vm',
            data: {
                year: '',
                classs: '',
                section: '',
                term: '',
                subjectCode: '',
                subjectName: '',
                subjectMark: '',
                portion: '',
                status: ''
            },
            methods: {
                loadFrom: (url, method, then) => {
                    axios.get('{{url("api/token")}}')
                        .then((response) => {
                            url = url + response.data.token;
                            switch (method) {
                                case 'get':
                                    axios.get(url).then(then);
                                    break;
                                case 'post':
                                    axios.post(url).then(then);
                                    break;
                                case 'put':
                                    axios.put(url).then(then);
                                    break;
                                case 'delete':
                                    axios.delete(url).then(then);
                                    break;
                                default:
                                    break;
                            }
                        });
                },
                createYear: () => {
                    var url = '../api/year?year=' + app.year + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Year ' + app.year + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
                createClass: () => {
                    var url = '../api/class?class=' + app.classs + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Class ' + app.classs + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
                createSection: () => {
                    var url = '../api/section?section=' + app.section + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Section ' + app.section + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
                createTerm: () => {
                    var url = '../api/term?term=' + app.term + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Term ' + app.term + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
                createSubject: () => {
                    var url = '../api/subject?subject=' + app.subjectName + '&code=' + app.subjectCode + '&mark=' + app.subjectMark + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Subject ' + app.subjectCode + ' - ' + app.subjectName + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
                createPortion: () => {
                    var url = '../api/portion?portion=' + app.portion + '&token=';
                    app.loadFrom(url, 'post', () => {
                        app.status = 'Portion ' + app.portion + ' added successfully';
                        window.scrollTo(0, 0);
                    });
                },
            }
        });
    </script>
@endsection
