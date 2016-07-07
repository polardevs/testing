<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Employee;
use App\Model\Department;
use App\Model\Position;
use App\Model\Permission;
use App\Model\Role;
use Auth;

class EmployeeController extends Controller
{
    public function index()
    {
      $employees            = $this->getEmployees(Auth::user());
      $dataTables           = [];
      $dataTables['colums'] = ['id', 'department', 'position', 'role', 'firstname', 'lastname', 'action'];
      $dataTables['datas']  = [];

      foreach($employees as $employee)
      {
        $department = (empty($employee->department))? 'none' : $employee->department->name;
        $position   = (empty($employee->position))? 'none'   : $employee->position->name;
        $roleName   = ($employee->permission && $employee->permission->role)? $employee->permission->role->name : 'none';

        $dataTables['datas'][] = [
          $this->renderClickEdit($employee, $employee->code),
          $department,
          $position,
          $roleName,
          $employee->firstname,
          $employee->lastname,
          $this->renderClickDelete($employee, 'delete')
        ];
      }

      $roles = Role::where('active', true)
                     ->whereNotIn('name', Role::developer())
                     ->get();

      return view('pages.employee.index', [
        'dataTables'  => $dataTables,
        'departments' => Department::all(),
        'positions'   => Position::all(),
        'roles'       => $roles,
      ]);
    }

    public function store(Request $request)
    {
      if($request->input('active') != 'on')
        $request['active'] = false;

      $this->validate($request, [
        'department_id' => 'required|integer',
        'position_id'   => 'required|integer',
        'role_id'       => 'required|integer',
        'code'          => 'required|unique:employee',
        'firstname'     => 'required|string',
        'lastname'      => 'required|string',
        // 'can_login'     => 'required|string',
        // 'active'        => 'required|string',
      ]);

      $employee = Employee::create($request->all());
      $employee->permission()->create([
        'employee_id' => $employee->id,
        'role_id'     => $request->role_id
      ]);

      if(!$employee)
        return back();

      return redirect()->action('EmployeeController@index');
    }

    public function edit($id)
    {
      // if(!Auth::user()->permission->role->isAdmin())
      //   return view('errors.404');

      $employee         = Employee::find($id);
      $roles            = Role::where('active', true)
                              ->whereNotIn('name', Role::Developer())
                              ->get();

      if(!$employee)
        return back();

      return view('pages.employee.edit', [
        'employee'    => $employee,
        'departments' => Department::all(),
        'positions'   => Position::all(),
        'roles'       => $roles,
        'canEdit'     => Auth::user()->permission->role->isAdmin() ? true : false
      ]);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'department_id' => 'required|integer',
        'position_id'   => 'required|integer',
        'role_id'       => 'required|integer',
        'code'          => 'required',
        'firstname'     => 'required|string',
        'lastname'      => 'required|string',
        // 'can_login'     => 'required|string',
        // 'active'        => 'required|string',
      ]);

      $employee = Employee::find($id);
      if($employee->code != $request->input('code') && Employee::where('code', $request->input('code'))->count() > 0)
        return back();

      if(!$employee)
        return back();

      if($request->input('active') != 'on')
        $request['active'] = false;

      $employee->update($request->all());
      $employee->permission->update([
        'role_id' => $request->role_id
      ]);

      return redirect()->action('EmployeeController@index');
    }

    public function destroy($id)
    {
      $employee = Employee::find($id);

      if($employee)
      {
        if(!$employee->projects->count() || !$employee->employeeProjects->count())
          $employee->delete();
      }

      return back();
    }

    /**
     * Render tag html
     */
    private function renderClickDelete($employee, $text)
    {
      if(Auth::user()->permission->role->isAdmin())
      {
        return '<span class="label label-warning" data-toggle="tooltip" title="It\'s has related" style="cursor:not-allowed;" disabled>Delete</span>';
      }

      if($employee->projects->count() || $employee->employeeProjects->count())
      {
        return '<span class="label label-warning" data-toggle="tooltip" title="It\'s has related" style="cursor:not-allowed;" disabled>Delete</span>';
      }
      else
      {
        return '<span class="label label-warning" style="cursor:pointer;" onclick="$(this).find(\'form\').submit();">
                  ' . $text . '
                  <form action="' . action('EmployeeController@destroy', $employee->id) . '" method="POST" hidden>'
                  . method_field('delete')
                  . csrf_field()
                  . '</form>
                </span>';
      }
    }

    private function renderClickEdit($employee, $text)
    {
      return '<a href="' . action('EmployeeController@edit', $employee->id) . '" ><strong> ' . $text . ' </strong></a>';
    }

    private function getEmployees($user)
    {
      $employees = [];
      if($user->permission->role->isAdmin())
      {
        $employees = Employee::all();
      }
      elseif($user->permission->role->isManager())
      {
        $excepts   = Permission::whereIn('role_id', Role::manager('id'))->lists('employee_id');
        $employees = Employee::where('department_id', $user->department_id)
                               ->whereNotIn('id', $excepts)
                               ->get();
      }
      elseif($user->permission->role->isProjectManager())
      {
        $excepts   = Permission::whereIn('role_id', Role::projectManager('id'))->lists('employee_id');
        $employees = Employee::where('department_id', $user->department_id)
                               ->whereNotIn('id', $excepts)
                               ->get();
      }

      return $employees;
    }
}
