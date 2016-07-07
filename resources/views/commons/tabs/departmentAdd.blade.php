@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<div class="tab-pane {{ $active }}" id="timesheet">
  <form role="form" action="{{ action('DepartmentController@store') }}" method="POST">
    {!! csrf_field() !!}
    <div class="box-body">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter department name">
      </div>
      <div class="form-group">
        <label>Status:</label>
        <div class="input-group">
          <input type="checkbox" class="minimal" name="active" checked>Active Department
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
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
    });
  </script>
@endsection
