<!DOCTYPE html>
@extends('layouts.app')

@section('style')
<style media="screen">
.aplus{
    color: darkgreen;
}
.fail{
    color: crimson;
}
.form-control, .btn{
    max-width: 280px;
    border-radius: 1px;
}

.btn{
    background: transparent;
    font-weight: bold;
    color: #103771;
    min-width: 100px;
}
td, th{
    text-align: center;
}
</style>
@endsection

@section('content')
    <div class="row" id="vm" v-cloak>
        <h2 class="text-center">Tabulation</h2>
        <hr>
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
                        <button type="button" class="btn btn-primary" @click="loadMarks">
                            Load
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12 table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th rowspan="3">New Roll</th>
                        <th rowspan="3">Old Roll</th>
                        <th rowspan="3" style="min-width:150px">Name</th>
                        <th v-for="sub in subs" :colspan="(terms.length * 3) + 1">@{{sub}}</th>
                        <th rowspan="3">GPA</th>
                    </tr>
                    <tr>

                        <template v-for="sub in subs">
                            <th v-for="term in terms" colspan="3">@{{term.term + ' (' + term.percentage + '%)'}}</th>
                            <th rowspan="2">Subject Grade</th>
                        </template>
                    </tr>
                    <tr>
                        <template v-for="sub in subs">
                            <template v-for="term in terms">
                                <th>Mark</th>
                                <th>Grade</th>
                                <th>Point</th>
                            </template>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(std, index) in groups">
                        <td>@{{index + 1}}</td>
                        <td>@{{getRoll(std)}}</td>
                        <td>@{{getName(std)}}</td>
                        <template v-for="sub in subs" :colth="terms.length * 3">
                            <template v-if="std[sub] !== undefined">
                                <template v-for="term in terms.length">
                                    <td>@{{Math.round(std[sub][term-1].mark)}}</td>
                                    <td
                                        :class="{aplus: std[sub][term-1].grade === 'A+', fail: std[sub][term-1].grade === 'F'}">
                                        <strong>@{{std[sub][term-1].grade}}</strong>
                                    </td>
                                    <td>@{{std[sub][term-1].grade_point}}</td>
                                </template>
                                <th>@{{std[sub].grade_point.toFixed(2)}}</th>
                            </template>
                            <td :colspan="terms.length*3" v-if="std[sub] === undefined">N/A</td>
                        </template>
                        <td>@{{std.gpa.toFixed(2)}}</td>
                    </tr>
                </tbody>
            </table>
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
            csy: [],
            marks: [],
            portions: [],
            year: Number,
            classs: Number,
            section: Number,
            groups: Object,
            subs: [],
            terms: []
        },
        computed: {
            years: function () {
                var res = _.map(this.csy, function (o) {
                    return {year_id: o.year_id, year: o.year};
                });
                res = _.orderBy(_.uniqWith(res, _.isEqual), ['year'], ['desc']);
                if (res.length) {
                    this.year = res[0].year_id;
                }
                return res;
            },
            classes: function () {
                var yearId = this.year;
                var res = _.map(
                    _.filter(this.csy, function (o) {
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
                    _.filter(this.csy, function (o) {
                        return o.year_id === yearId && o.class_id === classId;
                    }),
                    function (o) {
                        return {section_id: o.section_id, section: o.section};
                    }
                );
                return _.uniqWith(res, _.isEqual);
            },
            classSectionYear: function () {
                var res = _.find(this.csy, {class_id: this.classs, section_id: this.section, year_id: this.year});
                return res;
            }
        },
        methods: {
            loadMarks: function () {
                axios.get('{{url("api/token")}}')
                .then((response) => {
                    var token = response.data.token;
                    var url = '{{url("api/tabulation")}}?token=' + token + '&csyid=' + this.classSectionYear.id;
                    axios.get(url)
                    .then((response) => {
                        this.marks = response.data;
                        this.groupMarks();
                    });
                });
            },
            format: (mark) => {
                if (mark >= 0) return (Math.round(mark));
                if (mark === -1) return 'A';
                if (mark === -2) return 'N/A';
            },
            getRoll: (student) => {
                for (var t in student) {
                    for (var tt in student[t]) {
                        return student[t][tt].roll;
                    }
                }
            },
            getName: (student) => {
                for (var t in student) {
                    for (var tt in student[t]) {
                        return student[t][tt].first_name + ' ' + student[t][tt].last_name;
                    }
                }
            },
            log: (data) => { return console.log(data); },
            isDefined: (std, sub) => {
                console.log(sub + ': ' + std[sub]);
                return std[sub] !== undefined;
            },
            groupMarks: function () {
                var students = _.groupBy(this.marks, 'student_id');
                this.subs = [];
                for (var subs in students) {
                    students[subs] = _.groupBy(students[subs], o => o.subject_code + ' - ' + o.subject);
                    this.subs = _.union(this.subs, Object.keys(students[subs]));
                    var gradePointTotal = 0;
                    var numSubs = 0;
                    for (var sub in students[subs]) {
                        var terms = students[subs][sub];
                        var termPercentage = 0;
                        var gradePointTerm = 0;
                        for (var term in terms) {
                            var outOfHundred = (terms[term].mark / terms[term].total_mark) * 100;
                            termPercentage += terms[term].term_percentage;
                            if (outOfHundred >= 80) {
                                terms[term].grade = 'A+';
                                terms[term].grade_point = 5;
                            } else if (outOfHundred >= 70) {
                                terms[term].grade = 'A';
                                terms[term].grade_point = 4;
                            } else if (outOfHundred >= 60) {
                                terms[term].grade = 'A-';
                                terms[term].grade_point = 3.5;
                            } else if (outOfHundred >= 50) {
                                terms[term].grade = 'B';
                                terms[term].grade_point = 3;
                            } else if (outOfHundred >= 40) {
                                terms[term].grade = 'C';
                                terms[term].grade_point = 2;
                            } else if (outOfHundred >= 33) {
                                terms[term].grade = 'D';
                                terms[term].grade_point = 1;
                            } else {
                                terms[term].grade = 'F';
                                terms[term].grade_point = 0;
                            }
                            if (!terms[term].is_compulsory) {
                                terms[term].grade_point -= 2;
                                (terms[term].grade_point < 0) ? 0 : terms[term].grade_point;
                            }
                            gradePointTerm += terms[term].grade_point * terms[term].term_percentage;
                        }
                        terms.grade_point = (gradePointTerm / termPercentage);
                        gradePointTotal += terms.grade_point;
                        numSubs++;
                    }
                    students[subs].gpa = gradePointTotal / numSubs;
                }
                for (var v in students) {
                    for (var vv in students[v]) {
                        this.terms = _.map(students[v][vv], (o) => ({term: o.term, term_id: o.term_id, percentage: o.term_percentage}));
                        break;
                    }
                    break;
                }
                students = _.orderBy(students, ['gpa'], ['desc']);
                this.groups = _.toArray(students);
                console.log(this.groups);
            }
        },
        mounted () {
            axios.get('{{url("api/token")}}')
            .then((response) => {
                var token = response.data.token;
                axios.get('{{url("api/csy")}}?token=' + token)
                .then((response) => {
                    this.csy = response.data;
                    this.year = this.csy[0].year_id;
                    this.classs = this.classes[0].class_id;
                    this.section = this.sections[0].section_id;
                    this.classSectionYear = this.classSectionYear;
                });
            });
        }
    });
    </script>
@endsection
