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
  <form role="form" action="{{ action('PositionController@store') }}" method="POST">
    {!! csrf_field() !!}
    <div class="box-body">
      <div class="form-group">
        <label>Department</label>
        <select name="department_id" class="form-control select2" style="width: 100%;">
          @foreach($departments as $department)
            <option value="{{ $department->id }}" @if($department->id == old('department_id')) selected @endif>{{ $department->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter position name" name="{{ old('name') }}">
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
    });
  </script>
@endsection
