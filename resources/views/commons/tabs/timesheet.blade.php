<div class="tab-pane {{ @$active }}" id="timesheet">
  @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <form role="form" action="{{ action('TimesheetController@store') }}" method="POST">
    {!! csrf_field() !!}

    <div class="form-group">
      <label>Work_date:</label>
      <div class="input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input name="work_date" type="text" class="form-control pull-right" id="datepicker" data-date-format="dd/mm/yyyy">
      </div>
    </div>
    <div class="form-group">
      <label>Project Activity</label>
      <select name="project_activity_id" class="form-control select2" style="width: 100%;">
        <?php
          $now = date("Y-m-d");
        ?>
        @foreach($empProjects as $empProject)
          <?php
            if($empProject->project && $empProject->project->projectActivities)
            {
              $activities = $empProject->project->projectActivities()
                                                ->where('start_date', '<=', $now)
                                                ->where('end_date', '>', $now)
                                                ->get()
                                                ->sortBy('name')
                                                ->sortBy('sub_sequence')
                                                ->sortBy('sequence');
            }
          ?>
          @if(!empty($activities) && $activities->count() > 0 && $empProject->project && $empProject->project->start_date <= $now && $empProject->project->success_date > $now)
            <optgroup label="{{ $empProject->project->name }}">
            @foreach($activities as $activity)
              <option value="{{ $activity->id }}" @if(old('project_activity_id')) selected @endif>
                {!! $activity->sequenceChoice !!}
              </option>
            @endforeach
            </optgroup>
          @endif
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>Work Hour :</label>
      <input name="work_hour" type="text" class="form-control js-onlyNumberDot" placeholder="Enter your work hour" value="{{ old('work_hour') }}">
    </div>

    <div class="form-group">
      <label>Remark:</label>
      <textarea name="remark" class="form-control" rows="5" placeholder="Enter your remark">{{ old('remark') }}</textarea>
    </div>

    <div class="form-group">
      <div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $(".select2").select2();
      $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
      });

      $(".js-onlyNumberDot").keypress(function (e) {
        if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });

    });
  </script>
@endsection
