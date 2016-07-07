<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Position;
use App\Model\Department;

class PositionController extends Controller
{
  public function index()
  {
    $positions            = Position::all();
    $dataTables           = [];
    $dataTables['colums'] = ['name', 'department', 'action'];
    $dataTables['datas']  = [];

    foreach($positions as $position)
    {
      $departmentName = (empty($position->department))? 'none' : $position->department->name;
      $dataTables['datas'][] = [
        $this->renderClickEdit($position, $position->name),
        $departmentName,
        $this->renderClickDelete($position, 'delete'),
      ];
    }

    return view('pages.position.index', [
      'dataTables'  => $dataTables,
      'departments' => Department::all()
    ]);
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'department_id' => 'required|integer',
      'name'          => 'required',
    ]);

    Position::create($request->all());

    return redirect()->action('PositionController@index');
  }

  public function edit($id)
  {
    $position = Position::find($id);

    if(!$position)
      return back();

    return view('pages.position.edit', [
      'position'    => $position,
      'departments' => Department::all()
    ]);
  }

  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'department_id' => 'required|integer',
      'name'          => 'required',
    ]);

    $position = Position::find($id);

    if(!$position)
      return back();

    $position->update($request->all());

    return redirect()->action('PositionController@index');
  }

  public function destroy($id)
  {
    $position = Position::find($id);

    if($position)
    {
      if(!$position->employees->count())
        $position->delete();
    }

    return back();
  }

  /**
   * Render tag html
   */
  private function renderClickDelete($position, $text)
  {
    if($position->employees->count())
    {
      return '<span class="label label-warning" data-toggle="tooltip" title="It\'s has related" style="cursor:not-allowed;" disabled>Delete</span>';
    }
    else
    {
      return '<span class="label label-warning" style="cursor:pointer;" onclick="$(this).find(\'form\').submit();">
                ' . $text . '
                <form action="' . action('PositionController@destroy', $position->id) . '" method="POST" hidden>'
                . method_field('delete')
                . csrf_field()
                . '</form>
              </span>';
    }
  }

  private function renderClickEdit($position, $text)
  {
    return '<a href="' . action('PositionController@edit', $position->id) . '" ><strong> ' . $text . ' </strong></a>';
  }
}
