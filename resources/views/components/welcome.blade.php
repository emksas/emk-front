@if($dashboardData['totalExpenses'] == 0 && $dashboardData['numberOfExpenses'] == 0)
    <div role="status" class="w-full">
        <div class="mx-auto max-w-xl rounded-2xl border border-dashed bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border">
                <!-- Heroicon outline: exclamation-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
            </div>

            <h3 class="text-2xl font-semibold tracking-tight dark:text-gray-100">No data available</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Try adjusting filters or the date range.</p>


            <a href="{{ route('expenses.create') }}"
                class="mt-5 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Add new expense
            </a>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 gap-4 p-4 xl:grid-cols-2">
        <div class="min-w-0 bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                Expenses Month
            </h2>
            <p class="mt-2 max-w-full break-words text-3xl font-bold text-gray-700 dark:text-gray-300"> {{ $dashboardData['totalExpenses'] }} </p>
        </div>

        <div class="min-w-0 bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 mt-2 dark:text-gray-100">
                Total Expenses
            </h2>
            <p class="mt-2 max-w-full break-words text-3xl font-bold text-gray-700 dark:text-gray-300"> {{ $dashboardData['numberOfExpenses'] }} </p>
        </div>

        <div class="min-w-0 bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                Expenses By Account
            </h2>
            <div class="mt-4 w-full min-w-0 overflow-x-auto text-sm font-medium text-gray-700 dark:text-gray-300">
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

        <div class="min-w-0 bg-white rounded-2xl shadow p-6 border flex flex-col items-center text-center dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                Expenses By Account
            </h2>
            <div class="mt-4 w-full min-w-0 text-gray-700 dark:text-gray-300">
                <div id="chartExpensesByAccount" class="min-h-80 w-full"></div>
            </div>
        </div>

        <div class="col-span-full min-w-0 overflow-x-auto rounded-2xl bg-white p-6 shadow border border-gray-200 dark:border-gray-800 dark:bg-gray-900">
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
                                <td class="px-4 py-2">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense['id']) }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-yellow-600
                                                                                                 bg-transparent px-4 py-2 text-sm font-medium text-yellow-600
                                                                                                 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-600/40
                                                                                                 disabled:opacity-50 disabled:pointer-events-none">
                                            Edit
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense['id']) }}" method="POST"
                                            style="display:inline;">
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

@endif
