<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Dashboard Familiar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold text-green-600">Ingresos Familiares</h3>
                    <p class="text-2xl">${{ number_format($dashboardData['total_incomes'] ?? 0, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold text-red-600">Egresos Familiares</h3>
                    <p class="text-2xl">${{ number_format($dashboardData['total_expenses'] ?? 0, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold text-orange-600">Gastos Totales</h3>
                    <p class="text-2xl">${{ number_format($dashboardData['balance'] ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Registrar Gasto Familiar</h3>
                
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción del Gasto</label>
                        <input type="text" name="descripcion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                        <input type="number" step="0.01" name="monto" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a Miembro(s) (Rol Personal)</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto p-3 border rounded-md bg-gray-50">
                            @foreach($usuariosPersonal as $usuario)
                                <label class="inline-flex items-center mr-4">
                                    <input type="checkbox" name="usuarios_asignados[]" value="{{ $usuario->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <span class="ml-2 text-sm text-gray-600">{{ $usuario->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Selecciona uno o varios usuarios registrados en el sistema bajo el rol Personal.</p>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        Guardar Gasto
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>