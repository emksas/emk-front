@php

    use Carbon\Carbon;

    $now = Carbon::now();
    $actualYear = $now->year;
    $actualMonth = $now->month;
    $actualMonthName = $now->locale('en')->monthName;
@endphp

<x-app-layout>
    <x-slot name="header">
        @if (($isAdminDashboard ?? false) === true)
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
                {{ __('Administrator Dashboard') }}
            </h2>
        @else
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
                {{ __('Welcome to EMK') }} - {{ __('Selected Date:') }} {{ $dashboardData['monthName'] }} /
                {{ $dashboardData['year'] }}
            </h2>
            <div class="sm:ml-auto flex items-center gap-2">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2"
                    data-loading="true"
                    data-loading-title="Loading dashboard"
                    data-loading-message="Please wait while the selected period is loaded.">
                    <select id="selYear" name="year" class="border rounded px-2 py-1 text-sm min-w-32 border-gray-300 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">{{ $actualYear }}</option>
                    </select>
                    <select id="selMonth" name="month" class="border rounded px-2 py-1 text-sm min-w-32 border-gray-300 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="{{ $actualMonth }}">{{ $actualMonthName }}</option>
                    </select>
                    <button id="btnApply"
                        class="rounded-lg border border-blue-600 px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 dark:border-blue-400 dark:text-blue-300 dark:hover:bg-blue-950/50">
                        Apply
                    </button>
                </form>
            </div>
        @endif
    </x-slot>

    @if (($isAdminDashboard ?? false) === true)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                @if ($errors->any())
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert" aria-live="assertive">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40" aria-labelledby="active-users-heading">
                    <div class="p-6 lg:p-8">
                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-center">
                            <div class="text-center lg:border-r lg:border-gray-200 lg:pr-8 dark:lg:border-gray-800">
                                <p class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">User Overview</p>
                                <h3 id="active-users-heading" class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">Active Users</h3>
                                <p class="mt-3 text-5xl font-semibold text-indigo-700 dark:text-indigo-300">{{ $activeUsersCount }}</p>
                            </div>

                            <div class="lg:col-span-2">
                                <h4 id="users-by-role-heading" class="text-center text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 lg:text-left">Users by Role</h4>

                                <ul class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 2xl:grid-cols-4" aria-labelledby="users-by-role-heading">
                                    @forelse ($roles as $role)
                                        <li class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-5 text-center shadow-sm dark:border-gray-800 dark:bg-gray-950">
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $role->nombre }}</p>
                                            <p class="mt-3 text-3xl font-semibold text-gray-950 dark:text-white">{{ $role->users_count }}</p>
                                            <p class="mt-1 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                {{ trans_choice('user|users', $role->users_count) }}
                                            </p>
                                        </li>
                                    @empty
                                        <li class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-5 text-center text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400">
                                            No roles found.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40" aria-labelledby="users-table-heading">
                    <div class="p-6 text-center lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                        <h3 id="users-table-heading" class="text-lg font-semibold text-gray-900 dark:text-gray-100">Users</h3>

                        <div class="mt-6 overflow-x-auto">
                            <table class="mx-auto min-w-full divide-y divide-gray-200 text-center dark:divide-gray-800">
                                <caption class="sr-only">Registered users and their assigned roles</caption>
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    @forelse ($users as $user)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->roleType?->nombre ?? 'No role' }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @unless ($user->is(auth()->user()))
                                                    <form action="{{ route('user-management.users.destroy', $user) }}" method="POST" class="inline-flex justify-center"
                                                        data-confirm-delete
                                                        data-confirm-title="Delete user?"
                                                        data-confirm-message="This user account will be permanently deleted."
                                                        data-loading="true"
                                                        data-loading-title="Deleting user"
                                                        data-loading-message="Please wait while the user is removed.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-600 bg-transparent px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40" aria-label="Delete user {{ $user->name }}">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="sr-only">No available actions for your own user account.</span>
                                                @endunless
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950/40" aria-labelledby="roles-table-heading">
                    <div class="p-6 text-center lg:p-8 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                        <h3 id="roles-table-heading" class="text-lg font-semibold text-gray-900 dark:text-gray-100">Roles</h3>

                        <div class="mt-6 overflow-x-auto">
                            <table class="mx-auto min-w-full divide-y divide-gray-200 text-center dark:divide-gray-800">
                                <caption class="sr-only">Roles and their assigned user counts</caption>
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Assigned Users</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    @forelse ($roles as $role)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $role->nombre }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $role->users_count }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @unless ($role->users_count > 0)
                                                    <form action="{{ route('user-management.roles.destroy', $role) }}" method="POST" class="inline-flex justify-center"
                                                        data-confirm-delete
                                                        data-confirm-title="Delete role?"
                                                        data-confirm-message="This role will be permanently deleted."
                                                        data-loading="true"
                                                        data-loading-title="Deleting role"
                                                        data-loading-message="Please wait while the role is removed.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-600 bg-transparent px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40" aria-label="Delete role {{ $role->nombre }}">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="sr-only">No available actions because this role has assigned users.</span>
                                                @endunless
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No roles found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-welcome :dashboard-data="$dashboardData" />
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

                $(function () {
                    $('#plannedExpensesByAccount').DataTable({
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

                $(function () {
                    const plannedByAccount = @json($dashboardData['plannedExpensesByAccount']);
                    const chartElement = document.querySelector("#chartPlannedExpensesByAccount");

                    if (!chartElement || plannedByAccount.length === 0) {
                        return;
                    }

                    const labels = plannedByAccount.map(item => item.description);
                    const series = plannedByAccount.map(item => Number(item.total));

                    const options = {
                        chart: { type: 'bar', height: 320, toolbar: { show: false } },
                        series: [{ name: 'Planned', data: series }],
                        xaxis: { categories: labels },
                        colors: ['#2563eb'],
                        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                        dataLabels: { enabled: false },
                        tooltip: {
                            y: { formatter: (val) => new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(val) }
                        }
                    };

                    new ApexCharts(chartElement, options).render();
                });
            });
        </script>
        @endpush
    @endif
</x-app-layout>
