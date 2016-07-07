<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Model\Employee;

class ProfileController extends Controller
{
  public function index()
  {
    if(Auth::guest())
      return redirect()->guest('login');

    $profile = Employee::find(Auth::user()->id);

    if(!$profile)
      return response()->view('errors.401');

    return view('pages.profile', [
      'profile' => $profile
    ]);
  }

  public function update(Request $request, $id)
  {
    if($request->password == $request->rePassword)
    {
      $employee = Employee::find($id);
      $employee->update(['password' => bcrypt($request->password), 'active' => true, 'can_login' => true]);
    }

    return back();
  }

  public function resetPassword(Request $request)
  {
    $this->validate($request, [
      'code' => 'required|exists:employee'
    ]);

    $employee = Employee::where('code', $request->input('code'))->first();
    if(!$employee)
      return back();

    $employee->update(['password' => bcrypt('qweasd'), 'active' => false]);

    return redirect('');
  }
}
