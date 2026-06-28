@php
    $hasDashboardData = ($dashboardData['totalExpenses'] ?? 0) > 0
        || ($dashboardData['numberOfExpenses'] ?? 0) > 0
        || ($dashboardData['incomeTotal'] ?? 0) > 0
        || ($dashboardData['plannedExpensesTotal'] ?? 0) > 0;

    $remainingBudget = $dashboardData['remainingBudget'] ?? 0;
    $remainingTone = $remainingBudget >= 0
        ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100'
        : 'border-red-200 bg-red-50 text-red-900 dark:border-red-900 dark:bg-red-950 dark:text-red-100';
    $remainingAfterActualExpenses = $dashboardData['remainingAfterActualExpenses'] ?? 0;
    $actualRemainingTone = $remainingAfterActualExpenses >= 0
        ? 'border-sky-200 bg-sky-50 text-sky-900 dark:border-sky-900 dark:bg-sky-950 dark:text-sky-100'
        : 'border-red-200 bg-red-50 text-red-900 dark:border-red-900 dark:bg-red-950 dark:text-red-100';
    $projectionDifference = $dashboardData['projectionDifference'] ?? 0;
    $projectionTone = $projectionDifference >= 0
        ? 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100'
        : 'border-red-200 bg-red-50 text-red-900 dark:border-red-900 dark:bg-red-950 dark:text-red-100';
@endphp

@if(! $hasDashboardData)
    <div role="status" class="w-full">
        <div class="mx-auto max-w-xl rounded-lg border border-dashed bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
            </div>

            <h3 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">No data available</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Try adjusting filters or the selected period.</p>

            <a href="{{ route('expenses.create') }}"
                class="mt-5 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Add new expense
            </a>
        </div>
    </div>
@else
    <div class="space-y-6">
        <section aria-labelledby="personal-summary-heading" class="space-y-4">
            <div>
                <p class="text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Personal profile</p>
                <h3 id="personal-summary-heading" class="text-2xl font-semibold text-gray-950 dark:text-white">Financial summary</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Income this month</p>
                    <p class="mt-3 break-words text-3xl font-semibold text-gray-950 dark:text-white">
                        <x-money :value="$dashboardData['incomeTotal'] ?? 0" />
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $dashboardData['numberOfIncomes'] ?? 0 }} records</p>
                </div>

                <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Planned expenses</p>
                    <p class="mt-3 break-words text-3xl font-semibold text-gray-950 dark:text-white">
                        <x-money :value="$dashboardData['plannedExpensesTotal'] ?? 0" />
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $dashboardData['numberOfPlannedOperations'] ?? 0 }} operations</p>
                </div>

                <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Actual expenses</p>
                    <p class="mt-3 break-words text-3xl font-semibold text-gray-950 dark:text-white">
                        <x-money :value="$dashboardData['totalExpenses'] ?? 0" />
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $dashboardData['numberOfExpenses'] ?? 0 }} records</p>
                </div>

                <div class="min-w-0 rounded-lg border p-5 shadow-sm {{ $remainingTone }}">
                    <p class="text-sm font-medium">Money left after planned expenses</p>
                    <p class="mt-3 break-words text-3xl font-semibold">
                        <x-money :value="$remainingBudget" />
                    </p>
                    <p class="mt-2 text-sm opacity-80">Income minus planned expenses</p>
                </div>

                <div class="min-w-0 rounded-lg border p-5 shadow-sm {{ $actualRemainingTone }}">
                    <p class="text-sm font-medium">Money left after actual expenses</p>
                    <p class="mt-3 break-words text-3xl font-semibold">
                        <x-money :value="$remainingAfterActualExpenses" />
                    </p>
                    <p class="mt-2 text-sm opacity-80">Income minus real expenses</p>
                </div>

                <div class="min-w-0 rounded-lg border p-5 shadow-sm {{ $projectionTone }}">
                    <p class="text-sm font-medium">Projection vs actual difference</p>
                    <p class="mt-3 break-words text-3xl font-semibold">
                        <x-money :value="$projectionDifference" />
                    </p>
                    <p class="mt-2 text-sm opacity-80">
                        {{ $projectionDifference >= 0 ? 'Actual spend is below projection' : 'Actual spend is above projection' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-2" aria-label="Planned expenses by account">
            <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">Planned value by accounting account</h3>
                <div class="mt-4 w-full min-w-0 overflow-x-auto text-sm text-gray-700 dark:text-gray-300">
                    <table id="plannedExpensesByAccount" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Total</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($dashboardData['plannedExpensesByAccount'] ?? []) as $account)
                                <tr>
                                    <td>{{ $account['description'] }}</td>
                                    <td><x-money :value="$account['total']" /></td>
                                    <td>{{ $account['count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No planned expenses</td>
                                    <td><x-money :value="0" /></td>
                                    <td>0</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">Planned distribution</h3>
                <div class="mt-4 w-full min-w-0 text-gray-700 dark:text-gray-300">
                    @if (count($dashboardData['plannedExpensesByAccount'] ?? []) > 0)
                        <div id="chartPlannedExpensesByAccount" class="min-h-80 w-full"></div>
                    @else
                        <div class="flex min-h-80 items-center justify-center rounded-lg border border-dashed border-gray-200 text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
                            No planned operations for this period.
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-2" aria-label="Actual expenses by account">
            <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">Actual expenses by account</h3>
                <div class="mt-4 w-full min-w-0 overflow-x-auto text-sm text-gray-700 dark:text-gray-300">
                    <table id="expensesByAccount" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboardData['expensesByAccount'] as $expense)
                                @if ($expense['descripcion'] != null)
                                    <tr>
                                        <td>{{ $expense['descripcion'] }}</td>
                                        <td><x-money :value="$expense['total']" /></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white">Actual distribution</h3>
                <div class="mt-4 w-full min-w-0 text-gray-700 dark:text-gray-300">
                    <div id="chartExpensesByAccount" class="min-h-80 w-full"></div>
                </div>
            </div>
        </section>

        <section class="min-w-0 overflow-x-auto rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900" aria-label="Actual expense records">
            <h3 class="mb-4 text-lg font-semibold text-gray-950 dark:text-white">Actual expense records</h3>
            <table id="expenses" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Merchant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($dashboardData['monthlyExpenses'] as $expense)
                        @if ($expense['descripcion'] != null)
                            <tr>
                                <td><x-money :value="$expense['valor']" /></td>
                                <td>{{ $expense['descripcion'] }}</td>
                                <td>{{ $expense['fecha'] }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense['id']) }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-yellow-600 bg-transparent px-4 py-2 text-sm font-medium text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40 disabled:pointer-events-none disabled:opacity-50">
                                            Edit
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense['id']) }}" method="POST"
                                            data-confirm-delete
                                            data-confirm-title="Delete expense?"
                                            data-confirm-message="This expense will be permanently deleted."
                                            data-loading="true"
                                            data-loading-title="Deleting expense"
                                            data-loading-message="Please wait while the expense is removed.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg border border-red-600 bg-transparent px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600/40 disabled:pointer-events-none disabled:opacity-50">
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
        </section>
    </div>
@endif
