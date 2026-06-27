<?php

namespace App\services;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTypeService
{
    public function getAllUserTypes()
    {
        return UserType::all()->toArray();
    }

    public function getUserTypeById( $userTypeId ){
        return UserType::find($userTypeId)->toArray();
    }

    public function getUserManagementData(): array
    {
        return [
            'users' => User::with('roleType')->orderBy('name')->get(),
            'roles' => UserType::withCount('users')->orderBy('id')->get(),
        ];
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function createRole(array $data): UserType
    {
        return UserType::create([
            'nombre' => $data['role_name'],
        ]);
    }

    public function deleteRole(UserType $role): void
    {
        if ($role->users()->exists()) {
            throw ValidationException::withMessages([
                'role' => 'This role cannot be deleted because it has assigned users.',
            ]);
        }

        $role->delete();
    }
    
}
