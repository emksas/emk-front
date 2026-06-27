<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Dashboard Empresarial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <h3 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Flujo de Caja Empresarial</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border p-6 rounded-lg bg-green-50 border-green-200">
                        <h4 class="text-lg font-semibold text-green-800 mb-3">Módulo de Ingresos</h4>
                        <p class="text-sm text-gray-600 mb-4">Visualización y gestión de todas las entradas financieras de la empresa.</p>
                        <div class="bg-white p-4 rounded border border-green-300 text-center font-mono text-xl text-green-700">
                            $ 0.00 COP
                        </div>
                    </div>

                    <div class="border p-6 rounded-lg bg-red-50 border-red-200">
                        <h4 class="text-lg font-semibold text-red-800 mb-3">Módulo de Egresos</h4>
                        <p class="text-sm text-gray-600 mb-4">Visualización y control de salidas de capital, costos operacionales y pasivos.</p>
                        <div class="bg-white p-4 rounded border border-red-300 text-center font-mono text-xl text-red-700">
                            $ 0.00 COP
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
