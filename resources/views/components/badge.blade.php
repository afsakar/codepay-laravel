@props(['color' => 'gray'])

<span class="text-{{$color}}-700 bg-{{$color}}-100 rounded-full dark:bg-{{$color}}-700 dark:text-{{$color}}-100 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-md"> {{ $slot }}</span>
