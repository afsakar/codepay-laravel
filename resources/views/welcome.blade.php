<x-main-layout>
    <x-slot name="header">
        {{ __('TALL STACK DASHBOARD') }}
    </x-slot>

    <h4 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        {{ __('TALL STACK DASHBOARD') }}
    </h4>
    <div class="w-full overflow-hidden rounded-lg">

      <x-card>
          <x-slot name="header">
              Selamlar
          </x-slot>

          {{ App::getLocale() }}
          {{ Session::get('locale') }}

      </x-card>

    </div>
</x-main-layout>
