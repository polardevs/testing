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
  <form role="form" action="{{ action('ProjectController@store') }}" method="POST">
    {!! csrf_field() !!}
    <div class="box-header">
      <h3 class="box-title"><strong>Create Project</strong></h3>
    </div>
    <div class="box-body">
      <div class="col-md-6">
        <div class="form-group">
          <label>Department</label>
          <select name="department_id" class="form-control select2" style="width: 100%;">
            @foreach($departments as $department)
              <option value="{{ $department->id }}" @if(old('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter project name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
          <label>Owner</label>
          <select name="owner_id" class="form-control select2" style="width: 100%;">
            <option selected></option>
            @foreach($employees as $employee)
              <option value="{{ $employee->id }}" @if(old('owner_id') == $employee->id) selected @endif>
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
          <input type="text" name='code' class="form-control" placeholder="Enter project code" value="{{ old('code') }}">
        </div>

        <div class="form-group">
          <label>Date range:</label>
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input name="fromto" type="text" class="form-control" id="reservation" value="{{ old('fromto') }}">
          </div>
        </div>

        <div class="form-group">
          <label>Status:</label>
          <div class="input-group">
            <input type="checkbox" class="minimal" name="active" checked>Active Project
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group">
          <label>Description</label>
          <textarea class="form-control" name="description" rows="3" placeholder="Enter description here">{{ old('description') }}</textarea>
        </div>
      </div>
    </div>
    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>

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
          format: 'DD-MM-YYYY',
        }
      });

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
    });
  </script>
@endsection
