@props(['currency' => ''])

<div class="mt-1 relative rounded-md shadow-sm">
    {{-- 
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm sm:leading-5">
            ₺
        </span>
    </div>
    --}}

    <input type="text" {{ $attributes }} class="form-input block w-full p-3 pr-12 sm:text-sm sm:leading-5 dark:text-gray-400 dark:bg-gray-800 border-1 dark:border-gray-600" placeholder="0.00" aria-describedby="price-currency">
    
    {{--
    <div class="absolute inset-y-0 right-0 pr-10 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm sm:leading-5" id="price-currency">
            {{ $currency }}
        </span>
    </div>
    --}}
</div>