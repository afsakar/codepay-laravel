@props(['currency' => 'TL', 'position' => 'after'])

@php
    if($position == 'before') {
        $currencyClass = "left-0 rounded-l-md";
        $inputClass = "pl-10 pr-12";
    } else {
        $currencyClass = "right-0 rounded-r-md";
        $inputClass = "";
    }
@endphp

<div class="mt-1 relative rounded-md shadow-sm">
    <div class="absolute bg-gray-50 flex inset-y-0 items-center {{ $currencyClass }} p-3 pointer-events-none dark:bg-gray-900 dark:border-gray-600 dark:text-gray-400 border">
        <span class="text-gray-500 sm:text-sm sm:leading-5 font-bold">
            {{ $currency }}
        </span>
    </div>
    <input autocomplete="off" @if($position == "before") style="padding-left: 3rem;" @endif type="text" {{ $attributes }}
           class="form-input block w-full p-3 sm:text-sm sm:leading-5 {{ $inputClass }} dark:text-gray-400 dark:bg-gray-800 border-1 dark:border-gray-600"
           placeholder="0.00" aria-describedby="price-currency">
</div>
