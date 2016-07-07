<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ProjectActivity extends Model
{
    use SoftDeletes;

    protected $table    = 'project_activity';
    protected $dates    = ['deleted_at'];
    protected $fillable = [
                                'id',
                                'project_id',
                                'create_by',
                                'update_by',
                                'sequence',
                                'sub_sequence',
                                'name',
                                'start_date',
                                'end_date',
                                'can_edit',
                                'active'
                            ];

    protected static function boot()
    {
        parent::boot();

        ProjectActivity::creating(function ($projectActivity) {
          if($projectActivity->active == 'on')
            $projectActivity->active = true;

          if(Auth::check())
            $projectActivity->create_by = Auth::user()->id;
        });

        ProjectActivity::updating(function ($projectActivity) {
          if($projectActivity->active == 'on')
            $projectActivity->active = true;

          if(Auth::check())
            $projectActivity->update_by = Auth::user()->id;
        });
    }

    /**
     *  Mutators & Accessors
     */

    public function getStartDateDMYAttribute()
    {
        return date('d-m-Y', strtotime($this->start_date));
    }

    public function getEndDateDMYAttribute()
    {
        return date('d-m-Y', strtotime($this->end_date));
    }

    public function getActiveTextAttribute()
    {
        return $this->active ? 'active' : 'not active';
    }

    public function getSequenceNameAttribute()
    {
        return '<strong>' . $this->project->code . '</strong> ' .
               $this->project->name .
               ' - ' . $this->sequence . '.' . $this->sub_sequence .
               ' - ' . $this->name;
    }

    public function getSequenceChoiceAttribute()
    {
        return $this->project->code .
               ' - ' . $this->sequence . '.' . $this->sub_sequence .
               ' - ' . $this->name;
    }

    public function getActivityAttribute()
    {
        return $this->sequence . '.' . $this->sub_sequence . ' - ' . $this->name;
    }

    public function getDateRangeAttribute()
    {
        return date('m/d/Y', strtotime($this->start_date)) . ' - ' . date('m/d/Y', strtotime($this->end_date));
    }

    public function scopeOrderSequence($query)
    {
        return $query->orderBy('sequence')
                     ->orderBy('sub_sequence')
                     ->orderBy('name');
    }

    /**
     * Relations
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'project_activity_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
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
