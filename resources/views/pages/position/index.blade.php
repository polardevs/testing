@extends('layouts.app')

@section('htmlheader_title')
  Positions
@endsection

@section('contentheader_title')
  Positions Management
@endsection

@section('contentheader_description')
  Manage your position data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Positions</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">
        <?php
          $activelist   = count($errors) > 0 ? '' : 'active';
          $activecreate = count($errors) > 0 ? 'active' : '';
        ?>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="{{ $activelist }}">
              <a href="#list" data-toggle="tab">Positions list</a>
            </li>
            <li class="{{ $activecreate }}">
              <a href="#create" data-toggle="tab">Create new position</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane {{ $activelist }}" id="list">
              @include('commons.tables.datatableFull', ['name' => 'Position List Data', 'dataTables' => $dataTables])
            </div>
            <div class="tab-pane {{ $activecreate }}" id="create">
              @include('commons.tabs.positionAdd')
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
