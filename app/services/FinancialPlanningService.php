<?php

namespace App\services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinancialPlanningService
{

    protected string $baseUrl;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.spring_financial.base_url');
    }

    public function getByUserId(string|int $userId): array
    {
        $response = Http::get($this->apiUrl("/financial-planning/user/{$userId}"));

        if ($response->failed()) {
            return [];
        }

        return $response->json() ?? [];
    }

    public function getByUserIdWithOperations(string|int $userId): array
    {
        $response = Http::get($this->apiUrl("/financial-planning/user/{$userId}"));

        if ($response->failed()) {
            return [
                'financialPlannings' => [],
                'error' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ];
        }

        $financialPlannings = $response->json() ?? [];

        foreach ($financialPlannings as $key => $financialPlanning) {
            $operationsResponse = $this->getOperationsByPlan($userId, $financialPlanning['planId']);
            $financialPlannings[$key]['operations'] = $operationsResponse->successful()
                ? ($operationsResponse->json() ?? [])
                : [];
        }

        return [
            'financialPlannings' => $financialPlannings,
            'error' => null,
            'spring_status' => $response->status(),
        ];
    }

    public function getByPlanId(string|int $planId, string|int $userId): ?array
    {
        $response = Http::get($this->apiUrl("/financial-planning/user/{$userId}/plan/{$planId}"));

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function create(array $data, string|int $userId): Response
    {
        return Http::acceptJson()
            ->asJson()
            ->post($this->apiUrl('/financial-planning'), $this->buildPayload($data, $userId));
    }

    public function update(string|int $planId, string|int $userId, array $data): Response
    {
        return Http::acceptJson()
            ->asJson()
            ->put($this->apiUrl("/financial-planning/user/{$userId}/plan/{$planId}"), $this->buildPayload($data, $userId));
    }

    public function delete(string|int $planId, string|int $userId): Response
    {
        return Http::delete($this->apiUrl("/financial-planning/user/{$userId}/plan/{$planId}"));
    }

    public function getAccountingAccounts(string|int $userId): array
    {
        $response = Http::get($this->apiUrl("/accounting-account/user/{$userId}"));

        if ($response->failed()) {
            return [];
        }

        return $response->json() ?? [];
    }

    private function getOperationsByPlan(string|int $userId, string|int $planId): Response
    {
        return Http::get($this->apiUrl("/planned-operations/user/{$userId}/plan/{$planId}"));
    }

    private function buildPayload(array $data, string|int $userId): array
    {
        return [
            'userId' => (int) $userId,
            'planName' => $data['descripcion'],
            'description' => $data['descripcion'],
            'projectedValue' => $data['valor'],
            'projectedDate' => $data['fecha'] . 'T00:00:00',
            'personalProject' => true,
        ];
    }

    private function apiUrl(string $path): string
    {
        $baseUrl = rtrim($this->baseUrl, '/');
        $apiBaseUrl = str_ends_with($baseUrl, '/api') ? $baseUrl : $baseUrl . '/api';

        return $apiBaseUrl . '/' . ltrim($path, '/');
    }

    public function reportFailedResponse(Response $response, string $action): void
    {
        Log::warning("Financial planning {$action} request failed.", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }
}
