<?php

namespace App\services;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;
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
        $users = User::with('roleType')->orderBy('name')->get();

        return [
            'users' => $users,
            'roles' => UserType::withCount('users')->orderBy('id')->get(),
            'activeUsersCount' => $users->count(),
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
        DB::transaction(function () use ($user) {
            $userId = $user->getKey();
            $planIds = DB::table('planificacion_financiera')
                ->where('usuario_cedula', $userId)
                ->pluck('id');
            $accountIds = DB::table('cuentacontable')
                ->where('userId', $userId)
                ->pluck('id');

            DB::table('egreso')->where('user_id', $userId)->delete();
            DB::table('ingreso')->where('user_id', $userId)->delete();

            DB::table('operations_planified')
                ->where('user_id', $userId)
                ->when($planIds->isNotEmpty(), fn ($query) => $query->orWhereIn('id_planification', $planIds))
                ->when($accountIds->isNotEmpty(), fn ($query) => $query->orWhereIn('account_id', $accountIds))
                ->delete();

            DB::table('planificacion_financiera')->where('usuario_cedula', $userId)->delete();
            DB::table('cuentacontable')->where('userId', $userId)->delete();
            DB::table('sessions')->where('user_id', $userId)->delete();
            DB::table('personal_access_tokens')
                ->where('tokenable_type', User::class)
                ->where('tokenable_id', $userId)
                ->delete();

            $user->delete();
        });
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
