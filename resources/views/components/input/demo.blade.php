@props([
    'id' => '',
    'list' => [],
])
<div
    x-data="{
    value: '',
    showList: false,
    toggleList() { this.showList = ! this.showList },
    list: {{ json_encode($list) }}
        }"
     x-init="$watch('value')">
    <span x-text="value"></span>
    <input autocomplete="off" {{ $attributes->merge(['class' => 'flex-1 form-input border-cool-gray-300 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:text-gray-400 dark:bg-gray-800 border-1 dark:border-gray-600']) }}
        id="{{ $id }}"
        @click="toggleList"
        @keydown.escape="toggleList()"
        x-bind:value="value"
    />
    <div x-show="showList" x-cloak
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <ul>
            <div class="bg-gray-100 rounded-lg shadow-lg p-4 absolute">
                <template x-for="(item, key) in list" :key="key">
                    <li class="py-2 cursor:pointer">
                        <div class="form-check form-check-inline m-0">
                            <input class="hidden" x-model="value" name="{{ $id }}" type="radio" x-bind:id="'option-'+key" :value="key">
                            <label class="hover:bg-gray-200 px-5 py-2 hover:rounded-md cursor-pointer" x-bind:for="'option-'+key" x-text="item" @click="toggleList()"></label>
                        </div>
                    </li>
                </template>
            </div>
        </ul>
    </div>
</div>
