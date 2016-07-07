<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Position extends Model
{
  use SoftDeletes;

  protected $table    = 'position';
  protected $dates    = ['deleted_at'];
  protected $guarded  = [];

  protected static function boot()
  {
    parent::boot();

    Position::creating(function ($position) {
      if(Auth::check())
        $position->create_by = Auth::user()->id;
    });

    Position::updating(function ($position) {
      if(Auth::check())
        $position->update_by = Auth::user()->id;
    });
  }

  /**
   * Relations
   */
  public function employees()
  {
    return $this->hasMany(Employee::class, 'position_id');
  }

  public function department()
  {
    return $this->belongsTo(Department::class, 'department_id');
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
