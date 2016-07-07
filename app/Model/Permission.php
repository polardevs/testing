<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Permission extends Model
{
    use SoftDeletes;

    protected $table    = 'permission';
    protected $dates    = ['deleted_at'];
    protected $guarded  = [];

    protected static function boot()
    {
        parent::boot();

        Permission::creating(function ($permission) {
          if(Auth::check())
            $permission->create_by = Auth::user()->id;
        });

        Permission::updating(function ($permission) {
          if(Auth::check())
            $permission->update_by = Auth::user()->id;
        });
    }

    /**
     * Relations
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

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
}
