@props(['header' => "", 'color' => "bg-white dark:bg-gray-800"])

<div {{ $attributes->merge(['class' => 'min-w-0 p-4 '. $color .' rounded-lg shadow-xs']) }}>
    @if($header)
        <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
            {{ $header }}
        </h4>
    @endif
    <p class="text-gray-600 dark:text-gray-400">
      {{ $slot }}
    </p>
</div>
