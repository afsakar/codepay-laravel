@if(!$permission || permission_check($permission, 'read'))
<li class="relative px-6 py-3">
    @if(Request::is("$active/*") || Request::is("$active"))
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true" ></span>
    @endif
    @if($methodFrom != "")
        <x-button
        :class="Request::segment(1) == $active ? 'bg-cool-gray-200 p-2 rounded-md dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200' : 'focus:outline-none inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200'"
        x-on:click="{{$methodFrom}}"
        aria-haspopup="true">

        <span class="inline-flex items-center">
            {{$icon}}
            <span class="ml-4">{{$title}}</span>
        </span>

        @if($submenus)
            <template x-if="{{$methodTo}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </template>
            <template x-if="!{{$methodTo}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </template>
        @endif
        </x-button>
    @else
        <x-button.link :url="$url"
        :class="Request::segment(1) == $active ? 'bg-cool-gray-200 p-2 rounded-md dark:bg-gray-900 text-gray-800 dark:text-gray-100 inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200' : 'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200'">
            {{$icon}}
            <span class="ml-4">{{$title}}</span>
        </x-button.link>
    @endif
    @if($submenus)
        <template x-if="{{$methodTo}}">
            <ul
                x-transition:enter="transition-all ease-in-out duration-300"
                x-transition:enter-start="opacity-25 max-h-0"
                x-transition:enter-end="opacity-100 max-h-xl"
                x-transition:leave="transition-all ease-in-out duration-300"
                x-transition:leave-start="opacity-100 max-h-xl"
                x-transition:leave-end="opacity-0 max-h-0"
                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                aria-label="submenu">
                {{$submenus}}
            </ul>
        </template>
    @endif
</li>
@endif
