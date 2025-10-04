<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-8">
                    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white p-6 shadow sm:rounded-lg">
                            <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                @include('expenses._form', [
                                    'expense' => $expense,
                                    'accountingAccounts' => $accountingAccounts
                                ])
                                
                                 <div class="flex justify-center items-center gap-2">
                                    <div class="pt-4 flex gap-3">
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-green-600
         bg-transparent px-4 py-2 text-sm font-medium text-green-600
         hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600/40
         disabled:opacity-50 disabled:pointer-events-none">
                                            Guardar
                                        </button>
                                    </div>
                                    <div class="pt-4 flex gap-3">
                                        <a href="{{ route('employee.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-red-600
         bg-transparent px-4 py-2 text-sm font-medium text-red-600
         hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40
         disabled:opacity-50 disabled:pointer-events-none">
                                            Cancelar
                                        </a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>