<?php

namespace App\services;

use App\Http\Requests\UpdateAccountingAccountRequest;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;

class UserTypeService
{
    public function getAllUserTypes()
    {
        return UserType::all()->toArray();
    }

    public function getUserTypeById( $userTypeId ){
        return UserType::find($userTypeId)->toArray();
    }
    
}


?>