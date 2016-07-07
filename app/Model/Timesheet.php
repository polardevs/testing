<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Timesheet extends Model
{
    use SoftDeletes;

    protected $table    = 'timesheet';
    protected $dates    = ['deleted_at'];
    protected $guarded  = [];

    protected static function boot()
    {
        parent::boot();

        Timesheet::creating(function ($timesheet) {
          if(Auth::check())
          {
            $timesheet->employee_id = Auth::user()->id;
            $timesheet->create_by   = Auth::user()->id;

            if($timesheet->work_date)
              $timesheet->work_date   = date('Y-m-d', strtotime($timesheet->work_date));
          }
          else
          {
            redirect()->action('Auth\AuthController@showLoginForm');
          }
        });

        Timesheet::updating(function ($timesheet) {
          if(Auth::check())
          {
            $timesheet->update_by = Auth::user()->id;

            if($timesheet->work_date)
              $timesheet->work_date   = date('Y-m-d', strtotime($timesheet->work_date));
          }
          else
          {
            redirect()->action('Auth\AuthController@showLoginForm');
          }
        });
    }

    /**
     *  Mutato & Accessor
     */
    public function getWorkHourHIAttribute()
    {
      $times = explode('.', 4.5);//$this->work_hour
      $HI = '';
      if(count($times) > 1)
        $HI = $times[0] . ':' . $times[1];

      return $HI;
    }

    public function getStampDMYAttribute()
    {
        return date('d-m-Y', strtotime($this->work_date));
    }

    public function getApproveDMYAttribute()
    {
        return date('d-m-Y', strtotime($this->approve_date));
    }

    public function getStampTimeHMAttributes()
    {
      return date('H:m', strtotime($this->work_date));
    }

    public function getApproveHMAttributes()
    {
      return date('H:m', strtotime($this->approve_date));
    }

    public function getDatePickerAttribute()
    {
        return date('d/m/Y', strtotime($this->work_date));
    }

    /**
     * Relations
     */
    public function projectActivity()
    {
        return $this->belongsTo(ProjectActivity::class, 'project_activity_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeApprove()
    {
        return $this->belongsTo(Employee::class, 'approve_by');
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
