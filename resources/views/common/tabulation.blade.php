@extends('layouts.app')

@section('style')
  <style media="screen">
    .abcent{
      color: red;
      font-weight: bold;
    }
    .navail{
      color: blue;
      font-weight: bold;
    }
  </style>
@endsection

@section('content')
  <div class="row" id="vm">
    <div class="col-md-4">
      <div class="form-horizontal">
        <div class="form-group">
          <label for="year">Year</label>
          <select class="form-control" id="year" v-model="year">
            <option v-for="year in years" :value="year.year_id">@{{year.year}}</option>
          </select>
        </div>

        <div class="form-group">
          <label for="class">Class</label>
          <select class="form-control" id="class" v-model="classs">
            <option v-for="cls in classes" :value="cls.class_id">@{{cls.classs}}</option>
          </select>
        </div>

        <div class="form-group">
          <label for="section">Section</label>
          <select class="form-control" id="section" v-model="section">
            <option v-for="section in sections" :value="section.section_id">@{{section.section}}</option>
          </select>
        </div>

        <div class="form-group">
          <button type="button" class="btn btn-default" @click="loadMarks">
            Load
          </button>
        </div>

      </div>
    </div>

    <div class="col-md-8">
      <table class="table table-striped">
        <thead>
          <th>Term</th>
          <th>Subject</th>
          <th>Subject Code</th>
          <th>Portion</th>
          <th>Old Roll</th>
          <th>Name</th>
          <th>Mark</th>
        </thead>
        <tbody>
          <tr v-for="mark in marks">
            <td>@{{mark.term}}</td>
            <td>@{{mark.subject}}</td>
            <td>@{{mark.subject_code}}</td>
            <td>@{{mark.portion}}</td>
            <td>@{{mark.roll}}</td>
            <td>@{{mark.first_name + ' ' + mark.last_name}}</td>
            <td :class="{abcent: mark.mark == -1, navail: mark.mark == -2}">@{{format(mark.mark)}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
  /* global Vue, _, axios */
    new Vue({
      el: '#vm',
      data: {
        csy: [],
        marks: [],
        year: Number,
        classs: Number,
        section: Number
      },
      computed: {
        years: function () {
          var res = _.map(this.csy, function (o) {
            return {year_id: o.year_id, year: o.year};
          });
          return _.uniqWith(res, _.isEqual);
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
            });
          });
        },
        format: (mark) => {
          if (mark >= 0) return mark;
          if (mark === -1) return 'A';
          if (mark === -2) return 'N/A';
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
