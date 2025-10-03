@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="grid grid-cols-2 gap-2">

    <div class="bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center">
        <h2 class="text-xl font-semibold text-gray-900">
            Expenses Month
        </h2>
        <p class="mt-2 text-3xl font-bold text-gray-700"> {{ $dashboardData['totalExpenses'] }} </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center">
        <h2 class="text-xl font-semibold text-gray-900">
            Total Expenses
        </h2>
        <p class="mt-2 text-3xl font-bold text-gray-700"> {{ $dashboardData['numberOfExpenses'] }} </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center">
        <h2 class="text-xl font-semibold text-gray-900">
            Expenses By Account
        </h2>
        <div class="mt-2 text-3xl font-bold text-gray-700">
            <table id="expensesByAccount" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            Account
                        </th>
                        <th>
                            Total
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($dashboardData['expensesByAccount'] as $expense)
                        @if ($expense['descripcion'] != null)
                            <tr>
                                <td>
                                    {{ $expense['descripcion'] }}
                                </td>
                                <td>
                                    {{ $expense['total'] }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center">
        <h2 class="text-xl font-semibold text-gray-900">
            Expenses By Account
        </h2>
        <div class="mt-2 text-3xl font-bold text-gray-700">
            <div id="chartExpensesByAccount"></div>
        </div>
    </div>

    <br>

    <div class="col-span-2  p-6 lg:p-8 bg-white border-b border-gray-200">
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
                @foreach ($dashboardData['monthlyExpenses'] as $expense)
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
                            <td>
                                <a href="{{ route('expenses.edit', $expense['idegreso']) }}"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">
                                    Edit
                                </a>
                                <form action="{{ route('expenses.destroy', $expense['idegreso']) }}" method="POST"
                                    style="display:inline;">
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
                    @endif
                @endforeach
            </tbody>
        </table>
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

                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
                },
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });

        $(function () {
            $('#expensesByAccount').DataTable({
                pageLength: 5,
                dom: 'tp',
                lengthChange: false,     // oculta selector "mostrar N"
                searching: false,         // o false si no quieres búsqueda
                info: false,             // oculta "Mostrando X de Y"
                ordering: true,
                pagingType: 'simple',
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
                },
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(function () {
            const expensesByAccount = @json($dashboardData['expensesByAccount']);
            const labels = expensesByAccount.map(item => item.descripcion);
            const series = expensesByAccount.map(item => item.total);


            var options = {
                chart: { type: 'donut', height: 360 },
                series: series,
                labels: labels,
                tooltip: {
                    y: { formatter: (val) => new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(val) }
                },
                plotOptions: {
                    pie: {
                        donut: { size: '55%' } // quita este bloque si quieres 'pie' sólido
                    }
                }
            };
            new ApexCharts(document.querySelector("#chartExpensesByAccount"), options).render();
        });
    </script>

</div>