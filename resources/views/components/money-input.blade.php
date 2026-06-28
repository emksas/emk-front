@props([
    'name',
    'value' => '',
    'id' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
])

<input
    type="text"
    inputmode="decimal"
    autocomplete="off"
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    value="{{ $value }}"
    data-money-input
    {{ $required ? 'required' : '' }}
    {{ $readonly ? 'readonly' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'w-full border rounded px-3 py-2']) !!}
>

@once
    @push('scripts')
        <script>
            (function () {
                const formatter = new Intl.NumberFormat('es-CO', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                function normalizeMoney(value) {
                    if (value === null || value === undefined) {
                        return '';
                    }

                    const cleaned = String(value).replace(/[^\d,.-]/g, '');

                    if (!cleaned) {
                        return '';
                    }

                    if (cleaned.includes(',')) {
                        return cleaned.replace(/\./g, '').replace(',', '.');
                    }

                    const dots = (cleaned.match(/\./g) || []).length;
                    const lastDot = cleaned.lastIndexOf('.');
                    const decimals = lastDot >= 0 ? cleaned.slice(lastDot + 1) : '';

                    if (dots === 1 && decimals.length > 0 && decimals.length <= 2) {
                        return cleaned;
                    }

                    return cleaned.replace(/\./g, '');
                }

                function formatMoneyValue(value) {
                    const normalized = normalizeMoney(value);

                    if (normalized === '') {
                        return '';
                    }

                    const number = Number(normalized);

                    if (!Number.isFinite(number)) {
                        return '';
                    }

                    return `$ ${formatter.format(number)}`;
                }

                function formatPartialInput(input) {
                    let value = input.value.replace(/[^\d,]/g, '');

                    if (!value) {
                        input.value = '';
                        return;
                    }

                    const parts = value.split(',');
                    const integerPart = parts[0].replace(/\D/g, '');
                    const decimalPart = parts.length > 1 ? parts.slice(1).join('').replace(/\D/g, '').slice(0, 2) : null;
                    const groupedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                    input.value = `$ ${groupedInteger}${decimalPart !== null ? `,${decimalPart}` : ''}`;
                }

                function prepareMoneyInputs(form) {
                    form.querySelectorAll('[data-money-input]').forEach((input) => {
                        input.value = normalizeMoney(input.value);
                    });
                }

                function initializeMoneyInput(input) {
                    if (input.dataset.moneyReady === 'true') {
                        return;
                    }

                    input.dataset.moneyReady = 'true';
                    input.value = formatMoneyValue(input.value);

                    input.addEventListener('input', () => formatPartialInput(input));
                    input.addEventListener('blur', () => {
                        input.value = formatMoneyValue(input.value);
                    });
                    input.addEventListener('focus', () => {
                        input.select();
                    });
                }

                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[data-money-input]').forEach(initializeMoneyInput);
                    document.querySelectorAll('form').forEach((form) => {
                        form.addEventListener('submit', () => prepareMoneyInputs(form));
                    });
                });
            })();
        </script>
    @endpush
@endonce
