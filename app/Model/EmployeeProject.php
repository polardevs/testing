<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EmployeeProject extends Model
{
    use SoftDeletes;

    protected $table    = 'employee_project';
    protected $dates    = ['deleted_at'];
    protected $guarded  = [];

    protected static function boot()
    {
        parent::boot();

        EmployeeProject::creating(function ($employeeProject) {
          if($employeeProject->active == 'on')
            $employeeProject->active = true;

          if($employeeProject->can_approve == 'on')
            $employeeProject->can_approve = true;

          if(Auth::check())
            $employeeProject->create_by = Auth::user()->id;
        });

        EmployeeProject::updating(function ($employeeProject) {
          if($employeeProject->active == 'on')
            $employeeProject->active = true;

          if($employeeProject->can_approve == 'on')
            $employeeProject->can_approve = true;

          if(Auth::check())
            $employeeProject->update_by = Auth::user()->id;
        });
    }

    /**
     * Relations
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeCreate()
    {
        return $this->belongsTo(Employee::class, 'create_by');
    }

    public function employeeUpdate()
    {
        return $this->belongsTo(Employee::class, 'update_by');
    }

    public function project()
    {
      return $this->belongsTo(Project::class, 'project_id');
    }
}
