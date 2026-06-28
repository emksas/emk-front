<?php

namespace App\services;

use App\Http\Requests\UpdateAccountingAccountRequest;
use App\Models\AccountingAccount;
use Illuminate\Support\Facades\Auth;

class AccountingAccountService
{
    public function getAllAccountingAccounts(): array
    {
        return AccountingAccount::all()->where('userId', Auth::id())->where('esProyeccion', false)->toArray();
    }

    public function createAccountingAccount($request)
    {
        AccountingAccount::create($request->validated());
    }

    public function deleteAccountingAccount(AccountingAccount $accountingAccount)
    {
        $accountingAccount->delete();
    }
}


?>