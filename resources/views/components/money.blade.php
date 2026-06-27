@props([
    'value' => 0,
    'decimals' => 2,
    'prefix' => '$',
])

@php
    $numericValue = is_numeric($value) ? (float) $value : 0;
@endphp

{{ $prefix }} {{ number_format($numericValue, $decimals, ',', '.') }}
