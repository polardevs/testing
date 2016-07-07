<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Department extends Model
{
    use SoftDeletes;

    protected $table    = 'department';
    protected $dates    = ['deleted_at'];
    protected $guarded  = [];

    protected static function boot()
    {
        parent::boot();

        Department::creating(function ($department) {
          if($department->active)
            $department->active = true;

          if(Auth::check())
            $department->create_by = Auth::user()->id;
        });

        Department::updating(function ($department) {
          if($department->active)
            $department->active = true;

          if(Auth::check())
            $department->update_by = Auth::user()->id;
        });
    }

    /**
     * Relations
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');

    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'department_id');

    }

    public function employeeCreate()
    {
        return $this->belongsTo(Employee::class, 'create_by');
    }

    public function employeeUpdate()
    {
        return $this->belongsTo(Employee::class, 'update_by');
    }
}
