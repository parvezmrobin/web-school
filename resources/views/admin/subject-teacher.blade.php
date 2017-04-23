@extends('layouts.app')

@section('style')
  <style media="screen">
    .panel{
      border: none;
    }
    select{
      max-width: 280px;
    }
  </style>
@endsection

@section('content')
  <div class="row" id="vm" v-cloak>
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
              <option v-for="section in sections" :value="section.section_id">@{{section.section}}</option>
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
                    <a class="btn btn-sm btn-danger" href="#" @click="remove(st.id)">
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
              <option v-for="teacher in teachers" :value="teacher.id">@{{teacher.first_name + ' ' + teacher.last_name}}</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="subject" class="control-label col-sm-4">Select Subject</label>
          <div class="col-sm-8">
            <select class="form-control" id="subject" v-model="subject">
              <option v-for="subject in subjects" :value="subject.id">@{{subject.subject_code + ' - ' + subject.subject + ' (' + subject.mark + ')'}}</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-8 col-sm-offset-4">
            <button type="button" class="btn btn-primary" @click="assignSubject">
              Assign
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection

@section('script')
  <script type="text/javascript">
  /* global Vue, _, axios */
  var app = new Vue({
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
      section: ''
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
      assignSubject: () => {
        var url = '{{url("api/st")}}?teacher=' + app.teacher + '&subject=' + app.subject + '&csy=' + app.classSectionYear.id + '&token=';
        app.loadFrom(url, 'post', (response) => {
          var sub = _.find(app.subjects, {id: app.subject});
          var tea = _.find(app.teachers, {id: app.teacher});
          var obj = {
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
        var url = '{{url("api/st")}}?csy=' + csy.id + '&token=';
        app.loadFrom(url, 'get', (response) => {
          app.subjectTeachers = response.data;
        });
      },
      loadClassSectionYear: () => {
        var res = _.find(app.csys, {class_id: app.classs, section_id: app.section, year_id: app.year});
        app.loadSubjectTeacher(res);
        app.classSectionYear = res;
      },
      remove: (id) => {
        var url = '{{url("api/st/delete")}}/' + id + '?token=';
        app.loadFrom(url, 'get', (response) => {
          var i = _.findIndex(app.subjectTeachers, (o) => {
            return o.id === id;
          });

          app.subjectTeachers.splice(i, 1);
        });
      }
    },
    watch: {
      year: () => { app.loadClassSectionYear(); },
      classs: () => { app.loadClassSectionYear(); },
      section: () => { app.loadClassSectionYear(); }
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
      }
    },
    mounted () {
      axios.get('{{url("api/token")}}')
      .then((response) => {
        var token = response.data.token;
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
