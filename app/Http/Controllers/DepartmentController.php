<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $departments          = Department::all();
      $dataTables           = [];
      $dataTables['colums'] = ['name', 'status', 'action'];
      $dataTables['datas']  = [];

      foreach($departments as $department)
      {
        $dataTables['datas'][] = [
          $this->renderClickEdit($department, $department->name),
          ($department->active ? 'active' : 'denied'),
          $this->renderClickDelete($department, 'Delete'),
        ];
      }

      return view('pages.department.index', [
        'dataTables' => $dataTables
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'name' => 'required'
      ]);

      Department::create($request->all());

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
      $department = Department::find($id);

      if(!$department)
        return back();

      return view('pages.department.edit', [
        'department' => $department
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
        'name' => 'required'
      ]);

      $department = Department::find($id);

      if(!$department)
        return back();

      $department->update($request->all());

      return redirect()->action('DepartmentController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $department = Department::find($id);

      if($department)
      {
        if(!$department->employees->count() || !$department->projects->count())
          $department->delete();
      }

      return back();
    }

    /**
     * Render tag html
     */
    private function renderClickDelete($department, $text)
    {
      if($department->employees->count() || $department->projects->count())
      {
        return '<span class="label label-warning" data-toggle="tooltip" title="It\'s has related" style="cursor:not-allowed;" disabled>Delete</span>';
      }
      else
      {
        return '<span class="label label-warning" style="cursor:pointer;" onclick="$(this).find(\'form\').submit();">
                  ' . $text . '
                  <form action="' . action('DepartmentController@destroy', $department->id) . '" method="POST" hidden>'
                  . method_field('delete')
                  . csrf_field()
                  . '</form>
                </span>';
      }
    }

    private function renderClickEdit($department, $text)
    {
      return '<a href="' . action('DepartmentController@edit', $department->id) . '" ><strong> ' . $text . ' </strong></a>';
    }
}
