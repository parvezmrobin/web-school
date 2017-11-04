@extends('layouts.app')

@section('style')
    <style media="screen">
        .form-control, .btn, .panel {
            border-radius: 2px;
        }

        .panel-heading {
            border-radius: 1px 1px 5px 5px;
        }

        h2 {
            margin-top: 0px;
        }
        .panel {
            border: none;
        }

        select {
            max-width: 280px;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="vm" v-cloak xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row">
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
                </div>
            </div>
            <div class="panel panel-info col-md-7">
                <h2 class="panel-heading text-center">Summary</h2>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Total Mark</th>
                                <th>Compulsory</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="st in subjectTeachers">
                                <td>@{{st.subject_code}}</td>
                                <td>@{{st.subject}}</td>
                                <td>@{{st.first_name + ' ' + st.last_name}}</td>
                                <td>@{{st.mark}}</td>
                                <td>@{{(st.is_compulsory)? 'True' : 'False'}}</td>
                                <td>
                                    <a class="btn btn-sm btn-danger" href="#" v-on:click="remove(st.id)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary col-md-8 col-md-offset-2">
                <h2 class="panel-heading text-center">Assign Subject Teacher</h2>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label for="teacher" class="control-label col-sm-4">Select Teacher</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="teacher" v-model="teacher">
                                <option v-for="teacher in teachers"
                                        :value="teacher.id">@{{teacher.first_name + ' ' + teacher.last_name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="control-label col-sm-4">Select Subject</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="subject" v-model="subject">
                                <option v-for="subject in subjects"
                                        :value="subject.id">@{{subject.subject_code + ' - ' + subject.subject + ' (' + subject.mark + ')'}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <button type="button" class="btn btn-primary" v-on:click="assignSubject">
                                Assign
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            {{-- Update Subject Panel --}}
            <div class="panel panel-warning col-md-8 col-md-offset-2 form-horizontal">
                <h2 class="panel-heading text-center">Update Subject</h2>

                <!--Select Subject For Update-->
                <div class="form-group">
                    <label for="subject2" class="control-label col-sm-4">Select Subject</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="subject2" v-model="subject2">
                            <option v-for="subject in subjects"
                                    :value="subject.id">
                                @{{subject.subject_code + ' - ' + subject.subject + ' (' + subject.mark + ')'}}
                            </option>
                        </select>
                    </div>
                </div>

                <!--Set The New Values-->
                <div class="form-group panel-body">
                    <div class="col-md-2">
                        <input type="text" placeholder="Code" class="form-control" v-model="subjectCode2">
                    </div>
                    <div class="col-md-4">
                        <input type="text" placeholder="Name" class="form-control" v-model="subjectName2">
                    </div>
                    <div class="col-md-2">
                        <input type="text" placeholder="Mark" class="form-control" v-model="subjectMark2">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="updateSubject" class="btn btn-warning">Update</button>
                    </div>
                </div>

            </div>
        </div>
        <hr>

        <div class="row">
            <h2 v-if="status.length" class="text-center">@{{ status }}</h2>
            {{-- Create Subject Panel --}}
            <div class="panel panel-success col-md-8 col-md-offset-2">
                <h2 class="panel-heading text-center">Create Subject</h2>
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
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let app = new Vue({
            el: '#vm',
            data: {
                subjects: [],
                teachers: [],
                subjectTeachers: [],
                classSectionYear: [],
                teacher: '',
                subject: '',
                csys: [],
                year: '',
                classs: '',
                subjectCode: '',
                subjectName: '',
                subjectMark: '',
                section: '',
                status: '',
                subject2: '',
                subjectCode2: '',
                subjectMark2: '',
                subjectName2: '',
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
                createSubject: () => {
                    var url = '../api/subject?subject=' + app.subjectName + '&code=' + app.subjectCode + '&mark=' + app.subjectMark + '&token=';
                    app.loadFrom(url, 'post', (resp) => {
                        app.status = 'Subject ' + app.subjectCode + ' - ' + app.subjectName + ' added successfully';
                        app.subjects.splice(app.subjects.length, 0, resp.data);
                    });
                },
                assignSubject: () => {
                    let url = '{{url("api/st")}}?teacher=' + app.teacher + '&subject=' + app.subject + '&csy=' + app.classSectionYear.id + '&token=';
                    app.loadFrom(url, 'post', (response) => {
                        const sub = _.find(app.subjects, {id: app.subject});
                        const tea = _.find(app.teachers, {id: app.teacher});
                        const obj = {
                            id: response.data.id,
                            subject_id: response.data.subject_id,
                            teacher_id: response.data.teacher_id,
                            subject_code: sub.subject_code,
                            subject: sub.subject,
                            first_name: tea.first_name,
                            last_name: tea.last_name,
                            mark: sub.mark,
                            is_compulsory: sub.is_compulsory
                        };
                        app.subjectTeachers.splice(app.subjectTeachers.length, 0, obj);
                    });
                },
                loadSubjectTeacher: function (csy = app.classSectionYear) {
                    let url = '{{url("api/st")}}?csy=' + csy.id + '&token=';
                    app.loadFrom(url, 'get', (response) => {
                        app.subjectTeachers = response.data;
                    });
                },
                loadClassSectionYear: () => {
                    const res = _.find(app.csys, {class_id: app.classs, section_id: app.section, year_id: app.year});
                    app.loadSubjectTeacher(res);
                    app.classSectionYear = res;
                },
                remove: (id) => {
                    let url = '{{url("api/st/delete")}}/' + id + '?token=';
                    app.loadFrom(url, 'get', (response) => {
                        let i = _.findIndex(app.subjectTeachers, (o) => {
                            return o.id === id;
                        });

                        app.subjectTeachers.splice(i, 1);
                    });
                },
                updateSubject: () => {
                    let url = '../api/subject/' + app.subject2 + '?subject=' + app.subjectName2 +
                        '&code=' + app.subjectCode2 + '&mark=' + app.subjectMark2 + '&token=';

                    app.loadFrom(url, 'put', (resp) => {
                        const i = _.findIndex(app.subjects, (o) => {return o.id == app.subject2;});
                        app.subjects.splice(i, 1, resp.data);
                    })
                }
            },
            watch: {
                year: () => {
                    app.loadClassSectionYear();
                },
                classs: () => {
                    app.loadClassSectionYear();
                },
                section: () => {
                    app.loadClassSectionYear();
                },
                subject2: () => {
                    const i = _.findIndex(app.subjects, (o) => {return o.id == app.subject2;})
                    app.subjectName2 = app.subjects[i].subject;
                    app.subjectCode2 = app.subjects[i].subject_code;
                    app.subjectMark2 = app.subjects[i].mark;
                }
            },
            computed: {
                years: function () {
                    const res = _.map(this.csys, function (o) {
                        return {year_id: o.year_id, year: o.year};
                    });
                    return _.uniqWith(res, _.isEqual);
                },
                classes: function () {
                    const yearId = this.year;
                    const res = _.map(
                        _.filter(this.csys, function (o) {
                            return o.year_id === yearId;
                        }),
                        function (o) {
                            return {class_id: o.class_id, classs: o.class};
                        }
                    );
                    return _.uniqWith(res, _.isEqual);
                },
                sections: function () {
                    const yearId = this.year;
                    const classId = this.classs;
                    const res = _.map(
                        _.filter(this.csys, function (o) {
                            return o.year_id === yearId && o.class_id === classId;
                        }),
                        function (o) {
                            return {section_id: o.section_id, section: o.section};
                        }
                    );

                    return _.uniqWith(res, _.isEqual);
                }
            },
            mounted () {
                axios.get('{{url("api/token")}}')
                    .then((response) => {
                        const token = response.data.token;
                        axios.get('{{url("api/csy")}}?token=' + token)
                            .then((response) => {
                                this.csys = response.data;
                                this.year = this.csys[0].year_id;
                                this.classs = this.classes[0].class_id;
                                this.section = this.sections[0].section_id;
                            });

                        axios.get('{{url("api/subject")}}?token=' + token)
                            .then((response) => {
                                this.subjects = response.data;
                                this.subject = this.subjects[0].id;
                            });

                        axios.get('{{url("api/user")}}?token=' + token + '&role=teacher')
                            .then((response) => {
                                this.teachers = response.data;
                                this.teacher = this.teachers[0].id;
                            });
                    });
            }
        });
    </script>
@endsection
