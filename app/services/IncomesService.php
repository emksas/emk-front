<?php

namespace App\services;

use App\Models\Income;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomesService
{
    private $baseUrl = config('services.python_incomes.base_url');

    public function getIncomes( $user )
    {
        $incomes = [];

        try {

            $response = Http::get($this->baseUrl.'/api/incomes/?user_id='.$user);

            dd( $response );

            $incomes = $response['data'] ?? $response;
        } catch (\Exception $e) {
            return "exception in the http request";
        }

        print_r("incomes responde from pyhton ");
        print_r($incomes);

        return $incomes;
    }

    public function create($payload){
        $response = Http::acceptJson()->asJson()->post($this->baseUrl.'/api/incomes/', $payload);
        return $response;
    }

    


}