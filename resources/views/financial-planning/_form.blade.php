@php

use Illuminate\Support\Carbon;

$financialPlanningDate = data_get($financialPlanning, 'fecha', data_get($financialPlanning, 'projectedDate'));
$date = old(
'fecha',
isset($financialPlanning) && $financialPlanningDate
? Carbon::parse($financialPlanningDate)->format('Y-m-d')
: ''
);

$val = function ($key, $default = '') use ($financialPlanning) {
    $serviceKeys = [
        'valor' => 'projectedValue',
        'descripcion' => 'description',
    ];

    return old(
        $key,
        data_get($financialPlanning, $key, data_get($financialPlanning, $serviceKeys[$key] ?? $key, $default))
    );
};
@endphp

<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="valor" class="block text-sm font-medium mb-1">Projected Value *</label>
            <x-money-input id="valor" name="valor" :value="$val('valor')" required />
            @error('valor')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="descripcion" class="block text-sm font-medium mb-1">Description *</label>
            <input type="text" id="descripcion" name="descripcion" value="{{ $val('descripcion') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('descripcion')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2 flex justify-center">
            <div class="w-full">
                <label for="fecha" class="block text-sm font-medium mb-1">Date *</label>
                <input type="date" id="fecha" name="fecha"
                    class="w-full border rounded px-3 py-2"
                    value="{{ $date }}" required>
                @error('fecha')
                <p class="text-red-600 text-sm mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>
        </div>

    </div>

</div>
