@extends('layouts.app')

@section('content')
<div class="row">
    <h2 class="text-info text-center" v-text="sPost.title"></h2>
    <hr>
    <div class="col-md-8">
        <div class="well" style="text-align: justify;" v-text="sPost.body">

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
<script type="text/javascript">
    var app = new Vue({
        el: '.row',
        data: {
            sPost: {title: 'Loading...', body: ''},
            posts: [],
            type: 1
        },
        methods: {
            typeChange: function () {
                this.posts = [{title: 'Loading...'}];
                this.sPost = {title: 'Loading...', body: ''};
                Vue.http.get('{{url("api/post/type")}}/' + this.type)
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
            }
        },
        mounted(){
            var url = window.location.href;
            var id = url.substring(url.lastIndexOf('/') + 1);
            Vue.http.get("{{url('/api/post')}}/" + id)
            .then((response) => {
                this.type = response.data.type;
                this.typeChange();
                this.sPost = response.data;
            });

        }
    })
</script>
@endsection
