@extends('layouts.app')

@section('htmlheader_title')
  Project
@endsection

@section('contentheader_title')
  Project - {{ $project->name }}
@endsection

@section('contentheader_description')
  employee project
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li><a href="{{ action('ProjectController@index') }}"> Project</a></li>
  <li class="active">Employee</li>
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

          <!-- Choice -->
          @if(Auth::user()->permission->role->isManager())
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Enter Employee Data</h3>
              </div>
              <div class="box-body">
                <form id="formAddEmployee" action="{{ action('EmployeeProjectController@store', $project->id) }}" method="POST">
                  {!! csrf_field() !!}
                  <input name="project_id" value={{ $project->id }} hidden>
                  <div class="row">
                    <div class="form-group col-md-6" style="margin-bottom: 15px">
                      <select name="employee_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Employee">
                        @foreach($employees as $employee)
                          <option value="{{ $employee->id }}">
                            {{ $employee->firstname . ' ' . $employee->lastname . '  (' . $employee->code . ')' }}
                          </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group col-md-2" style="margin-bottom: 15px">
                      <div class="input-group">
                        <input name="can_approve" type="checkbox" class="minimal"> can approve
                      </div>
                    </div>

                    <div class="form-group col-md-1" style="margin-bottom: 15px">
                      <div class="input-group">
                        <input name="active" type="checkbox" class="minimal" checked> Active
                      </div>
                    </div>

                      <div class="col-md-1" style="margin-bottom: 15px">
                        <button id="addAjax" type="button" class="btn btn-primary form-control" data-toggle="modal" data-target="#addModal">ADD</button>
                      </div>

                      <div class="col-md-1" style="margin-bottom: 15px">
                        <button type="button" class="btn btn-warning form-control" data-toggle="modal" data-target="#addAllModal">
                          <i class="fa fa-plus-circle"> ALL</i>
                        </button>
                      </div>

                      <div class="col-md-1" style="margin-bottom: 15px">
                        <button type="button" class="btn btn-danger form-control" data-toggle="modal" data-target="#deleteAllModal">
                          <i class="fa fa-minus-circle"> ALL</i>
                        </button>
                      </div>

                  </div>

                  <!-- Modal Confirm add selected employee -->
                  <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Confirm to insert data</h4>
                        </div>
                        <div class="modal-body">
                          <p>Please Confirm to add this employees to this project</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Confirm add all -->
                  <div class="modal fade" tabindex="-1" role="dialog" id="addAllModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Confirm to insert data</h4>
                        </div>
                        <div class="modal-body">
                          <p>Please Confirm to add all employee to this project</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" onclick="$('#addAll').submit();">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Confirm delete all -->
                  <div class="modal fade" tabindex="-1" role="dialog" id="deleteAllModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Confirm to remove data</h4>
                        </div>
                        <div class="modal-body">
                          <p>Please confirm to remove all employees from this project</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-danger" onclick="$('#deleteAll').submit();">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>

                </form>
                <form id="addAll" action="{{ action('ProjectController@addAll', $project->id) }}" method="POST" hidden>
                  {!! csrf_field() !!}
                </form>
                <form id="deleteAll" action="{{ action('ProjectController@deleteAll', $project->id) }}" method="POST" hidden>
                  {!! csrf_field() !!}
                </form>
              </div>
              <!-- /.box-body -->
            </div>
          @endif

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">List Employee</h3>
              <a class="pull-right" href="{{ action('ProjectController@activity', $project->id) }}">
                <strong>Add activity to this project </strong>
              </a>
            </div>
            <div class="box-body">
              <div id="renderList">
                <!-- content -->
                @include('commons.tables.datatableFull')
                <!-- /. content -->
              </div>
            </div>
            <!-- /.box-body -->
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

      $('#example1').DataTable();

      $('#fromto').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });

      $(".js-onlyNumber").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });
    });
  </script>
@endsection
