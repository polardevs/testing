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

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#edit" class="active" data-toggle="tab">Project list</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="edit">
              @if (count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <div class="tab-pane {{ @$active }}" id="timesheet">
                <form role="form" action="{{ action('ProjectController@update', $project->id) }}" method="POST">
                  {!! method_field('PUT') !!}
                  {!! csrf_field() !!}
                  <div class="box-header">
                    <h3 class="box-title"><strong>Edit Project</strong></h3>
                  </div>
                  <div class="box-body">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" class="form-control select2" style="width: 100%;">
                          @foreach($departments as $department)
                            <option value="{{ $department->id }}" @if($department->id == $project->department_id) selected @endif>{{ $department->name }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter project name" value="{{ $project->name or '' }}">
                      </div>

                      <div class="form-group">
                        <label>Owner</label>
                        <select name="owner_id" class="form-control select2" style="width: 100%;">
                          <option selected></option>
                          @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @if($employee->id == $project->owner_id) selected @endif>
                              {{
                                $employee->firstname . ' ' . $employee->lastname . ' [' . $employee->code . '] - '.
                                $employee->department->name . ', ' . $employee->permission->role->name
                              }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Code</label>
                        <input type="text" name='code' class="form-control" placeholder="Enter project code" value="{{ $project->code or '' }}">
                      </div>

                      <div class="form-group">
                        <label>Date range:</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <?php
                            if($project->start_date && $project->success_date)
                              $fromto = date('m/d/Y', strtotime($project->start_date)) . ' - ' . date('m/d/Y', strtotime($project->success_date));
                            else
                              $fromto = '';
                          ?>
                          <input name="fromto" type="text" class="form-control" id="reservation" value="{{ $fromto }}">
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Status:</label>
                        <div class="input-group">
                          <input type="checkbox" class="minimal" name="active" @if($project->active) checked @endif>Active Project
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter description here">{{ $project->description or '' }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $(".select2").select2();
      $(".timepicker").timepicker({
        minuteStep: 1,
        showMeridian: false,
      });

      $('#reservation').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
    });
  </script>
@endsection
