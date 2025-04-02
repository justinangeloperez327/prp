@props([
    'circular' => true,
    'size' => 'md',
])

{{-- <img
    {{
        $attributes
            ->class([
                'fi-avatar object-cover object-center',
                'rounded-md' => ! $circular,
                'fi-circular rounded-full' => $circular,
                match ($size) {
                    'sm' => 'h-6 w-6',
                    'md' => 'h-8 w-8',
                    'lg' => 'h-10 w-10',
                    default => $size,
                },
            ])
    }}
/> --}}

<a href="#" class="flex items-center space-x-2">
    <span>
        @if (filament()->auth()->user()->hasRole('customer'))
        {{ filament()->auth()->user()->contact->first_name }}
        {{ filament()->auth()->user()->contact->last_name }}
    @else
        {{ filament()->auth()->user()->email }}
    @endif
    </span>

    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
    </svg>
</a>
