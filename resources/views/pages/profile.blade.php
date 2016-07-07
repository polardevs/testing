@extends('layouts.app')

@section('htmlheader_title')
  Profile
@endsection

@section('contentheader_title')
  User Profile
@endsection

@section('contentheader_description')

@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Profile</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="box box-primary">
          @if($profile->active)
            <div class="box-body box-profile">
              <h3 class="profile-username text-center">{{ $profile->firstname . '  ' . $profile->lastname }}</h3>
              <p class="text-muted text-center">{{ $profile->position->name or 'none' }}</p>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Employee ID</b> <a class="pull-right">{{ $profile->code }}</a>
                </li>
                <li class="list-group-item">
                  <b>Department</b> <a class="pull-right">{{ $profile->department->name or 'none' }}</a>
                </li>
                <li class="list-group-item">
                  <b>Username</b> <a class="pull-right">{{ $profile->username }}</a>
                </li>
                <li class="list-group-item">
                  <b>Role</b> <a class="pull-right">{{ $profile->permission->role->name or 'none' }}</a>
                </li>
              </ul>
              <a href="{{ action('Auth\AuthController@logout') }}" class="btn btn-primary btn-block btn-flat">Sign out</a>
            </div>
          @else
            <div class="register-box-body">
              <p class="login-box-msg">Change your password to activate user</p>
              <form action="{{ action('ProfileController@update', $profile->id) }}" method="post">
                {!! csrf_field() !!}
                {!! method_field('put') !!}
                <div class="form-group has-feedback">
                  <input name="password" type="password" class="form-control" placeholder="Password">
                  <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                  <input name="rePassword" type="password" class="form-control" placeholder="Retype password">
                  <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                  <!-- /.col -->
                  <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
            </div>
          @endif

          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </div>
@endsection
