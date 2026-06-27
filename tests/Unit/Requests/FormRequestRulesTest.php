<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\StoreAccountingAccountRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateAccountingAccountRequest;
use App\Http\Requests\UpdateExpenseRequest;
use PHPUnit\Framework\TestCase;

class FormRequestRulesTest extends TestCase
{
    public function test_store_expense_request_rules_are_defined(): void
    {
        $rules = (new StoreExpenseRequest())->rules();

        $this->assertSame('required|numeric', $rules['valor']);
        $this->assertSame('required|string|max:255', $rules['descripcion']);
        $this->assertSame('required|date', $rules['fecha']);
        $this->assertSame('required', $rules['cuentacontable_id']);
    }

    public function test_update_expense_request_rules_are_defined(): void
    {
        $rules = (new UpdateExpenseRequest())->rules();

        $this->assertSame('required|numeric', $rules['valor']);
        $this->assertSame('required|string|max:255', $rules['descripcion']);
        $this->assertSame('required|date', $rules['fecha']);
        $this->assertSame('nullable|integer', $rules['cuentacontable_id']);
    }

    public function test_accounting_account_request_rules_are_defined(): void
    {
        $storeRules = (new StoreAccountingAccountRequest())->rules();
        $updateRules = (new UpdateAccountingAccountRequest())->rules();

        $this->assertSame('required|string|max:255', $storeRules['descripcion']);
        $this->assertSame('required', $storeRules['userId']);
        $this->assertSame($storeRules, $updateRules);
    }

    public function test_requests_authorize_by_default(): void
    {
        $this->assertTrue((new StoreExpenseRequest())->authorize());
        $this->assertTrue((new UpdateExpenseRequest())->authorize());
        $this->assertTrue((new StoreAccountingAccountRequest())->authorize());
        $this->assertTrue((new UpdateAccountingAccountRequest())->authorize());
    }
}
