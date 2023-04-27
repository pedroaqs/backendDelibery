<?php

namespace App\Models\Auth;

use Laravel\Sanctum\PersonalAccessToken as PersonalAccessTokenSanctum;

class PersonalAccessToken extends PersonalAccessTokenSanctum
{
    protected $table = 'personal_access_tokens';
}
