<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add User</h3>

                        <form action="{{ route('user-management.users.store') }}" method="POST" class="mt-6 space-y-4"
                            data-loading="true"
                            data-loading-title="Creating user"
                            data-loading-message="Please wait while the user account is created.">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium mb-1 dark:text-gray-100">Name *</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium mb-1 dark:text-gray-100">Email *</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium mb-1 dark:text-gray-100">Role *</label>
                                <select id="role" name="role" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                                    <option value="">Select a role...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                            {{ $role->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="password" class="block text-sm font-medium mb-1 dark:text-gray-100">Password *</label>
                                    <input id="password" name="password" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium mb-1 dark:text-gray-100">Confirm Password *</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                                </div>
                            </div>

                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-600 bg-transparent px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600/40 disabled:opacity-50 disabled:pointer-events-none">
                                Add User
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add Role</h3>

                        <form action="{{ route('user-management.roles.store') }}" method="POST" class="mt-6 space-y-4"
                            data-loading="true"
                            data-loading-title="Creating role"
                            data-loading-message="Please wait while the role is created.">
                            @csrf

                            <div>
                                <label for="role-name" class="block text-sm font-medium mb-1 dark:text-gray-100">Role Name *</label>
                                <input id="role-name" name="role_name" type="text" value="{{ old('role_name') }}" maxlength="45" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                            </div>

                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-600 bg-transparent px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600/40 disabled:opacity-50 disabled:pointer-events-none">
                                Add Role
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Users</h3>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->roleType?->nombre ?? 'No role' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Roles</h3>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Assigned Users</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $role->nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $role->users_count }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('user-management.roles.destroy', $role) }}" method="POST"
                                                data-confirm-delete
                                                data-confirm-title="Delete role?"
                                                data-confirm-message="This role will be permanently deleted."
                                                data-loading="true"
                                                data-loading-title="Deleting role"
                                                data-loading-message="Please wait while the role is removed.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-600 bg-transparent px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40 disabled:opacity-50 disabled:pointer-events-none" @disabled($role->users_count > 0)>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
