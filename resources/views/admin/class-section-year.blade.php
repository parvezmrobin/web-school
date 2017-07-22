@extends('layouts.app')

@section('style')
    <style media="screen">
        .form-control, .btn, .panel {
            border-radius: 1px;
        }

        .panel-heading {
            border-radius: 1px 1px 5px 5px;
        }

        h2 {
            margin-top: 0px;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="vm" v-cloak xmlns:v-on="http://www.w3.org/1999/xhtml">

        <!--Format Start-->
        <div class="panel panel-primary">
            <h2 class="panel-heading text-center">Format</h2>

            <div class="panel-body">

                <!--Select Year-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year1" class="control-label col-md-12">Select Year</label>
                        <div class="col-md-12">
                            <select id="year1" v-model="year1" class="form-control">
                                <option v-for="year in years" :value="year.year_id">@{{ year.year }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Select Class-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class1" class="control-label col-md-12">Select Class</label>
                        <div class="col-md-12">
                            <select id="class1" v-model="class1" class="form-control">
                                <option v-for="cls in classes" :value="cls.class_id">@{{ cls.classs }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Remove Section-->
                <div class="col-md-4">
                    <table class="table">
                        <tr v-for="section in sections">
                            <td>@{{ section.section }}</td>
                            <td>
                                <button class="btn btn-danger" v-on:click="remove(section.class_section_year_id)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!--Format Ends-->

        <!--Addition Starts-->
        <div class="panel panel-success">
            <h2 class="panel-heading text-center">Add</h2>
            <div class="panel-body">

                <!--Select Year-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year2" class="control-label col-md-12">Select Year</label>
                        <div class="col-md-12">
                            <select id="year2" v-model="year2" class="form-control">
                                <option v-for="year in allYears" :value="year.id">@{{ year.year }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Select Class-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class2" class="control-label col-md-12">Select Class</label>
                        <div class="col-md-12">
                            <select id="class2" v-model="class2" class="form-control">
                                <option v-for="cls in allClasses" :value="cls.id">@{{ cls.class }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Select and Add Section-->
                <div class="col-md-4">
                    <table class="table">
                        <tr v-for="section in allSections">
                            <td>@{{ section.section }}</td>
                            <td><input type="checkbox" :value="section.id" v-model="selectedSections"></td>
                        </tr>
                    </table>
                    <div class="">
                        <button class="btn btn-success" v-on:click="addSections">Add</button>
                    </div>
                </div>
            </div>

        </div>
        <!--Addition Ends-->

        <!--Creation Starts-->
        <hr>
        <h2 v-if="status.length">@{{ status }}</h2>

        {{-- Year Panel --}}
        <div class="panel panel-warning col-md-4">
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
        <div class="panel panel-warning col-md-4">
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
        <div class="panel panel-warning col-md-4">
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
    </div>
    <!--Creation Ends-->
    </div>
@endsection

@section('script')
    <script type="application/javascript">
        const app = new Vue({
            el: '#vm',
            data: {
                csys: [],
                allYears: {!! \App\Year::all() !!},
                allClasses: {!! \App\Classs::all() !!},
                allSections: {!! \App\Section::all() !!},
                selectedSections: [],
                year1: '',
                class1: '',
                year2: '',
                class2: '',
                year: '',
                classs: '',
                section: '',
                status: ''
            },
            computed: {
                years: function () {
                    const res = _.map(this.csys, function (o) {
                        return {year_id: o.year_id, year: o.year};
                    });
                    return _.uniqWith(res, _.isEqual);
                },
                classes: function () {
                    const yearId = this.year1;
                    const res = _.map(
                        _.filter(this.csys, function (o) {
                            return o.year_id == yearId;
                        }),
                        function (o) {
                            return {class_id: o.class_id, classs: o.class};
                        }
                    );
                    return _.uniqWith(res, _.isEqual);
                },
                sections: function () {
                    const yearId = this.year1;
                    const classId = this.class1;
                    const res = _.map(
                        _.filter(this.csys, function (o) {
                            return o.year_id == yearId && o.class_id == classId;
                        }),
                        function (o) {
                            return {
                                section_id: o.section_id,
                                section: o.section,
                                class_section_year_id: o.id
                            };
                        }
                    );

                    return _.uniqWith(res, _.isEqual);
                }
            },
            methods: {
                loadFrom: (url, method, then) => {
                    axios.get('{{url("api/token")}}')
                        .then((response) => {
                            url = url + response.data.token;
                            if (method === 'get') {
                                axios.get(url).then(then);
                            } else if (method === 'post') {
                                axios.post(url).then(then);
                            } else if (method === 'put') {
                                axios.put(url).then(then);
                            } else if (method === 'delete') {
                                axios.delete(url).then(then);
                            }
                        });
                },
                createYear: () => {
                    var url = '../api/year?year=' + app.year + '&token=';
                    app.loadFrom(url, 'post', (resp) => {
                        app.status = 'Year ' + app.year + ' added successfully';
                        app.allYears.splice(app.allYears.length, 0, resp.data);
                    });
                },
                createClass: () => {
                    var url = '../api/class?class=' + app.classs + '&token=';
                    app.loadFrom(url, 'post', (resp) => {
                        app.status = 'Class ' + app.classs + ' added successfully';
                        app.allClasses.splice(app.allClasses.length, 0, resp.data);
                    });
                },
                createSection: () => {
                    var url = '../api/section?section=' + app.section + '&token=';
                    app.loadFrom(url, 'post', (resp) => {
                        app.status = 'Section ' + app.section + ' added successfully';
                        app.allSections.splice(app.allSections.length, 0, resp.data);
                    });
                },
                remove: (csy_id) => {
                    const url = '../api/csy/' + csy_id + '?token=';
                    app.loadFrom(url, 'delete', (resp) => {
                        const i = _.findIndex(app.csys, o => o.id == csy_id);
                        app.csys.splice(i, 1);
                    })
                },
                addSections: () => {
                    let url = '../api/csy?year=' + app.year2 + '&class=' + app.class2 + '&section=';

                    for (let key in app.selectedSections) {
                        if (app.selectedSections.hasOwnProperty(key)) {
                            const url1 = (url + app.selectedSections[key] + '&token=');

                            app.loadFrom(url1, 'post', (resp) => {
                                app.csys.splice(app.csys.length, 0, resp.data);
                            });
                        }
                    }
                }
            },
            mounted() {
                axios.get('{{url("api/token")}}').then((resp) => {
                    const token = resp.data.token;

                    let url = '../api/csy?token=' + token;
                    axios.get(url).then((resp) => {
                        this.csys = resp.data;
                        this.year1 = this.years[0].year_id;
                        this.class1 = this.classes[0].class_id;
                    });
                    this.year2 = this.allYears[0].id;
                    this.class2 = this.allClasses[0].id;
                });
            }
        });
    </script>
@endsection