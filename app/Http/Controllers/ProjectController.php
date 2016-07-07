<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Department;
use App\Model\Project;
use App\Model\Employee;
use App\Model\ProjectActivity;
use App\Model\EmployeeProject;
use Auth;

class ProjectController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $projects             = $this->genProject(Auth::user());
    $dataTables           = [];
    $dataTables['colums'] = ['code', 'project name', 'department', 'owner', 'description', 'from', 'to', 'status', '#', '#', '#'];
    $dataTables['datas']  = [];

    foreach($projects as $project)
    {
      $dataTables['datas'][] = [
        '<a href="' . action('ProjectController@edit', $project->id) . '"><strong>' . $project->code . '</strong></a>',
        $project->name,
        $project->department ? '<a href="' . action('DepartmentController@edit', $project->department->id) . '"><strong>' . $project->department->name . '</strong></a>' : 'none',
        $project->owner ? '<a href="' . action('EmployeeController@edit', $project->owner->id) . '"><strong>' . $project->owner->code . '</strong></a>' : 'none',
        $project->description,
        $project->start_date,
        $project->success_date,
        ($project->active ? 'active' : 'denied'),
        $this->renderLink('ProjectController', 'activity', $project, 'activity'),
        $this->renderLink('ProjectController', 'employee', $project, 'employee'),
        $this->renderLink('ProjectController', 'destroy', $project, 'delete'),
      ];
    }

    $employees = Employee::whereHas('permission.role', function ($q) {
      $q->whereNotIn('name', ['employee general']);
    })->get();

    return view('pages.project.index', [
      'dataTables'  => $dataTables,
      'departments' => Department::all(),
      'employees'   => $employees
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      "department_id" => "required|integer",
      "name"          => "required|string",
      "owner_id"      => "integer",
      "code"          => "required|string",
      "fromto"        => "required|string",
      "description"   => "string",
    ]);

    $date                    = $this->splitDateIsFromTo($request->input('fromto'));
    $request['success_date'] = $date['success_date'];
    $request['start_date']   = $date['start_date'];

    Project::create($request->all());

    return redirect()->action('ProjectController@index');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $project = Project::find($id);

    if(!$project)
      return back();

    // $exceptEmpID = $project->employeeProjects->lists('employee_id');
    $exceptEmpID = [];
    $employees   = Employee::whereNotIn('id', $exceptEmpID)
                           ->whereHas('permission.role', function ($q) {
                               $q->whereNotIn('name', ['employee general']);
                             })
                           ->get();

    return view('pages.project.edit', [
      'project'     => $project,
      'departments' => Department::all(),
      'employees'   => $employees
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      "department_id" => "required|integer",
      "name"          => "required|string",
      "owner_id"      => "integer",
      "code"          => "required|string",
      "fromto"        => "required|string",
      "description"   => "string",
    ]);

    $project = Project::find($id);

    if(!$project)
      return back();

    $date = $this->splitDateIsFromTo($request->input('fromto'));
    $request['success_date'] = $date['success_date'];
    $request['start_date']   = $date['start_date'];

    $project->update($request->all());

    return redirect()->action('ProjectController@index');
  }

  public function destroy($id)
  {
    $project = Project::find($id);

    if($project)
    {
      $project->delete();
    }

    return back();
  }

  public function splitDateIsFromTo($strDate)
  {
    $date['start_date']   = date('Y-m-d', strtotime(explode(' - ', $strDate)[0]));
    $date['success_date'] = date('Y-m-d', strtotime(explode(' - ', $strDate)[1]));

    return $date;
  }

  public function employee($projectId)
  {
    $project               = Project::find($projectId);
    $employeesProject      = EmployeeProject::where('project_id', $project->id)->get();
    $employees             = Employee::whereNotIn('id', $employeesProject->lists('employee_id'))->get();
    $dataTables['colums']  = ['#', 'emp_code', 'firstname', 'lastname', 'department', 'can_approve', 'active'];

    if(Auth::user()->permission->role->isManager())
      array_push($dataTables['colums'], 'action');

    $dataTables['datas']   = [];

    if($employees)
    {
      $empsProject  = EmployeeProject::where('project_id', $projectId)->with('employee')->get();

      foreach($empsProject as $index => $empProject)
      {
        if($empProject->employee)
        {
          $data = [
            $index + 1,
            $empProject->employee->code,
            $empProject->employee->firstname,
            $empProject->employee->lastname,
            $empProject->employee->department->name,
            $empProject->can_approve ? 'approve' : 'not approve',
            $empProject->active ? 'active' : 'not active'
          ];

          if(Auth::user()->permission->role->isManager())
            array_push($data, $this->renderLink('EmployeeProjectController', 'destroy', $empProject, 'delete'));

          $dataTables['datas'][] = $data;
        }
      }
    }

    return view('pages.project.employeeAdd', [
      'project'           => $project,
      'employeesProject'  => $employeesProject,
      'employees'         => $employees,
      'dataTables'        => $dataTables
    ]);

  }

  public function activity($projectId)
  {
    $project           = Project::find($projectId);

    if(!$project)
      return redirect()->action('ProjectController@index');

    $projectActivities = ProjectActivity::where('project_id', $project->id)
                                          ->orderBy('sequence')
                                          ->orderBy('sub_sequence')
                                          ->orderBy('name')
                                          ->get();

    $dataTables['colums']  = ['sequence', 'sub', 'activity', 'start date', 'end date', 'active', 'action'];
    $dataTables['datas']   = [];

    if($projectActivities)
    {
      foreach($projectActivities as $index => $projectActivity)
      {
        $dataTables['datas'][] = [
          $projectActivity->sequence,
          $projectActivity->sub_sequence,
          '<a href="#" data-toggle="modal" data-target="#activity' . $projectActivity->id . '">'
            . '<strong>' . $projectActivity->name . '</strong>'
            . '</a>',
          $projectActivity->startDateDMY,
          $projectActivity->endDateDMY,
          $projectActivity->active ? 'active' : 'not active',
          $this->renderLink('ProjectActivityController', 'destroy', $projectActivity, 'delete'),
        ];
      }
    }

    return view('pages.project.activityAdd', [
      'project'           => $project,
      'projectActivities' => $projectActivities,
      'dataTables'        => $dataTables
    ]);
  }

  public function addAll($projectId)
  {
    $employeesProject = EmployeeProject::where('project_id', $projectId)->get();
    $employees        = Employee::whereNotIn('id', $employeesProject->lists('employee_id'))->get();

    foreach($employees as $employee)
    {
      $saveItems = [
        'project_id'  => $projectId,
        'employee_id' => $employee->id,
        'active'      => true,
        'can_approve' => false
      ];

      EmployeeProject::create($saveItems);
    }

    return back();
  }

  public function deleteAll($projectId)
  {
    $project = Project::find($projectId);

    if($project)
      $project->employeeProjects()->delete();

    return back();
  }

  /**
   * Render tag html
   */
  private function renderLink($controller, $action, $obj, $text, $label = 'info')
  {
    if($action == 'destroy')
    {
      $label  = 'warning';
      $button = '<span class="label label-' . $label . '" style="cursor:pointer;" onclick="$(this).find(\'form\').submit();">
                  ' . $text . '
                  <form action="' . action($controller . '@' . $action, $obj->id) . '" method="POST" hidden>'
                  . method_field('delete')
                  . csrf_field()
                  . '</form>
                </span>';

      return $button;

    }
    else
    {
      return '<a href="' . action($controller . '@' . $action, $obj->id) . '" ><strong> ' . $text . ' </strong></a>';
    }
  }

  private function genProject($user)
  {
    $projects = [];
    if($user->permission->role->isAdmin())
    {
      $projects = Project::all();
    }
    elseif($user->permission->role->isProjectManager())
    {
      $projects = Project::where('owner_id', $user->id)
                         ->orWhereHas('employeeProjects', function ($q) use ($user) {
                            $q->where('employee_id', $user->id);
                         })->get();
    }
    elseif($user->permission->role->isGeneral())
    {
      $projects = Project::whereHas('employeeProjects', function ($q) use ($user) {
        $q->where('employee_id', $user->id);
      })->get();
    }

    return $projects;
  }

}
