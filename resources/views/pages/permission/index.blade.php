@extends('layouts.app')

@section('htmlheader_title')
  Role & Permission
@endsection

@section('contentheader_title')
  Role & Permission Management
@endsection

@section('contentheader_description')
  Setting you role & permission data
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Role & Permission</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#list" class="active" data-toggle="tab">List Table</a>
            </li>
            <li class="pull-right">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-fw fa-plus"></i><strong>Permission</strong>
              </button>
              <ul class="dropdown-menu">
                @foreach($roles->lists('name', 'id') as $roleID => $roleName)
                  <li><a href="{{ action('PermissionController@create', ['roleId' => $roleID]) }}">{{ $roleName }}</a></li>
                @endforeach
              </ul>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="list">
              @include('commons.tables.datatableFull', ['dataTables' => $dataTables])
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
