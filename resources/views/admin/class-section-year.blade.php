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

        <!--Edition Starts-->

        <!--Editions Ends-->
        <div class="panel panel-warning col-md-4">

            <!--Update Year Starts-->
            <h2 class="panel-heading">Update Year</h2>
            <div class="form-horizontal panel-body">

                <!--Select Year-->
                <div class="form-group">
                    <label for="year3" class="control-label col-md-4">Select Year</label>
                    <div class="col-md-8">
                        <select id="year3" v-model="year3" class="form-control">
                            <option v-for="year in allYears" :value="year.id">@{{ year.year }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <input type="number" name="year" placeholder="New Name" class="form-control" v-model="newYear">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="updateYear" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </div>
            <!--Update Year Ends-->
        </div>
        <div class="panel panel-warning col-md-4">
            <!--Update Class Starts-->
            <h2 class="panel-heading">Update Class</h2>
            <div class="form-horizontal panel-body">

                <!--Select Class-->
                <div class="form-group">
                    <label for="year3" class="control-label col-md-4">Select Class</label>
                    <div class="col-md-8">
                        <select id="class3" v-model="class3" class="form-control">
                            <option v-for="cls in allClasses" :value="cls.id">@{{ cls.class }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <input type="text" placeholder="New Name" class="form-control" v-model="newClass">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="updateClass" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </div>
            <!--Update Class Ends-->
        </div>
        <div class="panel panel-warning col-md-4">
            <!--Update Section Starts-->
            <h2 class="panel-heading">Update Section</h2>
            <div class="form-horizontal panel-body">

                <!--Select Section-->
                <div class="form-group">
                    <label for="section3" class="control-label col-md-5">Select Section</label>
                    <div class="col-md-7">
                        <select id="section3" v-model="section3" class="form-control">
                            <option v-for="section in allSections" :value="section.id">
                                @{{ section.section }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <input type="text" placeholder="New Name" class="form-control" v-model="newSection">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="updateSection" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </div>
            <!--Update Section Ends-->
        </div>


        <!--Creation Starts-->
        <hr>
        <h2 v-if="status.length">@{{ status }}</h2>

        {{-- Year Panel --}}
        <div class="panel panel-success col-md-4">
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
        <div class="panel panel-success col-md-4">
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
        <div class="panel panel-success col-md-4">
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
                status: '',
                year3: '',
                class3: '',
                section3: '',
                newYear: '',
                newClass: '',
                newSection: ''
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
                },
                updateYear: () => {
                    let url = '../api/year/' + app.year3 + '?year=' + app.newYear + '&token=';
                    app.loadFrom(url, 'put', (resp) => {
                        const i = _.findIndex(app.allYears, (o) => {return o.id == app.year3;})
                        app.allYears.splice(i, 1, resp.data);
                    })
                },
                updateClass: () => {
                    let url = '../api/class/' + app.class3 + '?class=' + app.newClass + '&token=';
                    app.loadFrom(url, 'put', (resp) => {
                        const i = _.findIndex(app.allClasses, (o) => {return o.id == app.class3;})
                        app.allClasses.splice(i, 1, resp.data);
                    })
                },
                updateSection: () => {
                    let url = '../api/section/' + app.section3 + '?section=' + app.newSection + '&token=';
                    app.loadFrom(url, 'put', (resp) => {
                        const i = _.findIndex(app.allSections, (o) => {return o.id == app.section3;})
                        app.allSections.splice(i, 1, resp.data);
                    })
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