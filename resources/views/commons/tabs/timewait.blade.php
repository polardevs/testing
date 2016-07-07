<style>
  .datepicker{z-index:1151 !important;}
</style>
<div class="tab-pane {{ @$active }}" id="timewait">
  <ul class="timeline timeline-inverse">
    <?php
      $byDateTimesheets = [];
      foreach($waitTimesheets as $timesheet)
      {
        $byDateTimesheets[$timesheet->stampDMY][] = $timesheet;
      }
    ?>

    @foreach($byDateTimesheets as $dateIndex => $timesheets)
      <li class="time-label">
        <span class="bg-red">
          {{ $dateIndex }}
        </span>
      </li>
      @foreach($timesheets as $timesheet)
        <li>
          @if($timesheet->approve_date > 0)
            <i class="fa fa-legal bg-green"></i>
          @else
            <i class="fa fa-envelope bg-yellow"></i>
          @endif

          <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i> work {{ $timesheet->work_hour . ' hour' }}</span>
            <h3 class="timeline-header">
              <strong>{{ $timesheet->employee->firstname }}</strong>:
              {!! $timesheet->projectActivity->sequenceName !!}
              <small>waiting to approve</small>

              <?php
                $flagApprove = false;
                if($timesheet->projectActivity && $timesheet->projectActivity->project && $timesheet->projectActivity->project->employeeProjects)
                {
                  $empProject = $timesheet->projectActivity->project->employeeProjects()->where('employee_id', Auth::user()->id)->first();
                  $flagApprove = $empProject ? $empProject->can_approve : $flagApprove;
                }
              ?>
              @if($timesheet->approve_date > 0)
                <span class="time label label-success pull-right" style="margin-right: 2px; margin-left: 2px;">approve</span>
              @elseif($flagApprove && Auth::user()->permission->role->isProjectManager() && $timesheet->employee_id != Auth::user()->id)
                <a href="{{ action('HomeController@approveTimesheet', $timesheet->id) }}">
                  <span class="time label label-warning pull-right" style="margin-right: 2px; margin-left: 2px;">approve</span>
                </a>
              @endif

              @if($timesheet->employee_id == Auth::user()->id)
                <a href="#">
                  <span class="time label label-primary pull-right"
                        style="margin-right: 2px; margin-left: 2px;"
                        data-toggle="modal"
                        data-target="#timesheetModal{{ $timesheet->id }}"
                        >edit</span>
                </a>
              @endif
            </h3>

            <!-- Modal Timesheet Edit -->
            <div class="modal fade" id="timesheetModal{{ $timesheet->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <form action="{{ action('TimesheetController@update', $timesheet->id) }}" method="POST">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">{!! $timesheet->projectActivity->sequenceName !!}</h4>
                    </div>
                    <div class="modal-body">

                      <div class="form-group">
                        <label>Work_date:</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input name="work_date" type="text" class="form-control pull-right js-datepicker" value="{{ $timesheet->datePicker }}" data-date-format="dd/mm/yyyy">
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
                        <input name="work_hour" type="text" class="form-control js-onlyNumberDot" placeholder="Enter your work hour" value="{{ $timesheet->work_hour }}">
                      </div>

                      <div class="form-group">
                        <label>Remark:</label>
                        <textarea name="remark" class="form-control" rows="5" placeholder="Enter your remark">{{ $timesheet->remark }}</textarea>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="timeline-body">
              {{ $timesheet->remark }}
            </div>
          </div>
        </li>
      @endforeach
    @endforeach
    @if(count($byDateTimesheets) > 0)
      <li>
        <i class="fa fa-clock-o bg-gray"></i>
      </li>
    @endif
  </ul>
</div>

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $(".select2").select2();
      $('.js-datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
      });

      $(".js-onlyNumberDot").keypress(function (e) {
        if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });

    });
  </script>
@endsection
