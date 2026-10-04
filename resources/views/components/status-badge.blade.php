@props(['status'])

@php
    $styles = [
        'Wishlist'       => ['bg-gray-50',     'text-gray-600',     'border-gray-200',     'bg-gray-400'],
        'Applied'        => ['bg-blue-50',     'text-blue-700',     'border-blue-100',     'bg-blue-500'],
        'Assessment'     => ['bg-amber-50',   'text-amber-700',    'border-amber-100',    'bg-amber-500'],
        'HR Interview'   => ['bg-emerald-50',  'text-emerald-700',  'border-emerald-100',  'bg-emerald-500'],
        'User Interview' => ['bg-purple-50',   'text-purple-700',   'border-purple-100',   'bg-purple-500'],
        'Offered'        => ['bg-green-50',    'text-green-700',    'border-green-200',    'bg-green-600'],
        'Rejected'       => ['bg-red-50',      'text-red-600',      'border-red-100',      'bg-red-400'],
        'Ghosted'        => ['bg-gray-100',    'text-gray-400',     'border-gray-200',     'bg-gray-300'],
    ];
    $s = $styles[$status] ?? $styles['Wishlist'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 text-xs font-medium px-2 py-0.5 rounded border {$s[0]} {$s[1]} {$s[2]}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $s[3] }}"></span>
    {{ $status }}
</span>
