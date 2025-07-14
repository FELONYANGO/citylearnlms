@props(['messages'])

@if ($messages)
<ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
    @foreach ((array) $messages as $message)
    <li class="flex items-center">
        <svg class="h-4 w-4 text-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $message }}
    </li>
    @endforeach
</ul>
@endif