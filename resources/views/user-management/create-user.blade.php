<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert" aria-live="assertive">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40" aria-labelledby="create-user-heading">
                <div class="p-6 text-center lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                    <h3 id="create-user-heading" class="text-lg font-semibold text-gray-900 dark:text-gray-100">User Details</h3>

                    <form action="{{ route('user-management.users.store') }}" method="POST" class="mx-auto mt-6 max-w-2xl space-y-4 text-left">
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

                        <div class="flex justify-center">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-600 bg-transparent px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600/40 disabled:opacity-50 disabled:pointer-events-none">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
