@extends('layouts.app')

@section('htmlheader_title')
  Department
@endsection

@section('contentheader_title')
  Department Management
@endsection

@section('contentheader_description')
  Manage your department data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Department</li>
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

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#edit" class="active" data-toggle="tab">Edit Department</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="edit">
              <div class="tab-pane">
                <!-- Form Edit -->
                <form role="form" action="{{ action('DepartmentController@update', $department->id) }}" method="POST">
                  {!! method_field('put') !!}
                  {!! csrf_field() !!}
                  <div class="box-body">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Enter department name" value="{{ $department->name or '' }}">
                    </div>
                    <div class="form-group">
                      <label>Status:</label>
                      <div class="input-group">
                        <input type="checkbox" class="minimal" name="active" @if($department->active) checked @endif> Active Department
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
    });
  </script>
@endsection
