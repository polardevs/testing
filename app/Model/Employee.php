<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Model\EmployeeProject;
use Auth;

class Employee extends Authenticatable
{
    use SoftDeletes;

    protected $table    = 'employee';
    protected $dates    = ['deleted_at'];
    // protected $guarded  = [];
    protected $fillable = [
      'code', 'department_id', 'position_id', 'create_by', 'update_by', 'username',
      'password', 'firstname', 'lastname', 'can_login', 'active', 'remember_token'
    ];
    protected $hidden   = [
      'password', 'remember_token',
    ];

    protected static function boot()
    {
      parent::boot();

      Employee::creating(function ($employee) {
        // if(!$employee->code)
        //   $employee->code = 'emp' . sprintf("%07s", Employee::count() +1);
        if($employee->code && !$employee->username)
          $employee->username = $employee->code;

        if($employee->code != $employee->username)
          $employee->username = $employee->code;

        if($employee->active || $employee->active == 'on')
          $employee->active = true;
        else
          $employee->active = false;

        if($employee->can_login == 'on')
          $employee->can_login = true;
        else
          $employee->can_login = false;

        if(Auth::check())
          $employee->create_by = Auth::user()->id;

        if(!$employee->password)
          $employee->password = bcrypt('qweasd');
      });

      Employee::updating(function ($employee) {
        if($employee->code && !$employee->username)
          $employee->username = $employee->code;

        if($employee->code != $employee->username)
          $employee->username = $employee->code;

        if($employee->active || $employee->active == 'on')
          $employee->active = true;
        else
          $employee->active = false;

        if($employee->can_login == 'on')
          $employee->can_login = true;
        else
          $employee->can_login = false;

        if(Auth::check())
          $employee->update_by = Auth::user()->id;

      });
    }

    public function getFullNameAttribute()
    {
      return $this->firstname . ' ' . $this->lastname;
    }

    public function getCodeNameAttribute()
    {
      return $this->code . ' - ' . $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Relations
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'employee_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function employeeProjects()
    {
        return $this->hasMany(EmployeeProject::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function employeeCreate()
    {
        return $this->belongsTo(Employee::class, 'create_by');
    }

    public function employeeUpdate()
    {
        return $this->belongsTo(Employee::class, 'update_by');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'employee_id');
    }
}
