<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'admin_departments';

    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_department_permission', 'dpm_id', 'permission_id');
    }

    public function givePermissionsTo(array $permissionId)
    {
        $this->permissions()->detach();
        $permissions = Permission::whereIn('id', $permissionId)->get();
        foreach ($permissions as $v) {
            $this->givePermissionTo($v);
        }
        return true;
    }

    public function givePermissionTo($permission)
    {
        return $this->permissions()->save($permission);
    }
}
