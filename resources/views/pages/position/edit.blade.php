@extends('layouts.app')

@section('htmlheader_title')
  Position
@endsection

@section('contentheader_title')
  Position Management
@endsection

@section('contentheader_description')
  Manage your position data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Position</li>
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
              <a href="#edit" class="active" data-toggle="tab">Edit Position</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="edit">
              <div class="tab-pane">
                <!-- Form Edit -->
                <form role="form" action="{{ action('PositionController@update', $position->id) }}" method="POST">
                  {!! method_field('put') !!}
                  {!! csrf_field() !!}
                  <div class="box-body">
                    <div class="form-group">
                      <label>Department</label>
                      <select name="department_id" class="form-control select2" style="width: 100%;">
                        @foreach($departments as $department)
                          <option value="{{ $department->id }}" @if($position->department_id == $department->id) selected @endif>{{ $department->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Enter department name" value="{{ $position->name or '' }}">
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
      $(".select2").select2();
    });
  </script>
@endsection
