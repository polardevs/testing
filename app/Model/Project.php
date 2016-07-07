<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Project extends Model
{
    use SoftDeletes;

    protected $table    = 'project';
    protected $dates    = ['deleted_at'];
    protected $fillable = [
                            'department_id',
                            'owner_id',
                            'create_by',
                            'update_by',
                            'code',
                            'description',
                            'name',
                            'start_date',
                            'success_date',
                            'active',
                          ];


    protected static function boot()
    {
        parent::boot();

        Project::creating(function ($project) {
          if($project->active == 'on')
            $project->active = true;

          if(Auth::check())
            $project->create_by = Auth::user()->id;
        });

        Project::updating(function ($project) {
          if($project->active == 'on')
            $project->active = true;

          if(Auth::check())
            $project->update_by = Auth::user()->id;
        });
    }

    /**
     * Relations
     */
    public function employeeProjects()
    {
        return $this->hasMany(EmployeeProject::class, 'project_id');
    }

    public function projectActivities()
    {
        return $this->hasMany(ProjectActivity::class, 'project_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function owner()
    {
        return $this->belongsTo(Employee::class, 'owner_id');
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
