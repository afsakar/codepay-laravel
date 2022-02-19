<x-menu>
  <x-slot name="header">
    <span class="mx-3 text-center text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center justify-center">
        {{ get_company_info()->name }}
    </span>
  </x-slot>
</x-menu>
@include('layouts.includes.mobile-sidebar')
