<?php

namespace App\services;

use Illuminate\Support\Facades\Http;

class FinancialPlanningService
{

    protected string $baseUrl;
    /**
     * Create a new class instance.
     */
    public function __construct() {
        $this->baseUrl = config('services.spring_financial.base_url');
    }

    public function getByUserId(string $userId)
    {
        $response = Http::get($this->baseUrl . '/financial-planning/user/' . $userId);
        if ($response->failed()) {
            return [
                'financialPlannings' => [],
                'error' => 'Error fetching data from financial planning service',
                'spring_status' => $response->status(),
            ];
        } else {
            return $response->json();
        }
    }

    public function create($payload)
    {
        $response = Http::acceptJson()
            ->asJson()
            ->post("{$this->baseUrl}/financial-planning", $payload);

        if ($response->successful()) {
            return [
                'response' => $response->json()
            ];
        } else {
            return [
                'error' => $response->reason()
            ];
        }
    }
}
