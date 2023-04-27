<?php

namespace App\Models\Auth;

use Spatie\Permission\Models\Role as RolSpatie;

class Role extends RolSpatie
{
 
    public $guard_name = 'api';
 
    protected $fillable = [
        'id',
        'name',
        'guard_name'
    ];
    
}
