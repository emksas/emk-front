@php

    use Illuminate\Support\Carbon;
    $date = old(
        'fecha',
        isset($expense) && $expense->fecha
        ? Carbon::parse($expense->fecha)->format('Y-m-d')
        : ''
    );
    // Para edición, $employee llega definido; en creación es null.
    $val = fn($key, $default = '') => old($key, isset($expense) ? ($expense->{$key} ?? $default) : $default);
@endphp

<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Value *</label>
            <input type="numeric" name="valor" value="{{ $val('valor') }}" class="w-full border rounded px-3 py-2"
                required>
            @error('valor')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Description *</label>
            <input type="text" name="descripcion" value="{{ $val('descripcion') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('descripcion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Date * {{ $date }}</label>
            <input type="date" name="fecha" class="w-full border rounded px-3 py-2" value="{{ $date }}" required>
            @error('fecha')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Accounting Account*</label>
            <select id="accountingAccount" name="cuentacontable_id" class="w-full border rounded px-3 py-2" required>
                @foreach ($accountingAccounts as $accountingAccount)
                    <option value="{{ $accountingAccount['id'] }}" {{ (isset($expense) && $expense['cuentacontable_id'] == $accountingAccount['id']) ? 'selected' : '' }}>
                        {{ $accountingAccount['descripcion'] }}
                    </option>

                @endforeach
            </select>
            @error('cuentacontable_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror

        </div>
    </div>

</div>