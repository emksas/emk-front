@php
    // Para edición, $employee llega definido; en creación es null.
    $val = fn($key, $default = '') => old($key, isset($accountingAccount) ? ($accountingAccount->{$key} ?? $default) : $default);
@endphp

<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium mb-1">Description *</label>
            <input type="text" name="descripcion" value="{{ $val('descripcion') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">User Id *</label>
            <input type="text" name="userId value="{{ $val('usuario_cedula') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

    </div>

</div>