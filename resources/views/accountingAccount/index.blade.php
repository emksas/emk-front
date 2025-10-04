<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accounting Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">

                    <div class="flex items-center justify-center">
                        <a href="{{ route('accountingAccount.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                            Add Accounting Account
                        </a>
                    </div>
                </div>

                <hr>

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
                                    User Id
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
                                    <td>
                                        {{ $accountingAccount['userId'] }}
                                    </td>
                                    <td>
                                        <a href="{{ route('accountingAccount.edit', $accountingAccount['id']) }}"
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('accountingAccount.destroy', $accountingAccount['id']) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-red font-bold py-1 px-2 rounded"
                                                onclick="return confirm('Are you sure you want to delete this expense?');">
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