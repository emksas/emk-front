<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use App\services\UserTypeService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserManagementDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_dashboard_shows_accessible_user_and_role_tables(): void
    {
        [$admin, $user, $roles] = $this->createAdminScenario();

        $this->mockUserTypeService($admin, $user, $roles);

        $response = $this->actingAs($admin)
            ->withSession(['success' => 'User created successfully.'])
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Administrator Dashboard');
        $response->assertSee('User Overview');
        $response->assertSee('Active Users');
        $response->assertSee('Users by Role');
        $response->assertSee('Users');
        $response->assertSee('Roles');
        $response->assertSee('aria-labelledby="users-by-role-heading"', false);
        $response->assertSee('Registered users and their assigned roles');
        $response->assertSee('Roles and their assigned user counts');
        $response->assertSee('scope="col"', false);
        $response->assertSee('role="status"', false);
        $response->assertSee('aria-live="polite"', false);
        $response->assertSee('aria-labelledby="users-table-heading"', false);
        $response->assertSee('Create User');
        $response->assertSee('Add Role');
        $response->assertSee($admin->email);
        $response->assertSee($user->email);
        $response->assertSee('Administrator Role');
        $response->assertSee('Individual Role');
        $response->assertSee('Empty Role');
    }

    public function test_dashboard_hides_delete_actions_when_deletion_is_not_allowed(): void
    {
        [$admin, $user, $roles] = $this->createAdminScenario();

        $this->mockUserTypeService($admin, $user, $roles);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('Delete user '.$admin->name, false);
        $response->assertSee('Delete user '.$user->name, false);
        $response->assertDontSee('Delete role Administrator Role', false);
        $response->assertDontSee('Delete role Individual Role', false);
        $response->assertSee('Delete role Empty Role', false);
        $response->assertSee('No available actions for your own user account.');
        $response->assertSee('No available actions because this role has assigned users.');
    }

    public function test_non_admin_users_cannot_access_user_management_pages(): void
    {
        [, $user] = $this->createAdminScenario();

        $this->actingAs($user)->get(route('user-management.users.create'))->assertForbidden();
        $this->actingAs($user)->get(route('user-management.roles.create'))->assertForbidden();
    }

    public function test_create_user_page_has_accessible_form_controls(): void
    {
        [$admin, $user, $roles] = $this->createAdminScenario();

        $this->mockUserTypeService($admin, $user, $roles);

        $response = $this->actingAs($admin)->get(route('user-management.users.create'));

        $response->assertOk();
        $response->assertSee('Create User');
        $response->assertSee('aria-labelledby="create-user-heading"', false);
        $response->assertSee('for="name"', false);
        $response->assertSee('for="email"', false);
        $response->assertSee('for="role"', false);
        $response->assertSee('for="password"', false);
        $response->assertSee('for="password_confirmation"', false);
    }

    public function test_create_role_page_has_accessible_form_controls(): void
    {
        [$admin] = $this->createAdminScenario();

        $response = $this->actingAs($admin)->get(route('user-management.roles.create'));

        $response->assertOk();
        $response->assertSee('Add Role');
        $response->assertSee('aria-labelledby="create-role-heading"', false);
        $response->assertSee('for="role-name"', false);
        $response->assertSee('role_name', false);
    }

    private function createAdminScenario(): array
    {
        $individualRole = new UserType([
            'nombre' => 'Individual Role',
        ]);
        $individualRole->id = 1;
        $individualRole->users_count = 1;

        $adminRole = new UserType([
            'nombre' => 'Administrator Role',
        ]);
        $adminRole->id = 4;
        $adminRole->users_count = 1;

        $emptyRole = new UserType([
            'nombre' => 'Empty Role',
        ]);
        $emptyRole->id = 5;
        $emptyRole->users_count = 0;

        $admin = new User([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 4,
        ]);
        $admin->id = 10;
        $admin->setRelation('roleType', $adminRole);

        $user = new User([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'role' => 1,
        ]);
        $user->id = 11;
        $user->setRelation('roleType', $individualRole);

        return [$admin, $user, collect([$individualRole, $adminRole, $emptyRole])];
    }

    private function mockUserTypeService(User $admin, User $user, Collection $roles): void
    {
        $this->mock(UserTypeService::class, function ($mock) use ($admin, $user, $roles) {
            $mock->shouldReceive('getUserManagementData')->andReturn([
                'users' => collect([$admin, $user]),
                'roles' => $roles,
                'activeUsersCount' => 2,
            ]);
        });
    }
}
