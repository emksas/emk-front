<?php

namespace App\services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IncomesService
{
    private $baseUrl;

    public function getIncomes($user)
    {
        $response = null;

        try {
            $this->baseUrl = config('services.python_incomes.base_url');
            $response = Http::get($this->apiUrl('/incomes/'), ['user_id' => $user]);

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
            'source' => $payload['fuente'],
            'accounting_account_id' => $payload['cuentacontable_id'],
            'date' => Carbon::parse($payload['fecha'])->toDateString(),
            'user_id' => intval($userId),
            'financial_planning_id' => 1,
        ];

        return Http::acceptJson()->asJson()->post($this->apiUrl('/incomes/'), $newIncome);
    }

    public function deleteIncome($userId, $accountingAccountId)
    {
        $this->baseUrl = config('services.python_incomes.base_url');
        return Http::withQueryParameters([
            'user_id' => intval($userId),
            'account_id' => intval($accountingAccountId),
        ])->delete($this->apiUrl('/incomes/delete-by-user-account/'));
    }

    public function updateIncome($income, $payload)
    {
        $this->baseUrl = config('services.python_incomes.base_url');

        $updatedIncome = [
            'value' => $payload['valor'],
            'source' => $payload['fuente'],
            'accounting_account_id' => $payload['cuentacontable_id'],
            'date' => Carbon::parse($payload['fecha'])->toDateString(),
            'user_id' => intval($income['user_id']),
            'financial_planning_id' => 1,
        ];

        return Http::acceptJson()->asJson()->put($this->apiUrl('/incomes/' . $income['id'] . '/'), $updatedIncome);
    }

    private function apiUrl(string $path): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
    }

}
