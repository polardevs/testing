<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Model\Department;
use App\Model\Employee;
use App\Model\EmployeeProject;
use App\Model\Permission;
use App\Model\Position;
use App\Model\Project;
use App\Model\ProjectActivity;
use App\Model\Role;
use App\Model\Timesheet;
use Auth;
use Excel;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $fromto         = $request->input('fromto') ? $request->input('fromto') : date("d-m-Y", strtotime("-1 month")) . ' - ' . date('d-m-Y');
    $timesheets     = $this->getWorkTimesheet(Auth::user(), $fromto);
    $waitTimesheets = $this->getWaitTimesheet(Auth::user(), $fromto);
    $employee       = Auth::user();
    $projects       = $employee->employeeProjects()->with('project.projectActivities')->get();
    $user           = Auth::user();

    return view('pages.home', [
      'employee'       => $employee,
      'empProjects'    => $projects,
      'timesheets'     => $timesheets,
      'waitTimesheets' => $waitTimesheets
    ]);
  }

  public function listTimesheet(Request $request)
  {
    $fromto     = $request->input('fromto') ? $request->input('fromto') : date("d-m-Y", strtotime("-1 month")) . ' - ' . date('d-m-Y');
    $timesheets = $this->getWorkTimesheet(Auth::user(), $fromto);

    $dataTables['colums'] = ['project', 'activity', 'employee', 'work date', 'work hour', 'status'];
    $dataTables['datas']  = [];

    foreach($timesheets as $timesheet)
    {
      if(
        $timesheet->projectActivity &&
        $timesheet->projectActivity->project &&
        $timesheet->employee
      )
      {
        $dataTables['datas'][] = [
          $timesheet->projectActivity->project->code,
          $timesheet->projectActivity->activity,
          $timesheet->employee->codeName,
          $timesheet->stampDMY,
          $timesheet->work_hour,
          ((!$timesheet->approve_date) ? $this->renderApproveButton(true, 'waiting', $timesheet) : $this->renderApproveButton(false, 'approved', $timesheet))
        ];
      }
    }

    return view('pages.timesheet.index', [
      'timesheets' => $timesheets,
      'fromto'     => $fromto,
      'dataTables' => $dataTables
    ]);
  }

  private function renderApproveButton($flaglink, $title, $timesheet)
  {
    $timesheetID = $timesheet->id;
    $title    = Auth::user()->id != $timesheet->employee_id ? $title : 'no access';
    $flaglink = Auth::user()->id != $timesheet->employee_id ? $flaglink : false;
    $label    = Auth::user()->id != $timesheet->employee_id ? 'success' : 'danger';
    $button = '';
    if($flaglink)
    {
      $button = '<a href="' . action('HomeController@approveTimesheet', $timesheetID) . '"><span class="time label label-warning" style="margin-right: 2px; margin-left: 2px;">' . $title . '</span></a>';
    }
    else
    {
      $button = '<span class="time label label-' . $label . '" style="margin-right: 2px; margin-left: 2px;">' . $title . '</span>';
    }

    return $button;
  }

  private function getWaitTimesheet($user, $fromto = '')
  {
    $timesheets = [];
    if($user->permission)
    {
      $roleName  = $user->permission->role->isAdmin() ? 'admin' :
                    ($user->permission->role->isManager() ? 'manager' :
                      ($user->permission->role->isProjectManager() ? 'projectManager' : 'general')
                    );
      $excepts   = $this->getExceptEmpID($roleName);

      if(!$user->permission->role->isProjectManager())
      {
        $timesheets = Timesheet::orderBy('work_date', 'desc')
                                 ->where('employee_id', $user->id)
                                 ->where('approve_date', '=', null);
      }
      elseif($user->permission->role->isProjectManager())
      {
        $empID     = $user->department->employees()
                                      ->whereNotIn('id', $excepts)
                                      ->lists('id');

        $timesheets = Timesheet::orderBy('work_date', 'desc')
                                 ->whereIn('employee_id', $empID)
                                 ->where('approve_date', '=', null);
      }

      if($fromto)
      {
        $from = date('Y-m-d', strtotime(trim(explode(' - ', $fromto)[0])));
        $to   = date('Y-m-d', strtotime(trim(explode(' - ', $fromto)[1])));

        if($from && $to)
          $timesheets->whereBetween('work_date', [$from, $to]);
      }

      if($timesheets)
        $timesheets = $timesheets->get();
    }

    $timesheets = $this->rejectTimesheet($timesheets);

    return $timesheets;
  }

  private function getWorkTimesheet($user, $fromto = '')
  {

    $timesheets = [];
    if($user->permission)
    {
      $roleName  = $user->permission->role->isAdmin() ? 'admin' :
                    ($user->permission->role->isManager() ? 'manager' :
                      ($user->permission->role->isProjectManager() ? 'projectManager' : 'general')
                    );
      $excepts   = $this->getExceptEmpID($roleName);

      if(!$user->permission->role->isProjectManager())
      {
        $timesheets = Timesheet::where('employee_id', $user->id)->orderBy('work_date', 'desc');
      }
      elseif($user->permission->role->isProjectManager())
      {
        $empID = $user->department->employees()->whereNotIn('id', $excepts)->lists('id');
        $timesheets = Timesheet::orderBy('work_date', 'desc')
                               ->whereIn('employee_id', $empID);
      }

      if($fromto)
      {
        $from = date('Y-m-d', strtotime(trim(explode(' - ', $fromto)[0])));
        $to   = date('Y-m-d', strtotime(trim(explode(' - ', $fromto)[1])));

        if($from && $to)
          $timesheets->whereBetween('work_date', [$from, $to]);
      }

      if($timesheets)
        $timesheets = $timesheets->get();
    }

    $timesheets = $this->rejectTimesheet($timesheets);

    return $timesheets;
  }

  public function approveTimesheet($id)
  {
    $timesheet = Timesheet::find($id);

    if($timesheet)
      $timesheet->update(['approve_date' => date('Y-m-d')]);

    return back();
  }

  public function getExceptEmpID($roleType)
  {
    $excepts = [];
    if($roleType == 'projectManager')
    {
      $excepts = Permission::whereIn('role_id', Role::projectManager('id'))
                             ->lists('employee_id');
    }
    elseif($roleType == 'manager')
    {
      $excepts = Permission::whereIn('role_id', Role::manager('id'))
                             ->lists('employee_id');
    }

    if(count($excepts) > 0)
    {
      $excepts = $excepts->reject(function ($value, $key) {
                    return $value == Auth::user()->id;
                 });
    }

    // if($roleType != 'admin') $excepts[] = Auth::user()->id;

    return $excepts;
  }

  public function backupToCSV()
  {
    $excel = Excel::create('csv' . date('d-m-Y'), function($excel) {
      $excel->sheet('department', function($sheet) {
        $sheet->fromModel(Department::all());
      });

      $excel->sheet('employee', function($sheet) {
        $sheet->fromModel(Employee::all());
      });

      $excel->sheet('employee_project', function($sheet) {
        $sheet->fromModel(EmployeeProject::all());
      });

      $excel->sheet('permission', function($sheet) {
        $sheet->fromModel(Permission::all());
      });

      $excel->sheet('position', function($sheet) {
        $sheet->fromModel(Position::all());
      });

      $excel->sheet('project', function($sheet) {
        $sheet->fromModel(Project::all());
      });

      $excel->sheet('project_activity', function($sheet) {
        $sheet->fromModel(ProjectActivity::all());
      });

      $excel->sheet('role', function($sheet) {
        $sheet->fromModel(Role::all());
      });

      $excel->sheet('timesheet', function($sheet) {
        $sheet->fromModel(Timesheet::all());
      });

    })->export('xlsx');

    return back();
  }

  private function rejectTimesheet($timesheets)
  {
    $result = $timesheets->reject(function ($timesheet) {
      $reject = true;
      if($timesheet->projectActivity && $timesheet->projectActivity->project && $timesheet->employee)
        $reject = false;

      return $reject;
    });

    return $result;
  }
}
