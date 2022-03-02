@props(['color' => 'gray', 'id' => '', 'dismissible' => false, 'icon' => false])

<div class="alert dark:bg-{{ $color }}-700 dark:text-{{ $color }}-100 bg-{{ $color }}-100 rounded-lg py-5 px-6 mb-3 text-base text-{{ $color }}-700 inline-flex items-center w-full @if($dismissible) alert-dismissible fade show @endif" role="alert">
    @if($icon)
        <span class="text-{{ $color }}-700 dark:text-{{ $color }}-100">
        {{ $icon }}
        </span>
    @endif
        <div class="ml-3 text-sm font-medium">
            {{ $slot }}
        </div>
    @if($dismissible)
        <button type="button" class="btn-close box-content p-1 ml-auto text-{{ $color }}-700 border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-{{ $color }}-900 hover:opacity-75 hover:no-underline" data-bs-dismiss="alert" aria-label="Close">
            <x-heroicon-s-x class="w-5 h-5" />
        </button>
    @endif
</div>
