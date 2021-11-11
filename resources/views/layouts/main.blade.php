<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" :class="{ 'theme-dark': dark }" x-data="data()" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if (isset($header)) {{ $header }} - {{ config('app.name', 'Laravel') }} @else {{ config('app.name', 'Laravel') }} @endif</title>

    <link rel="stylesheet" href="{{ mix('css/app.css')}}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    @livewireStyles

    @include('layouts.includes.style')

</head>
<body>
    
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }" >

      <!-- Desktop sidebar -->
      @include('layouts.includes.aside')

      <div class="flex flex-col flex-1 w-full">

        @include('layouts.includes.header')

        <main class="h-full overflow-y-auto w-full overflow-x-auto">
            <div class="container grid px-6 mx-auto">
                {{ $slot }}
            </div>
            <x-notification />
        </main>

      </div>
    </div>

    @livewireScripts
    @include('layouts.includes.script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
</body>
</html>