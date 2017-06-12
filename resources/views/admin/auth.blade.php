@extends('layouts.app')

@section('style')
<style media="screen" xmlns:v-on="http://www.w3.org/1999/xhtml">
.form-control{
    max-width: 280px;
    background: rgba(255, 255, 255, .7);
}
.panel{
    border: none;
}
</style>
@endsection

@section('content')
    <div  id="vm" v-cloak>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-info">
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
                                    <option v-for="section in sections" :value="section.section_id">@{{section.section}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="panel panel-primary col-sm-12">
                <h2 class="panel-heading text-center">Summary</h2>
            </div> --}}
        </div>
        <div class="row">
            <div class="">
                <div class="panel panel-primary col-sm-6">
                    <h2 class="panel-heading text-center">Summary</h2>
                    <div class="panel-body form-horizontal">
                        <div class="form-group bg-info">
                            <label for="st" class="control-label col-sm-3">Subject</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="st" v-model="st">
                                    <option v-for="st in sts" :value="st.id">
                                        @{{st.subject_code + ' - ' + st.subject + ' (' + st.first_name + ' ' + st.last_name + ')'}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <table class="table table-striped" v-show="markEditors.length !== 0">
                            <thead>
                                <tr>
                                    <th>Authorized Editors</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="editor in markEditors">
                                    <td>@{{editor.first_name + ' ' + editor.last_name}}</td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                v-on:click="removeMarkAuth(editor.id)">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary col-sm-6">
                    <h2 class="panel-heading text-center">New Authorization</h2>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label for="editor" class="control-label col-sm-3">Editor</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="editor" v-model="markEditor">
                                    <option v-for="editor in editors" :value="editor.id">
                                        @{{editor.first_name + ' ' + editor.last_name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="button" class="btn btn-primary" @click="authMark">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" v-if="false">
                <div class="panel panel-primary">
                    <h2 class="panel-heading text-center">Transaction</h2>
                    <div class="panel-body">
                        <table class="table table-striped" v-show="transEditors.length !== 0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="editor in transEditors">
                                    <td>@{{editor.first_name + ' - ' + editor.last_name}}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="removeTransAuth(editor.id)">
                                            <span class="glyphicon glyphicon-floppy-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <h2 class="panel-heading text-center">New Transaction Authorization</h2>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label for="editor" class="control-label col-sm-3">Editor</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="editor1" v-model="transEditor">
                                    <option v-for="editor in editors" :value="editor.id">
                                        @{{editor.first_name + ' ' + editor.last_name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="button" class="btn btn-primary" v-on:click="authTrans">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
    /* global Vue, _, axios */
    /* eslint-disable indent */
    var app = new Vue({
        el: '#vm',
        data: {
            csys: [],
            subjectTeachers: [],
            markEditors: [],
            transEditors: [],
            editors: [],
            year: '',
            classs: '',
            section: '',
            st: '',
            markEditor: '',
            transEditor: ''
        },
        computed: {
            years: function () {
                var res = _.map(this.csys, function (o) {
                    return {year_id: o.year_id, year: o.year};
                });
                return _.uniqWith(res, _.isEqual);
            },
            classes: function () {
                var yearId = this.year;
                var res = _.map(
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
                var yearId = this.year;
                var classId = this.classs;
                var res = _.map(
                    _.filter(this.csys, function (o) {
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
                var url = '{{url("api/auth/mark")}}?st=' + this.st + '&cid=' + this.classs +
                    '&sid=' + this.section + '&yid=' + this.year + '&token=';
                this.loadFrom(url, 'get', (response) => {
                    this.markEditors = response.data;
                });
            },
            year: function () {
                this.updateSt();
            },
            classs: function () {
                this.updateSt();
            },
            section: function () {
                this.updateSt();
            }
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
            authMark: () => {
                var url = '{{url("api/auth/mark/store")}}?st=' + app.st + '&editor=' + app.markEditor + '&token=';
                app.loadFrom(url, 'get', (resp) => {
                    var edtr = _.find(app.editors, (o) => { return o.id === app.markEditor; });
                    var obj = {
                        id: resp.data.id,
                        first_name: edtr.first_name,
                        last_name: edtr.last_name
                    };

                    app.markEditors.splice(app.markEditors.length, 0, obj);
                });
            },
            authTrans: () => {

            },
            removeMarkAuth: (editorId) => {
                var url = '{{url("api/auth/mark/delete")}}/' + editorId + '?token=';
                app.loadFrom(url, 'get', (resp) => {
                    var i = _.findIndex(app.markEditors, (o) => { return o.id === editorId; });
                    app.markEditors.splice(i, 1);
                });
            },
            removeTransAuth: (editorId) => {

            },
            updateSt: () => {
                url = '{{url("api/st")}}?cid=' + this.classs + '&sid=' + this.section + '&yid=' + this.year + '&token=';
                app.loadFrom(url, 'get', (response) => {
                    this.subjectTeachers = response.data;
                    this.st = response.data[0].id;

                    url = '{{url("api/auth/mark")}}?st=' + this.st + '&cid=' + this.classs + '&sid=' + this.section + '&yid=' + this.year + '&token=';
                    this.loadFrom(url, 'get', (response) => {
                        this.markEditors = response.data;
                    })
                });

            }
        },
        mounted () {
            axios.get('../api/token')
            .then((response) => {
                var token = response.data.token;

                var url = '{{url("api/csy")}}?token=' + token;
                axios.get(url)
                .then((response) => {
                    this.csys = response.data;
                    this.classs = this.csys[0].class_id;
                    this.section = this.csys[0].section_id;
                    this.year = this.csys[0].year_id;

                    url = '{{url("api/st")}}?cid=' + this.classs + '&sid=' + this.section + '&yid=' + this.year + '&token=' + token;

                    axios.get(url)
                    .then((response) => {
                        this.subjectTeachers = response.data;
                        this.st = response.data[0].id;

                        url = '{{url("api/auth/mark")}}?st=' + this.st + '&cid=' + this.classs + '&sid=' + this.section + '&yid=' + this.year + '&token=' + token;
                        axios.get(url)
                        .then((response) => {
                            this.markEditors = response.data;
                        });
                    });

                    url = '{{url("api/auth/trans")}}?cid=' + this.classs + '&sid' + this.section + '&yid=' + this.year + '&token=' + token;
                    axios.get(url)
                    .then((response) => {
                        this.transEditors = response.data;
                    });
                });

                url = '{{url("api/user")}}?role=editor&token=' + token;
                axios.get(url)
                .then((response) => {
                    this.editors = response.data;
                    this.markEditor = this.editors[0].id;
                    this.transEditor = this.editors[0].id;
                });
            });
        }
    });
    </script>
@endsection
