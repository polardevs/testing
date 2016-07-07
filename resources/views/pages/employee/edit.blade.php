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

        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#edit" class="active" data-toggle="tab">Edit Employee</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="edit">
              <div class="tab-pane">
                <!-- Form Edit -->
                <form role="form" action="{{ action('EmployeeController@update', $employee->id) }}" method="POST">
                  {!! method_field('put') !!}
                  {!! csrf_field() !!}
                  <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" class="form-control select2" style="width: 100%;" @if(!$canEdit) disabled @endif>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}" @if($employee->department_id == $department->id) selected @endif>{{ $department->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Position</label>
                    <select name="position_id" class="form-control select2" style="width: 100%;" @if(!$canEdit) disabled @endif>
                      @foreach($positions as $position)
                        <option value="{{ $position->id }}" @if($employee->position_id == $position->id) selected @endif>{{ $position->name . ' (' . $position->department->name  . ')' }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Role</label>
                    <?php
                      if($employee->permission->role->isDeveloper())
                        $canEdit = false;
                    ?>
                    <select name="role_id" class="form-control select2" style="width: 100%;" @if(!$canEdit) disabled @endif>
                      @foreach($roles as $role)
                        <option value="{{ $role->id }}" @if($employee->permission && $employee->permission->role->id == $role->id) selected @endif>{{ $role->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Employee ID</label>
                    <input type="text" name="code" class="form-control" placeholder="Enter employee id" value="{{ $employee->code or '' }}" @if(!$canEdit) disabled @endif>
                  </div>

                  <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" name="firstname" class="form-control" placeholder="Enter your firstname" value="{{ $employee->firstname or '' }}" @if(!$canEdit) disabled @endif>
                  </div>

                  <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" name="lastname" class="form-control" placeholder="Enter your lastname" value="{{ $employee->lastname or '' }}" @if(!$canEdit) disabled @endif>
                  </div>

                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="can_login" @if($employee->can_login) checked @endif @if(!$canEdit) disabled @endif>
                        Can login
                      </label>

                      <label>
                        <input type="checkbox" name="active" @if($employee->active) checked @endif @if(!$canEdit) disabled @endif>
                        Active
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div>
                      <button type="submit" class="btn btn-primary" @if(!$canEdit) disabled @endif>Submit</button>
                    </div>
                  </div>
                </form>
                <!-- / Form Edit -->
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
    });
  </script>
@endsection
