<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Role;
use App\Model\Employee;
use App\Model\Permission;

class PermissionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $roles = Role::with('permissions.employee.department')->get();

    $dataTables = [];
    $dataTables['colums'] = ['role', 'employee code', 'department', 'name', 'action'];
    $dataTables['datas']  = [];

    foreach($roles as $role)
    {
      foreach($role->permissions as $permission)
      {
        if($permission->employee)
        {
          $dataTables['datas'][] = [
            $role->name,
            '<a href="' . action('EmployeeController@edit', $permission->employee->id) . '">' . $permission->employee->code . '</a>',
            $permission->employee->department->name,
            $permission->employee->firstname,
            $this->renderLink('PermissionController', 'destroy', $permission, 'delete'),
            // '<a href="' . action('PermissionController@edit', $permission->id) . '">edit</a>'
          ];
        }
      }
    }

    return view('pages.permission.index', [
      'roles'      => $roles,
      'dataTables' => $dataTables
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    $role        = Role::find($request->roleId);
    if(!$role)
      return back();

    $permissions           = $role->permissions;
    $employees             = Employee::whereNotIn('id', Permission::lists('employee_id'))->get();
    // $employees             = Employee::whereNotIn('id', $role->permissions->lists('employee_id'))->get();
    $dataTables['colums']  = ['#', 'role', 'emp_code', 'name', 'department', 'action'];
    $dataTables['datas']   = [];

    if($permissions)
    {
      foreach($permissions as $index => $permission)
      {
        $dataTables['datas'][] = [
          $index + 1,
          $permission->role->name,
          $permission->employee->code,
          $permission->employee->firstname,
          $permission->employee->department->name,
          $this->renderLink('PermissionController', 'destroy', $permission, 'delete'),
        ];
      }
    }

    return view('pages.permission.add', [
      'role'       => $role,
      'dataTables' => $dataTables,
      'employees'  => $employees
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
      'role_id'     => 'required|integer',
      'employee_id' => 'required',
    ]);

    foreach($request->employee_id as $empId)
    {
      Permission::create([
        'role_id'     => $request->role_id,
        'employee_id' => $empId
      ]);
    }

    return back();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
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
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $permission = Permission::find($id);

    if($permission)
      $permission->delete();

    return back();
  }

  /**
   * Render tag html
   */
  private function renderLink($controller, $action, $obj, $text, $label = 'info')
  {
    if($action == 'destroy')
    {
      $label = 'warning';

      return '<span class="label label-' . $label . '" style="cursor:pointer;" onclick="$(this).find(\'form\').submit();">
                ' . $text . '
                <form action="' . action($controller . '@' . $action, $obj->id) . '" method="POST" hidden>'
                . method_field('delete')
                . csrf_field()
                . '</form>
              </span>';
    }
    else
    {
      return '<a href="' . action($controller . '@' . $action, $obj->id) . '" ><strong> ' . $text . ' </strong></a>';
    }
  }
}
