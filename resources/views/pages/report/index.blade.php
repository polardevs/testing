@extends('layouts.app')

@section('htmlheader_title')
  Report
@endsection

@section('contentheader_title')
  Report
@endsection

@section('contentheader_description')
  timesheet report
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li><a href="{{ action('ReportController@index') }}"> Project</a></li>
  <li class="active">Employee</li>
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

          <!-- Choice -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Search report by project</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <form id="form-report" action="{{ action('ReportController@index') }}" method="GET">
                  <div class="form-group col-md-10" style="margin-bottom: 15px">
                    <select name="project_code[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Project">
                      @foreach($projects as $project)
                        <option value="{{ $project->code }}" @if(in_array($project->code, $projectSelected)) selected @endif>
                          {{ $project->code . ' : ' . $project->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-1" style="margin-bottom: 15px">
                    <button type="button" class="btn btn-default form-control js-submit" data-actionurl="{{ action('ReportController@toExcel') }}">
                      <i class="fa fa-save"></i>
                    </button>
                  </div>
                  <div class="col-md-1" style="margin-bottom: 15px">
                    <button type="button" class="btn btn-primary form-control js-submit" data-actionurl="{{ action('ReportController@index') }}">
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </form>
              </div>

              <h3><strong>Work Hour Report</strong></h3>
              <table class="table table-striped">
                <tr>
                  <th class="text-center" style="width: 10px">{{ $dataTables['colums'][0] or '-' }}</th>
                  <th class="text-center">{{ $dataTables['colums'][1] or '-' }}</th>
                  <th class="text-center">{{ $dataTables['colums'][2] or '-' }}</th>
                  <th class="text-center" style="width: 80px">{{ $dataTables['colums'][3] or '-' }}</th>
                  <th class="text-center" style="width: 80px">{{ $dataTables['colums'][4] or '-' }}</th>
                  <th class="text-center" style="width: 80px">{{ $dataTables['colums'][5] or '-' }}</th>
                  <th class="text-center" style="width: 80px">{{ $dataTables['colums'][6] or '-' }}</th>
                  <th class="text-center" style="width: 80px">{{ $dataTables['colums'][7] or '-' }}</th>
                </tr>
                @foreach($dataTables['datas'] as $data)
                  <tr>
                    <td class="text-center">{{ $data[0] or '-' }}</td>
                    <td>
                      {{ $data[1] or '-' }}
                      {{--
                        <i style="cursor:pointer;" class="fa fa-search" data-toggle="modal" data-target="#empDetail{{ $data[0] }}"></i>
                      --}}
                    </td>
                    <td>{{ $data[2] or '-' }}</td>
                    <td class="text-center">{!! (isset($data[3]) && $data[3] != '-') ? ('<span class="badge bg-light-blue">' . $data[3] . '</span>') : '-' !!}</td>
                    <td class="text-center">{!! (isset($data[4]) && $data[4] != '-') ? ('<span class="badge bg-light-blue">' . $data[4] . '</span>') : '-' !!}</td>
                    <td class="text-center">{!! (isset($data[5]) && $data[5] != '-') ? ('<span class="badge bg-light-blue">' . $data[5] . '</span>') : '-' !!}</td>
                    <td class="text-center">{!! (isset($data[6]) && $data[6] != '-') ? ('<span class="badge bg-light-blue">' . $data[6] . '</span>') : '-' !!}</td>
                    <td class="text-center">{!! (isset($data[7]) && $data[7] != '-') ? ('<span class="badge bg-light-blue">' . $data[7] . '</span>') : '-' !!}</td>
                  </tr>
                @endforeach
              </table>
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
      $(".select2").select2({
        maximumSelectionLength: 5,
      });
      $('#example1').DataTable();
    });

    $('.js-submit').click(function () {
      $('#form-report').attr('action', $(this).data('actionurl'));
      $('#form-report').submit();
    });
  </script>
@endsection
