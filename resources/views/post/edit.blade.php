@extends('layouts.app')

@section('content')

<div class="row">
    <form class="form-horizontal" action="{{route('post.update', ['post' => $post->id])}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for="title" class="control-label col-md-2">Title</label>
            <div class="col-md-10">
                <input id="title" type="text" name="title" class="form-control" maxlength="250" placeholder="Title of post" required value="{{$post->title}}">
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="control-label col-md-2">Type</label>
            <div class="col-md-10">
                <select id="type" class="form-control" name="type" value="{{$post->type}}" style="max-width: 280px;">
                    <option value="1">Notice</option>
                    <option value="2">Result</option>
                    <option value="3">Speech</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="body" class="control-label col-md-2">Body</label>
            <div class="col-md-10">
                <textarea id="body" rows="10" name="body" class="form-control" maxlength="65000">{{$post->body}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="is_open" class="control-label col-md-2">Is Open</label>
            <div class="col-md-10">
                <select id="type" class="form-control" name="is_open" value="{{$post->is_open}}" style="max-width: 280px;">
                    <option value="1">True</option>
                    <option value="0">False</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <input type="submit" class="btn btn-success btn-lg" value="Update">
            </div>
        </div>

    </form>
</div>
@endsection
