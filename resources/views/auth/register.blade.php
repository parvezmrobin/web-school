@extends('layouts.app')

@section('content')
<div class="container" id="vm">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('fathers_name') ? ' has-error' : '' }}">
                            <label for="fathers_name" class="col-md-4 control-label">Father's Name</label>

                            <div class="col-md-6">
                                <input id="fathers_name" type="text" class="form-control" name="fathers_name" value="{{ old('fathers_name') }}" required>

                                @if ($errors->has('fathers_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fathers_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mothers_name') ? ' has-error' : '' }}">
                            <label for="mothers_name" class="col-md-4 control-label">Mother's Name</label>

                            <div class="col-md-6">
                                <input id="mothers_name" type="text" class="form-control" name="mothers_name" value="{{ old('mothers_name') }}" required>

                                @if ($errors->has('mothers_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mothers_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Address</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required>

                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Sex</label>

                            <div class="col-md-6">
                                <select id="sex" class="form-control" name="sex" value="{{ old('sex') }}">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>

                                @if ($errors->has('sex'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sex') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                            <label for="role" class="col-md-4 control-label">Role</label>

                            <div class="col-md-6">
                                <select id="role" class="form-control" name="role" v-model="role" value="{{ old('role') }}">
                                    <option value="1">Student</option>
                                    <option value="2">Teacher</option>
                                    <option value="3">Admin</option>
                                </select>

                                @if ($errors->has('role'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div id="extra">
                            <div id="student" v-if="role == 1">
                                <div class="form-group">
                                    <label for="year" class="col-md-4 control-label">Year</label>

                                    <div class="col-md-6">
                                        <select id="year" class="form-control" name="year" v-model="year">
                                            <option v-for="year in years" :value="year.id">@{{ year.year }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="class" class="col-md-4 control-label">Class</label>

                                    <div class="col-md-6">
                                        <select id="class" class="form-control" name="class" v-model="cls">
                                            <option v-for="cls in classes" :value="cls.id">@{{ cls.class }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="section" class="col-md-4 control-label">Section</label>

                                    <div class="col-md-6">
                                        <select id="section" class="form-control" name="section" v-model="section">
                                            <option v-for="section in sections" :value="section.id">@{{ section.section }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="teacher" v-if="role == 2">
                                <div class="form-group">
                                    <label for="designation" class="col-md-4 control-label">Designation</label>

                                    <div class="col-md-6">
                                        <select id="designation" class="form-control" name="designation" value="{{ old('designation') }}">
                                            <option value="1">Assistant Teacher</option>
                                            <option value="2">Senior Teacher</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="qualification" class="col-md-4 control-label">Qualification</label>

                                    <div class="col-md-6">
                                        <textarea name="qualification" id="qualification" class="form-control">{{old('qualification')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        const app = new Vue({
            el: '#vm',
            data: {
                role: '{{old('role')}}',
                cls: '1',
                year: '1',
                section: '1',
                years: [
                    {id: 1, year: 2015},
                    {id: 2, year: 2016},
                    {id: 3, year: 2017}
                ],
                classes: [
                    {id: 1, class: 'One'},
                    {id: 2, class: 'Two'},
                    {id: 3, class: 'Three'},
                    {id: 4, class: 'Four'},
                    {id: 5, class: 'Five'}
                ],
                sections: [
                    {id: 1, section: 'A'},
                    {id: 2, section: 'B'},
                    {id: 3, section: 'C'}
                ]
            }
        })
    </script>
@endsection
