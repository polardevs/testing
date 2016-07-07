@extends('layouts.app')

@section('htmlheader_title')
  Permission
@endsection

@section('contentheader_title')
  Permission Add Data
@endsection

@section('contentheader_description')
  Setting your permission
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li><a href="{{ action('PermissionController@index') }}">Permission</a></li>
  <li class="active">Add Permission</li>
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
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Select Employee</h3>
          </div>
          <div class="box-body">
            <form action="{{ action('PermissionController@store') }}" method="POST" role="form">
              {!! csrf_field() !!}
              <input type="text" name="role_id" value="{{ $role->id }}" hidden>
              <div class="row">
                <div class="col-md-11">
                  <select name="employee_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select Employee to add permission">
                    @foreach($employees as $employee)
                      <option value="{{ $employee->id }}">
                        {{ $employee->firstname . ' ' . $employee->lastname . '  (' . $employee->code . ')' }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1" style="margin-bottom: 15px">
                  <button type="submit" class="btn btn-default form-control">Add</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">List Permission</h3>
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
    $(".select2").select2();
  </script>
@endsection
