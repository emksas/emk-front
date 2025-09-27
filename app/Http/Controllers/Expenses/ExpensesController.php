<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Validator;
use App\Services\ExpensesService;
use Carbon\Carbon;

class ExpensesController extends Controller
{


    public function __construct(private ExpensesService $expensesService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = $this->expensesService->getAllExpenses();
        $error = null;

        return view('expenses.index', compact('expenses', 'error'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $this->expensesService->createExpense($request);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', ['expense' => $expense]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            $this->expensesService->deleteExpense($expense);
            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expenses.index')->with('error', 'Failed to delete expense: ' . $e->getMessage());
        }
    }

    public function getExpensesFromMail()
    {
        $expensesFromMail = $this->expensesService->fetchExpenses();
        foreach ($expensesFromMail as $expenseData) {
            // Here you would typically validate and save each expense
            // For demonstration, we'll just print the data
            if ($expenseData['paymentMethod'] != null) {

                $date = Carbon::parse($expenseData['transactionDate']);
                $data = [
                    'valor' => $expenseData['amount'],
                    'descripcion' => $expenseData['merchant'],
                    'fecha' => $date->format('Y-m-d H:i:s'),
                    'estado' => 'pay',
                    'idPlanificacion' => 1,
                    'cuentaContable_id' => 1,
                ];

                // 3) Validar
                $rules = [
                    'valor' => ['required'],
                    'descripcion' => ['required', 'string', 'max:255'],
                    'fecha' => ['required'], // si viene en otro formato, ver abajo
                    'estado' => ['sometimes', 'string'],
                    'idPlanificacion' => ['sometimes', 'integer'],
                    'cuentaContable_id' => ['sometimes', 'integer'],
                ];
                $validated = Validator::make($data, $rules)->validate();

                // 4) (Opcional) Formatear fecha si viene con otro formato
                // ej: 'd/m/Y H:i:s'
                // $validated['fecha'] = Carbon::createFromFormat('d/m/Y H:i:s', $validated['fecha'])->format('Y-m-d H:i:s');

                // 5) Crear (asegúrate de tener fillable)
                Expense::create($validated);

            }
        }
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

}