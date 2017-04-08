@extends('layouts.app')

@section('content')
<div class="row" v-cloak>
    <div class="form-horizontal col-sm-12 alert alert-success">
        <div class="form-group col-md-6">
            <label for="type" class="control-label col-md-4">Applications For</label>
            <div class="col-md-8">
                <select class="form-control" @change="typeChange" v-model="for_student">
                    <option value="-1">All</option>
                    <option value="1">Student</option>
                    <option value="0">Teacher</option>
                </select>
            </div>
        </div>
    </div>


    <div class="panel panel-default col-md-6" v-for="app in apps" style="padding: 5px">

        <h2 class="panel-heading text-center">
            <a href="#" @click="openApp(app.id)">
                @{{app.title}}
            </a>
        </h2>

        <div class="panel-body">
            <div class="list-group">
                <p v-if="for_student == '-1'" class="list-group-item list-group-item-info">
                    <strong>For </strong>:
                    <span v-if="app.for_student">Student</span>
                    <span v-else>Teacher</span>
                </p>
                <p class="list-group-item list-group-item-danger">
                    <strong>Dealine </strong> @{{fromNow(app.deadline.date)}}
                </p>
            </div>

            @{{app.detail}}
            <div class="alert alert-warning" v-show="isEditable(app.user_id)">
                <button type="button" @click="openEdit(app.id)" class="btn btn-info">Edit</button>
                <button type="button" class="btn btn-danger" style="float:right">Delete</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="/js/moment.min.js" charset="utf-8"></script>
<script type="text/javascript">
var app = new Vue({
    el: '.row',
    data: {
        apps: [],
        for_student: '-1',
        user: Object,
        roles: Object
    },
    methods:{
        openApp: function (id) {
            window.open('../resp/' + id, '_self');
        },
        typeChange:function () {
            var url = '{{url("api/app")}}' + ((this.for_student == '-1')? '': '?for_student=' + this.for_student);
            axios.get(url)
            .then((response) => {
                this.apps = response.data;
            });
        },
        fromNow: function (time) {
            return moment(time).fromNow();
        },
        isEditable: function (user_id) {
            return (user_id === this.user.id) ||
            (_.findIndex(this.roles, function (o) { return o === 'Admin'; }) !== -1);
        },
        openEdit: function (id) {
            window.open('{{url("application")}}/' + id + '/edit', '_self')
        }
    },
    mounted(){
        axios.get('{{url("api/app")}}')
        .then((response) => {
            this.apps = response.data;
        });

        axios.get("{{url('api/token')}}")
        .then((token) => {
            var token = token.data['token'];
            axios.get('{{url("api/user")}}?token=' + token)
            .then((response) => {
                this.user = response.data;
            });

            axios.get('{{url("api/roles")}}?token=' + token)
            .then((response) => {
                this.roles = response.data;
            });
        });
    }
})
</script>
@endsection
