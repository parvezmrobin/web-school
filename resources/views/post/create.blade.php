@extends('layouts.app')

@section('content')
<div class="row">
    <form class="form-horizontal" action="{{url('post')}}" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="title" class="control-label col-md-2">Title</label>
            <div class="col-md-10">
                <input id="title" type="text" name="title" class="form-control" maxlength="255" placeholder="Write title here" required>
            </div>
        </div>
        <div class="form-group">
            <label for="Type" class="control-label col-md-2">Type</label>
            <div class="col-md-10">
                <select class="form-control" name="type" style="max-width:280px">
                    <option value="1">Notice</option>
                    <option value="2">Result</option>
                    <option value="3">Speech</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="body" class="control-label col-md-2">Body</label>
            <div class="col-md-10">
                <textarea id="body" type="text" rows="10" name="body" class="form-control" maxlength="65535" placeholder="Post body goes here" required></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <input type="submit" class="btn btn-success" value="Create">
            </div>
        </div>

    </form>
</div>
@endsection
