<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Add Role') }}
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

            <section class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40" aria-labelledby="create-role-heading">
                <div class="p-6 text-center lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                    <h3 id="create-role-heading" class="text-lg font-semibold text-gray-900 dark:text-gray-100">Role Details</h3>

                    <form action="{{ route('user-management.roles.store') }}" method="POST" class="mx-auto mt-6 max-w-xl space-y-4 text-left">
                        @csrf

                        <div>
                            <label for="role-name" class="block text-sm font-medium mb-1 dark:text-gray-100">Role Name *</label>
                            <input id="role-name" name="role_name" type="text" value="{{ old('role_name') }}" maxlength="45" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" required>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-600 bg-transparent px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600/40 disabled:opacity-50 disabled:pointer-events-none">
                                Add Role
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
