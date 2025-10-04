@php

    use Carbon\Carbon;

    $now = Carbon::now();
    $actualYear = $now->year;
    $actualMonth = $now->month;
    $actualMonthName = $now->locale('en')->monthName;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to EMK') }} - {{ __('Selected Date:') }} {{ $dashboardData['monthName'] }} /
            {{ $dashboardData['year'] }}
        </h2>
        <div class="sm:ml-auto flex items-center gap-2">
            <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                @csrf
                <select id="selYear" name="year" class="border rounded px-2 py-1 text-sm min-w-32">
                    <option value="">{{ $actualYear }}</option>
                </select>
                <select id="selMonth" name="month" class="border rounded px-2 py-1 text-sm min-w-32">
                    <option value="{{ $actualMonth }}">{{ $actualMonthName }}</option>
                </select>
                <button id="btnApply"
                    class="rounded-lg border border-blue-600 px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50">
                    Apply
                </button>
            </form>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome :dashboard-data="$dashboardData" class="bg-white" />
            </div>
        </div>
    </div>

    @pushOnce('scripts')
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
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endPushOnce
    @push('scripts')
        <script>
            $(document).ready(async function () {

                const years = await fetch('{{ route('filters.years') }}', { headers: { 'Accept': 'application/json' } })
                    .then(response => response.json())
                    .then(data => data);

                const yearElement = $('#selYear');
                yearElement.empty();
                (years || []).forEach(year => {
                    const isSelected = year == '{{ request('year', $actualYear) }}' ? 'selected' : '';
                    yearElement.append(`<option value="${year}" ${isSelected}>${year}</option>`);
                });

                async function loadMonths() {
                    const y = yearElement.val();
                    const resp = await fetch(`{{ route('filters.months') }}?year=${y}`, { headers: { 'Accept': 'application/json' } });
                    const json = await resp.json();

                    const $month = $('#selMonth');
                    $month.empty();
                    (json || []).forEach(m => {
                        console.log('month information', m);
                        $month.append(`<option value="${m.number}">${m.name.charAt(0).toUpperCase() + m.name.slice(1)}</option>`);
                    });

                    const currentMonth = {{ now()->month }};
                    if ($month.find(`option[value="${currentMonth}"]`).length) {
                        $month.val(String(currentMonth));
                    } else {
                        $month.find('option').first().prop('selected', true);
                    }
                }
                await loadMonths();

                yearElement.on('change', loadMonths);

            });
        </script>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(async function () {
                $(function () {
                    $('#expenses').DataTable({
                        pageLength: 20,
                        dom: 'Bfrtip',
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    });
                });

                $(function () {
                    $('#expensesByAccount').DataTable({
                        pageLength: 5,
                        dom: 'tp',
                        lengthChange: false,
                        searching: false,
                        info: false,
                        ordering: true,
                        pagingType: 'simple',
                    });
                });
            });
        </script>


        <script>
            $(document).ready(async function () {
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
            });
        </script>
    @endpush
</x-app-layout>