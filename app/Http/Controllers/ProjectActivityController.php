<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\ProjectActivity;

class ProjectActivityController extends Controller
{
    public function store(Request $request)
    {
      $this->validate($request, [
        'project_id'   => 'required|integer',
        'fromto'       => 'required|string',
        'sequence'     => 'required|integer',
        'sub_sequence' => 'required|integer',
        'name'         => 'required|string',
      ]);

      $date                  = $this->splitDateIsFromTo($request->input('fromto'));
      $request['start_date'] = $date['start_date'];
      $request['end_date']   = $date['end_date'];

      ProjectActivity::create($request->all());

      $projectActivities    = ProjectActivity::where('project_id', $request->input('project_id'))->get();
      $dataTables['colums'] = ['#', 'sequence', 'sub', 'activity', 'start date', 'end date', 'active'];
      $dataTables['datas']  = [];

      if($projectActivities)
      {
        foreach($projectActivities as $index => $projectActivity)
        {
          $dataTables['datas'][] = [
            $index + 1,
            $projectActivity->sequence,
            $projectActivity->sub_sequence,
            $projectActivity->name,
            $projectActivity->startDateDMY,
            $projectActivity->endDateDMY,
            $projectActivity->activeText
          ];
        }
      }

      return back();
      // return $projectActivities->toJson();
      // return collect($dataTables['datas'])->toJson();
    }

    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'fromto'       => 'required|string',
        'sequence'     => 'required|integer',
        'sub_sequence' => 'required|integer',
        'name'         => 'required|string',
      ]);

      $date                  = $this->splitDateIsFromTo($request->input('fromto'));
      $request['start_date'] = $date['start_date'];
      $request['end_date']   = $date['end_date'];
      $request['active']     = $request->input('active') == 'on' ? true : false;

      $activity = ProjectActivity::find($id);

      if(!$activity)
        return back();

      $activity->update($request->all());

      return back();

    }

    public function destroy($id)
    {
      $activity = ProjectActivity::find($id);

      if($activity)
        $activity->delete();

      return back();
    }

    public function splitDateIsFromTo($strDate)
    {
        $date['start_date'] = date('Y-m-d', strtotime(explode(' - ', $strDate)[0]));
        $date['end_date']   = date('Y-m-d', strtotime(explode(' - ', $strDate)[1]));

        return $date;
    }
}
