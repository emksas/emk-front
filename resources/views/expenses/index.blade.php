<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expenses') }}
        </h2>


        <div class="sm:ml-auto flex items-center gap-2">
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
             bg-transparent px-4 py-2 text-sm font-medium text-blue-600
             hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600/40
             disabled:opacity-50 disabled:pointer-events-none">
                Add Expense
            </a>
            <a href="{{ route('expenses.fromMail') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
             bg-transparent px-4 py-2 text-sm font-medium text-blue-600
             hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600/40
             disabled:opacity-50 disabled:pointer-events-none">
                From Mail
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
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
                            @foreach ($expenses as $expense)
                                @if ($expense['descripcion'] != null)
                                    <tr>
                                        <td>
                                            {{ $expense['valor'] }}
                                        </td>
                                        <td>
                                            {{ $expense['descripcion'] }}
                                        </td>
                                        <td>
                                            {{ $expense['fecha'] }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('expenses.edit', $expense['idegreso']) }}" class="inline-flex items-center gap-2 rounded-lg border border-yellow-600
                                             bg-transparent px-4 py-2 text-sm font-medium text-yellow-600
                                             hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40
                                             disabled:opacity-50 disabled:pointer-events-none">
                                                    Edit
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense['idegreso']) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-2 rounded-lg border border-red-600
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