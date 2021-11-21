@props(['tabs', 'color' => 'gray'])

<div x-data="{ openTab: 1 }" class="p-6">
    <ul class="flex">
        @foreach($tabs as $key => $tab)
            <li @click="openTab = {{ $key }} + 1" :class="{ '-mb-px': openTab === {{ $key }} + 1 }" class="-mb-px mr-1">
                <a :class="openTab === {{ $key }} + 1 ? 'border border-{{ $color }}-400 rounded-md bg-{{ $color }}-50 text-{{ $color }}-600 dark:text-{{ $color }}-400 dark:bg-{{ $color }}-700' : 'text-{{ $color }}-600 hover:rounded-md hover:text-{{ $color }}-600 hover:bg-{{ $color }}-100'" class="inline-block py-1 px-2 font-semibold" href="#">
                    {{ $tab }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="w-full pt-4">
        {{ $slot }}
    </div>
</div>
