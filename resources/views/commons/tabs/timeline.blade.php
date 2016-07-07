<div class="tab-pane {{ @$active }}" id="timeline">
  <ul class="timeline timeline-inverse">
    <?php
      $byDateTimesheets = [];
      foreach($timesheets as $timesheet)
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
        @if($timesheet->projectActivity && $timesheet->employee)
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
                  if($timesheet->projectActivity
                      && $timesheet->projectActivity->project
                      && $timesheet->projectActivity->project->employeeProjects)
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
              </h3>

              <div class="timeline-body">
                {{ $timesheet->remark }}
              </div>
            </div>
          </li>
        @endif
      @endforeach
    @endforeach
    @if(count($byDateTimesheets) > 0)
      <li>
        <i class="fa fa-clock-o bg-gray"></i>
      </li>
    @endif
  </ul>
</div>
