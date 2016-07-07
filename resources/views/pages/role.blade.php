@extends('layouts.app')

@section('htmlheader_title')
  Role
@endsection

@section('contentheader_title')
  Role Management
@endsection

@section('contentheader_description')
  Setting your role data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Role</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="col-md-12">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#create" data-toggle="tab">Add/Edit Role</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="create">
              @include('commons.tabs.roleAdd', ['roles' => $roles])
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
