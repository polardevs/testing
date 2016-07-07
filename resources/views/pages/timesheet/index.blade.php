@extends('layouts.app')

@section('htmlheader_title')
  TIMESHEETS
@endsection

@section('contentheader_title')
  TIMESHEETS
@endsection

@section('contentheader_description')

@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Timesheets</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">SEARCH FROM WORK DATE</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <form action="{{ action('HomeController@listTimesheet') }}" method="GET">
                <div class="form-group col-md-11" style="margin-bottom: 15px">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input name="fromto" type="text" class="form-control" id="reservation" value="{{ $fromto }}">
                  </div>
                </div>

                <div class="col-md-1" style="margin-bottom: 15px">
                  <button type="submit" class="btn btn-primary form-control js-load-report"><i class="fa fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">TIMESHEETS DATA LIST</h3>
          </div>
          <div class="box-body">
            @include('commons.tables.datatableFull', ['name' => 'Department List Data'])
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @parent
  <script type="text/javascript">
    $('#reservation').daterangepicker({
      locale: {
          format: 'DD-MM-YYYY',
      }
    });
  </script>
@endsection
