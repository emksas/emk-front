<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\services\UserTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(private UserTypeService $userTypeService)
    {
    }

    public function index(): View
    {
        $this->authorizeAdmin();

        return view('user-management.index', $this->userTypeService->getUserManagementData());
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', Rule::exists('tipo_usuario', 'id')],
        ]);

        $this->userTypeService->createUser($validated);

        return redirect()->route('user-management.index')->with('success', 'User created successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($user->is(Auth::user())) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own user account.',
            ]);
        }

        $this->userTypeService->deleteUser($user);

        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:45', Rule::unique('tipo_usuario', 'nombre')],
        ]);

        $this->userTypeService->createRole($validated);

        return redirect()->route('user-management.index')->with('success', 'Role created successfully.');
    }

    public function destroyRole(UserType $role): RedirectResponse
    {
        $this->authorizeAdmin();

        $this->userTypeService->deleteRole($role);

        return redirect()->route('user-management.index')->with('success', 'Role deleted successfully.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }
}
