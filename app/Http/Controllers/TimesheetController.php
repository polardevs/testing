<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Timesheet;
use Auth;

class TimesheetController extends Controller
{
    public function store(Request $request)
    {
      $this->validate($request, [
        'project_activity_id' => 'required|integer',
        'work_date'           => 'required|date_format:"d-m-Y"',
        'work_hour'           => 'required',
        'remark'              => 'string'
      ]);

      Timesheet::create($request->all());

      return back();
    }

    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'work_date'           => 'required|date_format:"d-m-Y"',
        'work_hour'           => 'required',
        'remark'              => 'string'
      ]);

      $timesheet = Timesheet::find($id);

      if(!$timesheet)
        return back();

      $timesheet->update($request->all());

      return back();
    }
}
