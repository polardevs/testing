@extends('layouts.app')

@section('htmlheader_title')
  Project
@endsection

@section('contentheader_title')
  Project - {{ $project->name }}
@endsection

@section('contentheader_description')
  project activity
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li><a href="{{ action('ProjectController@index') }}"> Project</a></li>
  <li class="active">Activity</li>
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
              <h3 class="box-title">Enter Activity Data</h3>
            </div>
            <div class="box-body">
              <form id="formAddActivity" action="{{ action('ProjectActivityController@store', $project->id) }}" method="POST">
                {!! csrf_field() !!}
                <input name="project_id" value="{{ $project->id }}" hidden>
                <div class="row">
                  <div class="col-md-3" style="margin-bottom: 15px">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input id="fromto" name="fromto" type="text" class="form-control" placeholder="select your date range ..." value="{{ old('fromto') }}">
                    </div>
                  </div>
                  <div class="col-md-2" style="margin-bottom: 15px">
                    <div class="input-group">
                      <input name="sequence" type="number" step="1" class="form-control js-onlyNumber" placeholder="Sequence" value="{{ old('sequence')? old('sequence') : 0 }}">
                      <span class="input-group-addon" style="border: 0px;">.</span>
                      <input name="sub_sequence" type="number" step="1" class="form-control js-onlyNumber" placeholder="Sub" value="{{ old('sequence')? old('sequence') : 0 }}">
                    </div>
                  </div>
                  <div class="col-md-5" style="margin-bottom: 15px">
                    <input name="name" type="text" class="form-control" placeholder="Enter activity name ..." value="{{ old('name') }}">
                  </div>
                  <div class="col-md-1" style="margin-bottom: 15px">
                    <div class="input-group">
                      <input name="active" type="checkbox" class="minimal" checked> Active
                    </div>
                  </div>
                  <div class="col-md-1" style="margin-bottom: 15px">
                    <!-- <button id="addActivity" type="button" class="btn btn-default form-control" data="xxItenmNumberxx">Add</button> -->
                    <button type="submit" class="btn btn-default form-control">Add</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.box-body -->
          </div>

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">List activities</h3>
              <a class="pull-right" href="{{ action('ProjectController@employee', $project->id) }}">
                <strong>Add employee to this project </strong>
              </a>
            </div>
            <div class="box-body">
              @include('commons.tables.datatableFull')
            </div>
          </div>

          @foreach($projectActivities as $activity)
            <form action="{{ action('ProjectActivityController@update', $activity->id) }}" method="POST">
              {!! method_field('PUT') !!}
              {!! csrf_field() !!}
              <div id="activity{{ $activity->id }}" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">{{ $activity->sequenceChoice }}</h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label>Date range</label>
                        <input name="fromto" type="text" class="form-control js-fromto" placeholder="select your date range ..." value="{{ $activity->dateRange }}">
                      </div>

                      <div class="form-group">
                        <label>Sequence - Subsequence</label>
                        <div class="input-group">
                          <input name="sequence" type="number" step="1" class="form-control js-onlyNumber" placeholder="Sequence" value="{{ $activity->sequence }}">
                          <span class="input-group-addon" style="border: 0px;">.</span>
                          <input name="sub_sequence" type="number" step="1" class="form-control js-onlyNumber" placeholder="Sub" value="{{ $activity->sub_sequence }}">
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Activity name</label>
                        <input name="name" type="text" class="form-control" placeholder="Enter activity name ..." value="{{ $activity->name }}">
                      </div>

                      <div class="form-group">
                        <input name="active" type="checkbox" class="minimal" @if($activity->active) checked @endif> Active
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          @endforeach

      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $(".select2").select2();
      $(".timepicker").timepicker({
        minuteStep: 1,
        showMeridian: false,
      });

      $('#fromto').daterangepicker({
        locale: {
          format: 'DD-MM-YYYY',
        }
      });

      $('.js-fromto').daterangepicker({
        locale: {
          format: 'DD-MM-YYYY',
        }
      });

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });

      $(".js-onlyNumber").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });

      /*
       *  Render list data
       */
      var projectActivity = {
        items: [],
        allItems: [],
        html: '',

        setItems: function(data){
          this.items = data;
        },

        setAllItems: function(data){
          this.allItems = data;
        },

        setDataTables: function(){
          $("#example1").dataTable().fnDestroy();
          $('#example1').DataTable({
            responsive: true,
            data: (this.allItems),
            columns: [
              '#',
              'sequence',
              'sub',
              'activity',
              'start date',
              'end date',
              'active'
            ],
          });
        },

        saveActivity: function(){
          var activity = this.items;
          var allItems = [];

          $.ajax({
            url: '{{ action('ProjectActivityController@store') }}',
            type: "post",
            data: {
              'project_id'   : {{ $project->id }},
              '_token'       : '{{ csrf_token() }}',
              'fromto'       : activity.fromto,
              'sequence'     : activity.sequence,
              'sub_sequence' : activity.sub_sequence,
              'name'         : activity.name,
              'active'       : activity.active
            },
            success: function(data){
              projectActivity.setAllItems(data)
              projectActivity.setDataTables()
            }
          });
        }
      };

      $('#addActivity').on('click',
        function () {
        var arr = [];
        var flag = true;
        var content = '';

        $("input", $("#formAddActivity")).each(function(item) {
          arr[this.id] = this.value;

          if(this.value == '')
            flag = false;
        });

        if(flag)
        {
          // projectActivity.setItems(arr);
          // projectActivity.saveActivity();
        }

      });

    });
  </script>
@endsection
