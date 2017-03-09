@extends('layouts.app')

@section('content')
<div class="row" v-cloak>
    <h2 class="text-info text-center" v-text="sPost.title"></h2>
    <hr>
    <div class="col-md-8">
        <p class="alert alert-info"><strong>Last Update: </strong>@{{dateDiff(sPost.updated_at)}}</p>
        <div class="well" style="text-align: justify;" v-text="sPost.body">

        </div>
        <div class="alert alert-warning" v-show="isEditable">
            <button type="button" @click="openEdit" class="btn btn-info">Edit</button>
            <button type="button" class="btn btn-danger" style="float:right">Delete</button>
        </div>
    </div>
<br>
    <div class="col-md-4 form-horizontal">
        <div class="form-group">
            <label for="type" class="control-label col-xs-4">Type</label>
            <div class="col-xs-8">
                <select v-model="type" class="form-control" name="type" @change="typeChange">
                    <option value="1">Notice</option>
                    <option value="2">Result</option>
                    <option value="3">Speech</option>
                </select>
            </div>
        </div>
        <div class="list-group">
            <a class="list-group-item" href="#" @click="postSelect(post)" v-for="post in posts">@{{post.title}}</a>
        </div>

    </div>

</div>
@endsection

@section('script')
<script src="/js/moment.min.js" charset="utf-8"></script>
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            sPost: {title: 'Loading...', body: ''},
            posts: [],
            type: 1,
            user: Object,
            roles: Object
        },
        methods: {
            typeChange: function () {
                this.posts = [{title: 'Loading...'}];
                this.sPost = {title: 'Loading...', body: ''};
                axios.get('{{url("api/post/type")}}/' + this.type)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }
                    this.posts = response.data;
                    this.sPost = this.posts[0];
                });
            },
            postSelect : function (post) {
                this.sPost = post;
            },
            dateDiff:  function (time) {
                return moment(time).fromNow();
            },
            openEdit: function () {
                window.open('{{url('post')}}/' + this.sPost.id + '/edit', '_self');
            }
        },
        computed: {
            isEditable: function () {
                return (this.sPost.user_id === this.user.id) ||
                (_.findIndex(this.roles, function (o) { return o === 'Admin'; }) !== -1);
            }
        },
        mounted(){
            var url = window.location.href;
            var id = url.substring(url.lastIndexOf('/') + 1);
            if (isNaN(id)) {
                this.type = 1;
                this.typeChange();
            }else {
                axios.get("{{url('/api/post')}}/" + id)
                .then((response) => {
                    this.type = response.data.type;
                    this.typeChange();
                    this.sPost = response.data;
                });
            }

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
