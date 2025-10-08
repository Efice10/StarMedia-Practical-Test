<?php

namespace App\Http\Controllers\User;

use App\Actions\User\AssignRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function assignRole(Request $request, int $userId, AssignRole $action)
    {
        $roleName = $request->input('role');
        
        return $action->execute($userId, $roleName);
    }
}