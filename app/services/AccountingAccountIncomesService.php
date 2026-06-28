<?php

namespace App\services;

use Illuminate\Support\Facades\Http; 
use App\Models\AccountingAccount;
use Illuminate\Support\Facades\Auth;

class AccountingAccountIncomesService
{
    
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.spring_financial.base_url');
    }

    public function getAll()
    {
        $userId = Auth::id();
        $urlRequest = "{$this->baseUrl}/accounting-account/user/{$userId}";
        $response = Http::get($urlRequest); 
        return $response->json();
    }

    public function create($payload)
    {

        $fullUrlAction =  "{$this->baseUrl}/accounting-account/";
        return Http::acceptJson()
            ->asJson()
            ->post($fullUrlAction, $payload);
    }

    public function delete(AccountingAccount $accountingAccount)
    {

    }
}


?>