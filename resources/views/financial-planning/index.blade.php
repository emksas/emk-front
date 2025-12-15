<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Financial Planning') }}
        </h2>

        <div class="sm:ml-auto flex items-center gap-2">
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
             bg-transparent px-4 py-2 text-sm font-medium text-blue-600
             hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600/40
             disabled:opacity-50 disabled:pointer-events-none">
                Add Project
            </a>
        </div>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-medium text-gray-900">
                        Financial Planning
                    </h1>
                    <p class="mt-6 text-gray-500 leading-relaxed">
                        Welcome to your financial planning dashboard. Here you can manage and review your financial
                        plans and associated operations.
                    </p>
                </div>

                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div id="accordion-card" data-accordion="collapse" class="mx-4">
                        @foreach ($financialPlannings as $financialPlanning)
                            <h2 id="accordion-card-heading-1">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-body rounded-base shadow-xs border border-default hover:text-heading hover:bg-neutral-secondary-medium gap-3 [&[aria-expanded='true']]:rounded-b-none [&[aria-expanded='true']]:shadow-none"
                                    data-accordion-target="#accordion-card-body-1" aria-expanded="true"
                                    aria-controls="accordion-card-body-1">
                                    <span> {{ $financialPlanning['planName'] }} - Total projected value:
                                        {{ $financialPlanning['projectedValue'] }} </span>
                                    <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m5 15 7-7 7 7" />
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-card-body-1"
                                class="hidden border border-t-0 border-default rounded-b-base shadow-xs"
                                aria-labelledby="accordion-card-heading-1">
                                <div class="flex justify-between items-center mb-4 p-4 md:p-5">
                                    <p class="text-xl font-bold mb-2">
                                        {{ $financialPlanning['description'] }}
                                    </p>

                                    <a href="{{ route('planning-operation.create', ['planningId' => $financialPlanning['planId'] ]) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
                                     bg-transparent px-4 py-2 text-sm font-medium text-blue-600
                                     hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600/40
                                     disabled:opacity-50 disabled:pointer-events-none">
                                        Add Expense
                                    </a>

                                </div>

                                <div class="p-4 md:p-5">

                                    <table id="expenses" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Amount
                                                </th>
                                                <th>
                                                    Merchant
                                                </th>
                                                <th>
                                                    Date
                                                </th>
                                                <th>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($financialPlanning['operations'] as $planningExpense)
                                                @if ($planningExpense['descripcion'] != null)
                                                    <tr>
                                                        <td>
                                                            {{ $planningExpense['valor'] }}
                                                        </td>
                                                        <td>
                                                            {{ $planningExpense['descripcion'] }}
                                                        </td>
                                                        <td>
                                                            {{ $planningExpense['fecha'] }}
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <div class="flex justify-center items-center gap-2">
                                                                <a href="{{ route('planning-operation.edit', [ 'id' => $planningExpense['id'], 'planId' => $financialPlanning['idplanificacionfinanciera'] ]) }}" class="inline-flex items-center gap-2 rounded-lg border border-yellow-600
                                                         bg-transparent px-4 py-2 text-sm font-medium text-yellow-600
                                                         hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40
                                                         disabled:opacity-50 disabled:pointer-events-none">
                                                                    Edit
                                                                </a>
                                                                <form action="{{ route('financial-planning.destroy',  [ 'id' => $planningExpense['id'], 'planId' => $financialPlanning['idplanificacionfinanciera'] ] ) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-600
                                                         bg-transparent px-4 py-2 text-sm font-medium text-red-600
                                                         hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40
                                                         disabled:opacity-50 disabled:pointer-events-none"
                                                                        onclick="return confirm('Are you sure you want to delete this expense?');">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- jQuery + DataTables (CDN) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        $(function () {
            $('#expenses').DataTable({
                pageLength: 20,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>


</x-app-layout>