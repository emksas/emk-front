<?php

namespace App\Services;

use App\Models\AccountingAccount;
use Symfony\Component\HttpFoundation\AcceptHeaderItem;

class AccountingAccountService
{
    public function getAllAccountingAccounts()
    {
        return AccountingAccount::all()->toArray();
    }

    public function createAccountingAccount($request)
    {
        AccountingAccount::create($request->validated());
    }

    public function updateAccountingAccount($request, AccountingAccount $accountingAccount)
    {
        $accountingAccount->update($request->validated());
    }

    public function deleteAccountingAccount(AccountingAccount $accountingAccount)
    {
        $accountingAccount->delete();
    }
}


?>