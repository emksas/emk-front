<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Expenses') }}
        </h2>


        <div class="sm:ml-auto flex items-center gap-2">
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
             bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors
             hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600/40
             dark:border-indigo-300 dark:bg-indigo-400 dark:text-gray-950 dark:hover:bg-indigo-300 dark:focus:ring-indigo-300/40
             disabled:opacity-50 disabled:pointer-events-none">
                Add Expense
            </a>
            <button type="button" id="auth-microsoft" data-url-auth-email="{{ $urlAuthEmail }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-600
             bg-transparent px-4 py-2 text-sm font-medium text-blue-600 transition-colors
             hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600/40
             dark:border-sky-400/70 dark:bg-sky-400/10 dark:text-sky-200
             dark:hover:border-sky-300 dark:hover:bg-sky-400/20 dark:hover:text-white dark:focus:ring-sky-300/40
             disabled:opacity-50 disabled:pointer-events-none">
                From Mail
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40">

                <div class="p-6 lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">

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
                                <td data-order="{{ $expense['valor'] }}">
                                    <x-money :value="$expense['valor']" />
                                </td>
                                <td>
                                    {{ $expense['descripcion'] }}
                                </td>
                                <td>
                                    {{ $expense['fecha'] }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense['id']) }}" class="inline-flex items-center gap-2 rounded-lg border border-yellow-600
                                             bg-transparent px-4 py-2 text-sm font-medium text-yellow-600 transition-colors
                                             hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40
                                             dark:border-amber-400/70 dark:bg-amber-400/10 dark:text-amber-200
                                             dark:hover:border-amber-300 dark:hover:bg-amber-400/20 dark:hover:text-white dark:focus:ring-amber-300/40
                                             disabled:opacity-50 disabled:pointer-events-none">
                                            Edit
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense['id']) }}"
                                            method="POST" style="display:inline;"
                                            data-confirm-delete
                                            data-confirm-title="Delete expense?"
                                            data-confirm-message="This expense will be permanently deleted."
                                            data-loading="true"
                                            data-loading-title="Deleting expense"
                                            data-loading-message="Please wait while the expense is removed.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg border border-red-600
                                             bg-transparent px-4 py-2 text-sm font-medium text-red-600 transition-colors
                                             hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40
                                             dark:border-rose-400/70 dark:bg-rose-400/10 dark:text-rose-200
                                             dark:hover:border-rose-300 dark:hover:bg-rose-400/20 dark:hover:text-white dark:focus:ring-rose-300/40
                                             disabled:opacity-50 disabled:pointer-events-none">
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
        $(function() {
            $('#expenses').DataTable({
                pageLength: 20,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });

        window.addEventListener('message', function(event) {
            if (event.origin !== window.location.origin) {
                return;
            }

            if (event.data?.type === 'MICROSOFT_AUTH_FINISHED') {
                window.location.reload();
            }
        });

        document.getElementById('auth-microsoft').addEventListener('click', async function() {
            const authUrl = this.dataset.urlAuthEmail;

            const ancho = 600;
            const alto = 700;
            const izquierda = (screen.width / 2) - (ancho / 2);
            const arriba = (screen.height / 2) - (alto / 2);

            const popup = window.open(
                authUrl,
                'MicrosoftAuth',
                `width=${ancho},height=${alto},top=${arriba},left=${izquierda}`
            );
        })
    </script>

</x-app-layout>
