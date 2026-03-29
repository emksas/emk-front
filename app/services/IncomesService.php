<?php

namespace App\services;

use App\Models\Income;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomesService
{

    public function fetchIncomes()
    {
        $incomes = [];

        try {
            $res = Http::timeout(10)->retry(3, 200)
                ->baseUrl('http://localhost:3000/api')
                ->get('/incomes') // ⚠️ endpoint de incomes
                ->throw()
                ->json();

            $incomes = $res['data'] ?? $res;

        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return $incomes;
    }

    public function createIncome($data)
    {
        // Si luego haces FormRequest como en expenses, cambias esto
        $validated = Validator::make($data->all(), [
            'valor' => ['required'],
            'fuente' => ['required', 'string', 'max:255'],
            'fecha' => ['required'],
            'estado' => ['sometimes', 'string'],
            'planificacionfinanciera_id' => ['sometimes', 'integer'],
            'cuentacontable_id' => ['sometimes', 'integer'],
        ])->validate();

        return Income::create($validated);
    }

    public function fromMail()
    {
        $incomesFromMail = $this->fetchIncomes();

        foreach ($incomesFromMail as $incomeData) {
            if ($incomeData['paymentMethod'] != null) {

                $date = Carbon::parse($incomeData['transactionDate']);

                $data = [
                    'valor' => $incomeData['amount'],
                    'descripcion' => $incomeData['merchant'],
                    'fecha' => $date->format('Y-m-d H:i:s'),
                    'estado' => 'pay',
                    'idPlanificacion' => 1,
                    'cuentacontable_id' => 1,
                ];

                $rules = [
                    'valor' => ['required'],
                    'descripcion' => ['required', 'string', 'max:255'],
                    'fecha' => ['required'],
                    'estado' => ['sometimes', 'string'],
                    'idPlanificacion' => ['sometimes', 'integer'],
                    'cuentacontable_id' => ['sometimes', 'integer'],
                ];

                $validated = Validator::make($data, $rules)->validate();
                Income::create($validated);
            }
        }
    }

    public function getMonthlyIncomes($month, $year)
    {
        return Income::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->get()
            ->toArray();
    }

    public function getMonthlyIncomesByAccount($month, $year)
    {
        return Income::join('cuentacontable as cc', 'cc.id', '=', 'ingreso.cuentacontable_id')
            ->whereYear('ingreso.fecha', $year)
            ->whereMonth('ingreso.fecha', $month)
            ->groupBy('cc.id', 'cc.descripcion')
            ->select('cc.id as cuenta_id', 'cc.descripcion', DB::raw('SUM(ingreso.valor) as total'))
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    public function getSumOfIncomesByMonth($month, $year)
    {
        return Income::whereMonth('fecha', $month)
            ->whereYear('fecha', $year)
            ->sum('valor');
    }

    public function getAllIncomes()
    {
        return Income::all()->toArray();
    }

    public function deleteIncome(Income $income)
    {
        return $income->delete();
    }

    public function getYearsAvailables()
    {
        return Income::selectRaw('YEAR(fecha) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    public function getMonthsAvailables($year)
    {
        return Income::selectRaw('MONTH(fecha) as month')
            ->whereYear('fecha', intval($year))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');
    }
}