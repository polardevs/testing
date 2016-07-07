@extends('layouts.app')

@section('htmlheader_title')
  Project
@endsection

@section('contentheader_title')
  Project Management
@endsection

@section('contentheader_description')
  Manage your project data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Project</li>
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
              <a href="#list" class="active" data-toggle="tab">Project list</a>
            </li>
            @if(Auth::user()->permission->role->isManager())
              <li class="{{ $activeCreate }}">
                <a href="#create" data-toggle="tab">Create new project</a>
              </li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane {{ $activeList }}" id="list">
              @include('commons.tables.datatableFull')
            </div>
            @if(Auth::user()->permission->role->isManager())
              <div class="tab-pane {{ $activeCreate }}" id="create">
                @include('commons.tabs.projectAdd')
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
