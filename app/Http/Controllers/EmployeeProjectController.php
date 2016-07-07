<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\EmployeeProject;

class EmployeeProjectController extends Controller
{
    public function store(Request $request)
    {
      $this->validate($request, [
        'project_id'    => 'required|integer',
        'employee_id'   => 'required',
      ]);

      if(count($request->employee_id) <= 0)
        return back();

      foreach($request->employee_id as $index => $empId)
      {
        $saveItems = [
          'project_id'  => $request->input('project_id'),
          'employee_id' => $empId,
          'active'      => $request->input('active') == 'on' ? true : false,
          'can_approve' => $request->input('can_approve') == 'on' ? true : false
        ];

        EmployeeProject::create($saveItems);
      }

      return redirect()->action('ProjectController@employee', $request->input('project_id'));
    }

    public function destroy($id)
    {
      $employeeProject = EmployeeProject::find($id);

      if($employeeProject)
        $employeeProject->delete();

      return back();
    }
}
