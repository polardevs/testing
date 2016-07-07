<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table    = 'role';
    protected $dates    = ['deleted_at'];
    protected $guarded  = [];

    /**
     * Relations
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function employeeCreate()
    {
        return $this->belongsTo(Employee::class, 'create_by');
    }

    public function employeeUpdate()
    {
        return $this->belongsTo(Employee::class, 'update_by');
    }

    /*
     *  Scope Access
     */
    public function scopeDeveloper($q, $fieldName = 'name')
    {
        return $q->whereIn('name', ['developer'])->lists($fieldName);
    }

    public function scopeAdmin($q, $fieldName = 'name')
    {
        return $q->whereIn('name', ['developer', 'กรรมการผู้จัดการ', 'HR'])->lists($fieldName);
    }

    public function scopeManager($q, $fieldName = 'name')
    {
        return $q->whereIn('name', ['developer', 'กรรมการผู้จัดการ', 'HR', 'ผู้ช่วยกรรมการผู้จัดการ'])->lists($fieldName);
    }

    public function scopeProjectManager($q, $fieldName = 'name')
    {
        return $q->whereNotIn('name', ['พนักงานทั่วไป'])->lists($fieldName);
    }

    /*
     *  Check Access
     */

    public function isDeveloper()
    {
        return in_array($this->name, ['developer']) ? true : false;
    }

    public function isAdmin()
    {
        return in_array($this->name, ['developer', 'กรรมการผู้จัดการ', 'HR']) ? true : false;
    }

    public function isManager()
    {
        return in_array($this->name, ['developer', 'กรรมการผู้จัดการ', 'HR', 'ผู้ช่วยกรรมการผู้จัดการ']) ? true : false;
    }

    public function isProjectManager()
    {
        return in_array($this->name, ['developer', 'กรรมการผู้จัดการ', 'HR', 'ผู้ช่วยกรรมการผู้จัดการ', 'ผู้จัดการฝ่าย']) ? true : false;
    }

    public function isGeneral()
    {
        return in_array($this->name, ['developer', 'กรรมการผู้จัดการ', 'HR', 'ผู้ช่วยกรรมการผู้จัดการ', 'ผู้จัดการฝ่าย', 'พนักงานทั่วไป']) ? true : false;
    }
}
