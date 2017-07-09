@extends('layouts.app')

@section('content')
    <style>
        .list-group-item{
            max-width: 280px;
        }
    </style>
    <ul class="list-group">
        <li class="list-group-item"><a href="./student/mark"><kbd>Stuent/Mark</kbd></a></li>
        <li class="list-group-item"><a href="./common/portion"><kbd>Common/Mark Portion</kbd></a></li>
        <li class="list-group-item"><a href="./common/tabulation"><kbd>Common/Tabulation</kbd></a></li>
        <li class="list-group-item"><a href="./common/mark/update"><kbd>Common/Mark Update</kbd></a></li>
        <li class="list-group-item"><a href="./admin/subject-teacher"><kbd>Admin/Subject-Teacher</kbd></a></li>
        <li class="list-group-item"><a href="./admin/term"><kbd>Admin/Term</kbd></a></li>
        <li class="list-group-item"><a href="./admin/create"><kbd>Admin/Create</kbd></a></li>
        <li class="list-group-item"><a href="./register"><kbd>Admin/Register</kbd></a></li>
    </ul>
@endsection