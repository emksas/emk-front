<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accounting Account') }}
        </h2>

        <div class="sm:ml-auto flex items-center gap-2">
            <a href="{{ route('accountingAccount.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
         bg-transparent px-4 py-2 text-sm font-medium text-blue-600
         hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600/40
         disabled:opacity-50 disabled:pointer-events-none">
                Add Accounting Account
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <table id="accountingAccount" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    Id
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($accountingAccounts as $accountingAccount)
                                <tr>
                                    <td>
                                        {{ $accountingAccount['id'] }}
                                    </td>
                                    <td>
                                        {{ $accountingAccount['descripcion'] }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('accountingAccount.edit', $accountingAccount['id']) }}"
                                                class="inline-flex items-center gap-2 rounded-lg border border-yellow-600
                                             bg-transparent px-4 py-2 text-sm font-medium text-yellow-600
                                             hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40
                                             disabled:opacity-50 disabled:pointer-events-none">
                                                Edit
                                            </a>

                                            <form action="{{ route('accountingAccount.destroy', $accountingAccount['id']) }}"
                                                method="POST" onsubmit="return confirm('Are you sure you want to delete this accounting account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-red-600
                                             bg-transparent px-4 py-2 text-sm font-medium text-red-600
                                             hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40
                                             disabled:opacity-50 disabled:pointer-events-none">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
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
            $('#accountingAccount').DataTable({
                pageLength: 20,
                dom: 'Bfrtip',

                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
                },
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>

</x-app-layout>