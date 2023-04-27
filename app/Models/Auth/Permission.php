<?php

namespace App\Models\Auth;

use Spatie\Permission\Models\Permission as PermissionSpatie;

class Permission extends PermissionSpatie
{
    public $guard_name = 'api';

    protected $fillable = [
        'id',
        'name',
        'guard_name'
    ];

    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', 'action.auth.*');
    }

}
