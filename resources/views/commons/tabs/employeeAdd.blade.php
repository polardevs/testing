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
  <form role="form" action="{{ action('EmployeeController@store') }}" method="POST">
    {!! csrf_field() !!}
    <div class="form-group">
      <label>Department</label>
      <select name="department_id" class="form-control select2" style="width: 100%;">
        @foreach($departments as $department)
          <option value="{{ $department->id }}" @if(old('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>Position</label>
      <select name="position_id" class="form-control select2" style="width: 100%;">
        @foreach($positions as $position)
          <option value="{{ $position->id }}" @if(old('position_id') == $position->id) selected @endif>{{ $position->name . ' (' . $position->department->name . ')' }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>Role</label>
      <select name="role_id" class="form-control select2" style="width: 100%;">
        @foreach($roles as $role)
          <option value="{{ $role->id }}" @if(old('role_id') == $role->id) selected @endif>{{ $role->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>Employee ID</label>
      <input type="text" name="code" class="form-control" placeholder="Enter employe id" value="{{ old('code') }}">
    </div>

    <div class="form-group">
      <label>Firstname</label>
      <input type="text" name="firstname" class="form-control" placeholder="Enter your firstname" value="{{ old('firstname') }}">
    </div>

    <div class="form-group">
      <label>Lastname</label>
      <input type="text" name="lastname" class="form-control" placeholder="Enter your lastname" value="{{ old('lastname') }}">
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="can_login" checked>
          Can login
        </label>

        <label>
          <input type="checkbox" name="active" @if(old('active') == 'on') checked @endif>
          Active
        </label>
      </div>
    </div>

    <div class="form-group">
      <div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Submit</button>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Confirm to insert data</h4>
          </div>
          <div class="modal-body">
            <p>Please Confirm to save employee data</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  </form>
</div>

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $(".select2").select2();
    });
  </script>
@endsection
