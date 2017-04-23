@extends('layouts.app')

@section('style')
  <style media="screen">
    .abcent{
      background-color: crimson;
      color: white;
    }
    .navail{
      background-color: skyblue;
      color: white;
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
  </style>
@endsection

@section('content')
  <div class="row" id="vm" v-cloak>
    <div class="col-md-3">
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
          <label for="term">Term</label>
          <select class="form-control" id="term" v-model="term" :disabled="!has_terms">
            <option v-for="term in terms" :value="term.term_id">@{{term.term}}</option>
          </select>
        </div>

        <div class="form-group">
          <button type="button" class="btn btn-primary" @click="loadMarks">
            Load
          </button>
        </div>

      </div>
    </div>
    <div class="col-md-9">
      <table class="table table-striped">
        <thead>
          <th>Subject</th>
          <th>Portion</th>
          <th>Roll</th>
          <th>Name</th>
          <th>Mark</th>
        </thead>
        <tbody>
          <tr v-for="mark in marks">
            <td>@{{mark.subject}}</td>
            <td>@{{mark.portion}}</td>
            <td><strong>@{{mark.roll}}</strong></td>
            <td>@{{mark.first_name + ' ' + mark.last_name}}</td>
            <td><input type="text"
              v-model="mark.mark"
              :class="{abcent: mark.mark == 'A', navail: mark.mark == 'N/A'}"
              class="form-control"
              @blur="updateMark(mark.id, mark.mark)">
            </td>
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
      section: Number,
      terms: [],
      term: Number,
      has_terms: true
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
        this.has_terms = false;
        axios.get('{{url("api/token")}}')
        .then((response) => {
          var token = response.data.token;
          var url = '{{url("api/csyt")}}?token=' + token + '&csy=' + res.class_section_year_id;
          axios.get(url)
          .then((response) => {
            this.terms = response.data;
            this.term = this.terms[0].term_id;
            this.has_terms = true;
          });
        });
        return res;
      }
    },
    methods: {
      updateMark: function (id, mark) {
        axios.get('{{url("api/token")}}')
        .then((response) => {
          var token = response.data.token;
          var url = '{{url("api/mark/update")}}/' + id + '?token=' + token + '&mark=' + mark;
          axios.get(url)
          .then((response) => {
            console.log(response.data);
          });
        });
      },
      loadMarks: function () {
        axios.get('{{url("api/token")}}')
        .then((response) => {
          var token = response.data.token;
          var url = '{{url("api/mark")}}?token=' + token + '&csyid=' + this.classSectionYear.class_section_year_id + '&tid=' + this.term;
          axios.get(url)
          .then((response) => {
            this.marks = _.map(response.data, (o) => {
              if (o.mark === -1) {
                o.mark = 'A';
              } else if (o.mark === -2) {
                o.mark = 'N/A';
              }
              return o;
            });
          });
        });
      }
    },
    mounted () {
      axios.get('{{url("api/token")}}')
      .then((response) => {
        var token = response.data.token;
        axios.get('{{url("api/st")}}?token=' + token)
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
