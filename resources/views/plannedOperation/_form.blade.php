<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1">Description *</label>
            <input type="text" name="description" value="{{ old('description', data_get($expense, 'description', '')) }}"
                class="w-full border rounded px-3 py-2" required>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Accounting Account*</label>
            <select id="accountingAccount" name="accountId" class="w-full border rounded px-3 py-2" required>
                @foreach ($accountingAccounts as $accountingAccount)
                    <option value="{{ $accountingAccount['id'] }}" {{ (isset($expense) && $expense['accountId'] == $accountingAccount['id']) ? 'selected' : '' }}>
                        {{ $accountingAccount['descripcion'] }}
                    </option>

                @endforeach
            </select>
            @error('accountId')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror

        </div>


        <div>
            <label class="block text-sm font-medium mb-1">Projected Value *</label>
            <input type="number" step="0.01" id="projectedValue" name="projectedValue" value="{{  old('projectedValue', data_get($expense, 'projectedValue', ''))  }}"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Quantity *</label>
            <input type="number" step="1" id="amount" name="amount" value="{{ old('amount', data_get($expense, 'amount', ''))  }}"
                class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Total Projected Value *</label>
            <input type="number" step="0.01" id="totalProjectedValue" name="totalProjectedValue" readonly
                class="w-full border rounded px-3 py-2 bg-gray-100" required>
        </div>


    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const projectedInput = document.getElementById('projectedValue');
        const amountInput = document.getElementById('amount');
        const totalInput = document.getElementById('totalProjectedValue');

        function calculateTotal() {
            const projectedValue = parseFloat(projectedInput.value) || 0;
            const amount = parseFloat(amountInput.value) || 0;

            const total = projectedValue * amount;

            // Limitar a 2 decimales
            totalInput.value = total.toFixed(2);
        }

        projectedInput.addEventListener('input', calculateTotal);
        amountInput.addEventListener('input', calculateTotal);

        // Calcular al cargar (por si viene en edición)
        calculateTotal();
    });
</script>