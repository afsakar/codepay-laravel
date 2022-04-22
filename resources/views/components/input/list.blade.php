@props([
'leadingAddOn' => false,
'id' => '',
])

<div class="flex rounded-md">
    @if ($leadingAddOn)
        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
            {{ $leadingAddOn }}
        </span>
    @endif

    <input list="{{ $id }}" autocomplete="off" {{ $attributes->merge(['class' => 'flex-1 form-input border-cool-gray-300 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:text-gray-400 dark:bg-gray-800 border-1 dark:border-gray-600' . ($leadingAddOn ? ' rounded-none rounded-r-md' : '')]) }}/>
</div>

<datalist id="{{ $id }}">
    {{ $slot }}
</datalist>
