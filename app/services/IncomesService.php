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

        $this->baseUrl = config('services.python_incomes.base_url');
        $response = Http::get($this->baseUrl . '/api/incomes/?user_id=' . $user);

        return $response;

        if ($response->failed()) {
            return [
                'incomes' => [],
                'error' => 'Error fetching data from income service',
                'spring_status' => $response->status(),
            ];
        } else {
            $incomes = $response->json();
        }

        /*
        try {
            $this->baseUrl = config('services.spring_financial.base_url');
            $response = Http::get($this->baseUrl.'/api/incomes/?user_id='.$user);

            return $response;

            if ($response->failed()) {
                return [
                    'incomes' => [],
                    'error' => 'Error fetching data from income service',
                    'spring_status' => $response->status(),
                ];
            } else {
                $incomes = $response->json();
            }

        } catch (\Exception $e) {
            return "exception in the http request";
        }
    */

    }

    public function create($payload)
    {
        $response = Http::acceptJson()->asJson()->post($this->baseUrl . '/api/incomes/', $payload);
        return $response;
    }




}