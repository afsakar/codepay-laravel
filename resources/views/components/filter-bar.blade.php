<div>
    <div x-show="showFilters"
         x-transition:enter="transition-all ease-in-out duration-300"
         x-transition:enter-start="opacity-25 max-h-0"
         x-transition:enter-end="opacity-100 max-h-xl"
         x-transition:leave="transition-all ease-in-out duration-300"
         x-transition:leave-start="opacity-100 max-h-xl"
         x-transition:leave-end="opacity-0 max-h-0">
        <x-card class="mb-4" color="bg-cool-gray-200 dark:bg-gray-700">
            <div class="md:flex md:relative">
                {{ $slot }}
            </div>
        </x-card>
    </div>
</div>
