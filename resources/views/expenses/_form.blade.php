@php
    // Para edición, $employee llega definido; en creación es null.
    $val = fn($key, $default = '') => old($key, isset($employee) ? ($employee->{$key} ?? $default) : $default);
@endphp

<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nombre *</label>
            <input type="text" name="first_name" value="{{ $val('first_name') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('first_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Apellido *</label>
            <input type="text" name="last_name" value="{{ $val('last_name') }}" class="w-full border rounded px-3 py-2"
                required>
            @error('last_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Correo *</label>
        <input type="email" name="email" value="{{ $val('email') }}" class="w-full border rounded px-3 py-2" required>
        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Teléfono</label>
        <input type="text" name="phone_number" value="{{ $val('phone_number') }}"
            class="w-full border rounded px-3 py-2">
        @error('phone_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Fecha de contratación *</label>
            <input type="date" name="hire_date"
                value="{{ old('hire_date', isset($employee) && $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('hire_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Cargo *</label>
            <select name="job_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Selecciona --</option>
                @foreach($jobs as $j)
                    <option value="{{ $j->job_id }}" @selected($val('job_id') == $j->job_id)>
                        {{ $j->job_title }}
                    </option>
                @endforeach
            </select>
            @error('job_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Salario *</label>
            <input type="number" step="0.01" name="salary" value="{{ $val('salary') }}"
                class="w-full border rounded px-3 py-2" required>
            @error('salary')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Departamento</label>
            <select name="department_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Ninguno --</option>
                @foreach($depts as $d)
                    <option value="{{ $d->department_id }}" @selected($val('department_id') == $d->department_id)>
                        {{ $d->department_name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jefe (manager)</label>
            <select name="manager_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Ninguno --</option>
                @foreach($managers as $m)
                    <option value="{{ $m->employee_id }}" @selected($val('manager_id') == $m->employee_id)>
                        {{ $m->last_name }}, {{ $m->first_name }}
                    </option>
                @endforeach
            </select>
            @error('manager_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>