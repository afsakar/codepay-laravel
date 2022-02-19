@props(['type' => 'link'])

@if ($type === 'link')
    <a {{ $attributes->merge(['href' => '#', 'class' => 'disabled:opacity-50 disabled:hover:bg-transparent block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none dark:focus:bg-gray-900 focus:bg-gray-100 focus:text-gray-900 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200']) }} role="menuitem">
        {{ $slot }}
    </a>
@elseif ($type === 'button')
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'disabled:opacity-50 disabled:hover:bg-transparent block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none dark:focus:bg-gray-900 focus:bg-gray-100 focus:text-gray-900 dark:bg-gray-700 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800']) }} role="menuitem">
        {{ $slot }}
    </button>
@endif
