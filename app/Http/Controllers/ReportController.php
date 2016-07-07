<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Route;
use App\Model\Project;
use App\Model\ProjectActivity;
use App\Model\Timesheet;
use App\Model\Employee;
use App\Http\Requests;
use Excel;

class ReportController extends Controller
{
  public function index(Request $request)
  {
    $dataTables           = [];
    $dataTables['colums'] = ['empID', 'name', 'department'];
    $dataTables['datas']  = [];
    $projectSelected      = isset($request->project_code) ? $request->project_code : [];

    if(count($projectSelected))
    {
      $dataTables = $this->genDataTables($dataTables, $projectSelected);
    }

    return view('pages.report.index', [
        'dataTables'      => $dataTables,
        'projects'        => Project::all(),
        'projectSelected' => $projectSelected,
    ]);
  }

  public function toExcel(Request $request)
  {
    $dataTables           = [];
    $dataTables['colums'] = ['empID', 'name', 'department'];
    $dataTables['datas']  = [];
    $projectSelected      = isset($request->project_code) ? $request->project_code : [];

    if(count($projectSelected))
    {
      $dataTables = $this->genDataTables($dataTables, $projectSelected);
    }

    $datas = $dataTables['datas'];
    if(count($datas))
    {
      array_unshift($datas, $dataTables['colums']->toArray());

      $excel = Excel::create('report_' . date('d-m-Y'), function($excel) use ($datas) {
        $excel->sheet('department', function($sheet) use ($datas) {
          $sheet->fromArray($datas);
        });
      })->export('xlsx');
    }

    return back();
  }

  // Set Data
  private function genDataTables($dataTables, $projectSelected)
  {
    $dataTables['colums'] = collect($dataTables['colums'])->merge($projectSelected);
    $projects             = Project::whereIn('code', $projectSelected)->get();

    $empReports  = [];
    $employees   = [];
    $empData     = [];
    $projectHour = [];
    foreach($projects as $project)
    {
      if($project->projectActivities)
      {
        $activitiesID = $project->projectActivities->lists('id');
        $timesheets = Timesheet::whereIn('project_activity_id', $activitiesID)
                                ->groupBy('employee_id')
                                ->selectRaw('*, sum(work_hour) as sumWorkHour')
                                ->get();
        array_push($employees, $timesheets->lists('employee_id')->toArray());

        foreach($timesheets as $timesheet)
        {
          if(empty($empData[$timesheet->employee_id]))
          {
            $empData[$timesheet->employee_id] = collect([
              (@$timesheet->employee->code             ? $timesheet->employee->code : '-'),
              (@$timesheet->employee->fullName         ? $timesheet->employee->fullName : '-'),
              (@$timesheet->employee->department->name ? $timesheet->employee->department->name : '-'),
            ]);
          }

          $projectHour[$timesheet->employee_id][$project->code] = $timesheet->sumWorkHour;
        }
      }
    }

    foreach($empData as $index => $data)
    {
      $projectSum = [];
      foreach($projectSelected as $code)
      {
        array_push($projectSum, (isset($projectHour[$index][$code]) ? $projectHour[$index][$code] : '-'));
      }

      $dataTables['datas'][] = $data->merge($projectSum)->toArray();
    }

    return $dataTables;
  }
}
