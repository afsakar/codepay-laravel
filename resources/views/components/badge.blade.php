@props(['color' => 'gray'])
<span class="text-sm px-2 py-1 font-semibold leading-tight text-{{$color}}-700 bg-{{$color}}-100 rounded-full dark:bg-{{$color}}-700 dark:text-{{$color}}-100">
    {{ $slot }}
</span>