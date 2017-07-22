<!DOCTYPE html>
@extends('layouts.app')

@section('style')
<style media="screen">
.form-control{
    max-width: 280px;
    background: rgba(255, 255, 255, .7);
    border-radius: 1px;
}
.panel{
    border: none;
}
.btn-danger{
    background-color: transparent;
    color: crimson;
    border-radius: 1px;
    width: 100%;
    border: none;
}
</style>
@endsection

@section('content')
    <div id="vm" v-cloak xmlns:v-on="http://www.w3.org/1999/xhtml">
        <div class="row">
            <div class="col-sm-6">
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
            <div class="panel panel-primary col-sm-6">
                <h2 class="panel-heading text-center">Summary</h2>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead class="bg-primary">
                        <th>Term</th>
                        <th>Percentage</th>
                        <th></th>
                        </thead>
                        <tbody>
                        <tr v-for="term in existingTerms">
                            <td>@{{term.term}}</td>
                            <td>@{{term.percentage}}</td>
                            <td>
                                <button :title="'Remove ' + term.term" type="button" class="btn btn-sm btn-danger" v-on:click="removeTerm(term.id)">
                                <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="panel panel-primary col-sm-8 col-sm-offset-2">
                <h2 class="panel-heading text-center">Add Term</h2>
                <div class="panel-body form-horizontal">
                    <div class="form-group">
                        <label for="term" class="control-label col-sm-2">Term</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="term" v-model="term">
                                <option v-for="term in terms" :value="term.id">@{{term.term}}</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                          <input type="number" v-model="percentage" placeholder="Percentage" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-5 col-sm-offset-2">
                            <button type="button" class="btn btn-primary" v-on:click="addTerm">
                              Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <hr>
            <h2 v-if="status.length">@{{ status }}</h2>
            {{-- Term Panel --}}
            <div class="panel panel-warning col-sm-8 col-sm-offset-2">
                <h2 class="panel-heading">Create Term</h2>
                <div class="form-group panel-body">
                    <div class="col-md-8">
                        <input type="text" name="newTerm" placeholder="New Term" class="form-control" v-model="newTerm">
                    </div>
                    <div class="col-md-4">
                        <button type="button" v-on:click="createTerm" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

    const app = new Vue({
        el: '#vm',
        data: {
            csys: [],
            terms: [],
            existingTerms: [],
            classs: '',
            section: '',
            year: '',
            term: '',
            newTerm: '',
            percentage: '',
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
            },
            csy: function () {
                if (this.csys.length === 0) {
                    return undefined;
                }
                const yearId = this.year;
                const classId = this.classs;
                const sectionId = this.section;
                const res = _.find(this.csys, function (o) {
                    return o.year_id === yearId && o.class_id === classId && o.section_id === sectionId;
                });
                return res.id;
            }
        },
        watch: {
            csy: function () {
                let url = '{{url("api/csyt")}}?csy=' + this.csy + '&token=';
                this.loadFrom(url, 'get', (resp) => {
                    app.existingTerms = resp.data;
                });
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
            createTerm: () => {
                let url = '../api/term?term=' + app.newTerm + '&token=';
                app.loadFrom(url, 'post', (resp) => {
                    app.status = 'Term ' + app.newTerm + ' added successfully';
                    app.terms.splice(app.terms.length, 0, resp.data);
                });
            },
            addTerm: () => {
                var url = '{{url("api/csyt")}}?csy=' + app.csy + '&term=' + app.term +
                    '&percentage=' + app.percentage + '&token=';
                app.loadFrom(url, 'post', (resp) => {
                    var t = _.find(app.terms, (o) => { return o.id === app.term; });
                    var obj = {
                        id: t.id,
                        term: t.term,
                        percentage: app.percentage
                    };

                    app.existingTerms.splice(app.existingTerms.length, 0, obj);
                });
            },
            removeTerm: (id) => {
                var url = '{{url("api/csyt/delete")}}?id=' + id + '&token=';
                app.loadFrom(url, 'post', (resp) => {
                    var i = _.findIndex(app.existingTerms, (o) => { return o.id === id; });
                    app.existingTerms.splice(i, 1);
                });
            }
        },
        mounted () {
            axios.get('{{url("api/token")}}')
            .then((response) => {
                var token = response.data.token;

                var url = '{{url("api/csy")}}?token=' + token;
                axios.get(url)
                .then((response) => {
                    this.csys = response.data;
                    this.classs = this.csys[0].class_id;
                    this.section = this.csys[0].section_id;
                    this.year = this.csys[0].year_id;
                    this.csy = this.csy;

                    url = '{{url("api/csyt")}}?csy=' + this.csy + '&token=' + token;
                    axios.get(url)
                    .then((response) => {
                        this.existingTerms = response.data;
                    });
                });

                axios.get('{{url("api/term")}}?token=' + token)
                .then((response) => {
                    this.terms = response.data;
                    this.term = this.terms[0].id;
                });
            });
        }
    });
    </script>
@endsection
