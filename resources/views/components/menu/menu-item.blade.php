<li class="relative px-6 py-3">
    @if(Request::segment(1) == $active)
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true" ></span>
    @endif
    @if($methodFrom != "")
        <x-button
        :class="Request::segment(1) == $active ? 'text-gray-800 dark:text-gray-100 focus:outline-none inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200' : 'focus:outline-none inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200'"
        x-on:click="{{$methodFrom}}"
        aria-haspopup="true">

        <span class="inline-flex items-center">
            {{$icon}}
            <span class="ml-4">{{$title}}</span>
        </span>

        @if($submenus)
            <svg
            class="w-4 h-4"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" ></path>
            </svg>
        @endif
        </x-button>
    @else
        <x-button.link :url="$url"
        :class="Request::segment(1) == $active ? 'text-gray-800 dark:text-gray-100 inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200' : 'inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200'">
            {{$icon}}
            <span class="ml-4">{{$title}}</span>
        </x-button.link>
    @endif
    @if($submenus)
        <template x-if="{{$methodTo}}">
            <ul
                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                aria-label="submenu">
                {{$submenus}}
            </ul>
        </template>
    @endif
  </li>
