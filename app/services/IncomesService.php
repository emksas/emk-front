<?php

namespace App\services;

use App\Models\Income;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomesService
{
    private $baseUrl;

    public function getIncomes($user)
    {
        $response = null;

        try {
            $this->baseUrl = config('services.python_incomes.base_url');
            $response = Http::get($this->baseUrl . '/api/incomes/?user_id=' . $user);

            if ($response->failed()) {
                return [
                    'incomes' => [],
                    'error' => 'Error fetching data from income service',
                    'status' => $response->status(),
                ];
            }

            $data = $response->json();
            if (!is_array($data) || empty($data)) {
                return [
                    'incomes' => [],
                    'error' => 'Invalid response format',
                    'status' => 500,
                ];
            }

            return [
                'incomes' => $data,
                'status' => 200,
            ];

        } catch (\Exception $e) {
            return [
                'incomes' => [],
                'error' => 'Exception occurred: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    public function create($payload, $userId)
    {
        $this->baseUrl = config('services.python_incomes.base_url');

        $newIncome = [
            'value' => $payload['valor'],
            'source' => $payload['descripcion'],
            'accounting_account_id' => $payload['cuentacontable_id'],
            'date' => Carbon::parse($payload['fecha'])->toDateString(),
            'user_id' => intval($userId),
            'financial_planning_id' => 1,
        ];


        $response = Http::acceptJson()->asJson()->post($this->baseUrl . '/api/incomes/', $newIncome);
        return $response;
    }

    public function deleteIncome($incomeId)
    {
        $this->baseUrl = config('services.python_incomes.base_url');
        $response = Http::delete($this->baseUrl . '/api/incomes/' . $incomeId);
        return $response;
    }

    public function updateIncome($incomeId, $payload)
    {
        $this->baseUrl = config('services.python_incomes.base_url');

        $updatedIncome = [
            'value' => $payload['valor'],
            'source' => $payload['descripcion'],
            'accounting_account_id' => $payload['cuentacontable_id'],
            'date' => Carbon::parse($payload['fecha'])->toDateString(),
            'financial_planning_id' => 1,
        ];

        $response = Http::acceptJson()->asJson()->put($this->baseUrl . '/api/incomes/' . $incomeId, $updatedIncome);
        return $response;
    }

}