@extends('layouts.app')

@section('htmlheader_title')
  Employee
@endsection

@section('contentheader_title')
  Employee Management
@endsection

@section('contentheader_description')
  Manage your employee data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Employee</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">
        <?php
          $activeList   = count($errors) > 0 ? '' : 'active';
          $activeCreate = count($errors) > 0 ? 'active' : '';
        ?>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="{{ $activeList }}">
              <a href="#list" data-toggle="tab">Employee list</a>
            </li>
            <li class="{{ $activeCreate }}">
              <a href="#create" data-toggle="tab">Create new employee</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane {{ $activeList }}" id="list">
              @include('commons.tables.datatableFull', ['name' => 'Employee List Data', 'employees' => []])
            </div>
            <div class="tab-pane {{ $activeCreate }}" id="create">
              @include('commons.tabs.employeeAdd')
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
