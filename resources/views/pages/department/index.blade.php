@extends('layouts.app')

@section('htmlheader_title')
  Department
@endsection

@section('contentheader_title')
  Department Management
@endsection

@section('contentheader_description')
  Manage your department data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Department</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">
        <?php
          $activelist = count($errors) > 0 ? '' : 'active';
          $activetab  = count($errors) > 0 ? 'active' : '';
        ?>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="{{ $activelist }}">
              <a href="#list" data-toggle="tab">Department list</a>
            </li>
            <li class="{{ $activetab }}">
              <a href="#create" data-toggle="tab">Create new department</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane {{ $activelist }}" id="list">
              @include('commons.tables.datatableFull', ['name' => 'Department List Data', 'active' => $activelist])
            </div>
            <div class="tab-pane {{ $activetab }}" id="create">
              @include('commons.tabs.departmentAdd', ['active' => $activetab])
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
