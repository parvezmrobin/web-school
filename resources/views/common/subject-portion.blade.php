@extends('layouts.app')

@section('style')
    <style media="screen">
        .panel {
            border: none;
        }

        .form-control, .alert {
            max-width: 280px;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="vm" v-cloak xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row">
            <!--Select Scope Starts-->
            <div class="panel panel-info col-md-5">
                <h2 class="panel-heading text-center">Select Scope</h2>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label for="year" class="control-label col-sm-3">Year</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="year" v-model="year">
                                <option v-for="year in years" :value="year.year_id">@{{year.year}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="class" class="control-label col-sm-3">Class</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="class" v-model="classs">
                                <option v-for="cls in classes" :value="cls.class_id">@{{cls.classs}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="section" class="control-label col-sm-3">Section</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="section" v-model="section">
                                <option v-for="section in sections"
                                        :value="section.section_id">@{{section.section}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="st" class="control-label col-sm-3">Subject</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="st" v-model="st">
                                <option v-for="st in sts" :value="st.id">
                                    @{{st.subject_code + ' - ' + st.subject + ' (' + st.first_name + ' ' + st.last_name + ')'}}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <!--Select Scope Ends-->

            <!--Summary Starts-->
            <div class="panel panel-info col-md-7">
                <h2 class="panel-heading text-center">Summary</h2>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <th>Portion</th>
                        <th>Percentage</th>
                        <th></th>
                        </thead>
                        <tbody>
                        <tr v-for="portion in subjectTeacherPortions">
                            <td>@{{portion.portion}}</td>
                            <td>@{{portion.percentage}}</td>
                            <td>
                                <button class="btn btn-danger" v-on:click="remove(portion.id)" title="Remove">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Summary Ends-->

            <!--Add Portion Starts-->
            <div class="panel panel-primary col-md-8 col-md-offset-2">
                <h2 class="panel-heading text-center">Add Portion</h2>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label for="portion" class="control-label col-sm-4">Portion</label>
                        <div class="col-md-5 col-sm-6">
                            <select class="form-control" id="portion" v-model="portion">
                                <option v-for="portion in portions" :value="portion.id">@{{portion.portion}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 col-sm-offset-4 col-md-offset-0">
                            <input type="number" class="form-control" v-model="percentage" placeholder="Percentage"
                                   v-on:blur="checkPercentage">
                        </div>
                    </div>
                    <div class="form-group" v-if="error !== ''">
                        <div class="col-md-8 col-sm-offset-4">
                            <p class="alert alert-danger">@{{error}}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-sm-offset-4">
                            <button type="button" class="btn btn-primary" v-on:click="addPortion">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Add Portion Ends-->
        </div>

        <!--Create Portion Starts-->
        <div class="row">
            <h2 class="text-center" v-if="status.length">@{{ status }}</h2>
            {{-- Portion Panel --}}
            <div class="panel panel-warning col-md-8 col-md-offset-2">
                <h2 class="panel-heading">Create Subject Portion</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="portion" placeholder="Subject Portion" class="form-control"
                               v-model="newPortion">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createPortion" class="btn btn-warning">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Create Portion Ends-->
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        const app = new Vue({
            el: '#vm',
            data: {
                portions: [],
                subjectTeacherPortions: [],
                subjectTeachers: [],
                portion: '',
                percentage: '',
                year: '',
                classs: '',
                section: '',
                st: '',
                error: '',
                newPortion: '',
                status: ''
            },
            computed: {
                years: function () {
                    var res = _.map(this.subjectTeachers, (o) => {
                        return {year_id: o.year_id, year: o.year};
                    });
                    return _.uniqWith(res, _.isEqual);
                },
                classes: function () {
                    var yearId = this.year;
                    var res = _.map(
                        _.filter(this.subjectTeachers, function (o) {
                            return o.year_id === yearId;
                        }),
                        function (o) {
                            return {class_id: o.class_id, classs: o.class};
                        }
                    );
                    return _.uniqWith(res, _.isEqual);
                },
                sections: function () {
                    var yearId = this.year;
                    var classId = this.classs;
                    var res = _.map(
                        _.filter(this.subjectTeachers, function (o) {
                            return o.year_id === yearId && o.class_id === classId;
                        }),
                        function (o) {
                            return {section_id: o.section_id, section: o.section};
                        }
                    );

                    return _.uniqWith(res, _.isEqual);
                },
                sts: function () {
                    var yearId = this.year;
                    var classId = this.classs;
                    var sectionId = this.section;
                    var res = _.map(
                        _.filter(this.subjectTeachers, function (o) {
                            return o.year_id === yearId && o.class_id === classId && o.section_id === sectionId;
                        }),
                        function (o) {
                            return {
                                id: o.id,
                                subject_code: o.subject_code,
                                subject: o.subject,
                                first_name: o.first_name,
                                last_name: o.last_name
                            };
                        }
                    );

                    return _.uniqWith(res, _.isEqual);
                }
            },
            watch: {
                st: function () {
                    var url = '{{url("api/stp")}}?st=' + this.st + '&token=';
                    this.loadFrom(url, 'get', (resp) => {
                        app.subjectTeacherPortions = resp.data;
                    });
                }
            },
            methods: {
                checkPercentage: () => {
                    if (app.percentage === '') {
                        app.error = 'Percentage cannot be empty';
                    } else if (app.percentage < 1) {
                        app.error = 'Percentage must be positive';
                    } else {
                        app.error = '';
                    }
                },
                createPortion: () => {
                    var url = '../api/portion?portion=' + app.newPortion + '&token=';
                    app.loadFrom(url, 'post', (resp) => {
                        app.status = 'Portion ' + app.newPortion + ' added successfully';
                        app.portions.splice(app.portions.length, 0, resp.data);
                    });
                },
                addPortion: () => {
                    if (app.error === '' && app.percentage !== '') {
                        var url = '{{route("stp.store")}}?st=' + app.st + '&portion=' + app.portion + '&percentage=' + app.percentage + '&token=';
                        app.loadFrom(url, 'get', (resp) => {
                            var portion = _.find(app.portions, (o) => {
                                return o.id === app.portion;
                            });
                            var newPortion = {
                                id: resp.data.id,
                                portion: portion.portion,
                                percentage: app.percentage
                            };
                            app.subjectTeacherPortions.splice(app.subjectTeacherPortions.length, 0, newPortion);
                        });
                    }
                },
                remove: (id) => {
                    var url = '{{url("api/stp/delete")}}/' + id + '?st=' + app.st + '&token=';
                    app.loadFrom(url, 'get', (resp) => {
                        var i = _.findIndex(app.subjectTeacherPortions, (o) => {
                            return o.id === id;
                        });
                        app.subjectTeacherPortions.splice(i, 1);
                    });
                },
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
                }
            },
            mounted () {
                axios.get('{{url("api/token")}}')
                    .then((response) => {
                        var token = response.data.token;
                        var url = '{{url("api/st")}}?token=' + token;
                        axios.get(url)
                            .then((response) => {
                                this.subjectTeachers = response.data;
                                this.year = response.data[0].year_id;
                                this.classs = response.data[0].class_id;
                                this.section = response.data[0].section_id;
                                this.st = response.data[0].id;
                            });

                        url = '{{url("api/portion")}}?token=' + token;
                        axios.get(url)
                            .then((response) => {
                                this.portions = response.data;
                                this.portion = this.portions[0].id;
                            });
                    });
            }
        });
    </script>
@endsection
