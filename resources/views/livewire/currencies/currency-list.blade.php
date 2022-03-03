<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Currency List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Currency List')}}
            <x-button x-on:click="openFilters" wire:click="toggleFilters" class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 dark:text-gray-400 border border-transparent rounded-lg focus:outline-none">
                <template x-if="showFilters">
                    <span class="flex items-center justify-between"><x-heroicon-o-chevron-up class="h-5 w-5 mr-1" /> {{ __("Close Filters") }}</span>
                </template>
                <template x-if="!showFilters">
                    <span class="flex items-center justify-between"><x-heroicon-o-chevron-down class="h-5 w-5 mr-1" /> {{ __("Open Filters") }}</span>
                </template>
            </x-button>
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            @empty(!$selected)
                @permission('currencies.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('currencies.create')
            <x-button wire:click="create" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-plus class="h-5 w-5 mr-1" /> <span>{{ __('New') }}</span>
            </x-button>
            @endpermission
        </div>
    </h4>

    <div class="w-full">
        {{-- Filters --}}
        <x-filter-bar>
            <div class="md:w-1/2 pr-2 space-y-4">
                <x-input.group inline for="filter-status" label="Status">
                    <x-input.select id="filter-status" wire:model="filters.status">
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        @foreach (App\Models\Currency::STATUS as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </div>

            <div class="md:w-1/2 pl-2 space-y-4">
                <x-button wire:click="resetFilters" class="md:absolute right-0 bottom-0 p-4 dark:text-gray-400">{{ __('Reset Filters') }}</x-button>
            </div>
        </x-filter-bar>

        <x-card>
            {{-- Search Area --}}
            <div class="grid grid-cols-2 gap-4 py-4 dark:text-gray-400 dark:bg-gray-800">
                <x-input.text wire:model="filters.search" placeholder="Search Currency List..."  />

                <div class="flex justify-end">
                    <x-input.select wire:model="perPage" id="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                    </x-input.select>
                </div>
            </div>

            {{-- Table --}}
            <x-table>
                <x-slot name="head">
                    <x-table.row class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase dark:border-gray-400 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                        <x-table.column class="pr-0 w-8">
                        </x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['code'] ?? null" wire:click="sortBy('code')">{{ __('Code') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['symbol'] ?? null" wire:click="sortBy('symbol')">{{ __('Symbol') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['position'] ?? null" wire:click="sortBy('position')">{{ __('Symbol Position') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        @permission('currencies.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="8">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $currencies->count(), 'totalCount' => $currencies->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $currencies->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($currencies as $currency)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $currency->id }}">
                        <x-table.cell class="pr-0">
                        @if(!in_array($currency->id, [1,2,3,4]))
                            @permission('currencies.delete')
                                <x-input.checkbox wire:model="selected" value="{{ $currency->id }}" />
                            @endpermission
                        @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $currency->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $currency->code }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $currency->symbol }}
                        </x-table.cell>
                        <x-table.cell>
                            <span class="capitalize">{{ $currency->position }}</span>
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge :color="$currency->status_color">
                                {{ __(App\Models\Currency::STATUS[$currency->status]) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell title="{{ $currency->created_at }}">
                            {{ $currency->created_at->diffForHumans() }}
                        </x-table.cell>
                        @permission('currencies.update')
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $currency->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                            </div>
                        </x-table.cell>
                        @endpermission
                    </x-table.row>
                @empty
                    <x-table.cell colspan="8" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $currencies->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Currency Type') }}
                @else
                    {{ __('Create Currency Type') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-input.group inline for="name" :label="__('Name')" :error="$errors->first('editing.name')">
                    <x-input.text wire:model.defer="editing.name" id="name" />
                </x-input.group>
                <x-input.group inline for="code" :label="__('Code')" :error="$errors->first('editing.code')">
                    <x-input.text wire:model.defer="editing.code" id="code" />
                </x-input.group>
                <x-input.group inline for="symbol" :label="__('Symbol')" :error="$errors->first('editing.symbol')">
                    <x-input.text wire:model.defer="editing.symbol" id="symbol" />
                </x-input.group>
                <x-input.group inline for="position" :label="__('Currency Position')">
                    <x-input.select id="position" wire:model.defer="editing.position">
                        <option value="after">{{ __("After") }}</option>
                        <option value="before">{{ __("Before") }}</option>
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="filter-status" :label="__('Status')">
                    <x-input.select id="filter-status" wire:model.defer="editing.status">
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        @foreach (App\Models\Currency::STATUS as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button type="button" wire:click="close()" class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="submit" class="text-white bg-green-700 rounded-lg px-3 py-1 text-sm font-medium leading-5" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-button>
            </x-slot>
        </x-jet-dialog-modal>
    </form>

    {{-- Multiple Delete Modal --}}
    <form wire:submit.prevent="deleteSelected">
        <x-jet-confirmation-modal wire:model.defer="deleteModal">
            <x-slot name="title">

            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete the selected items? This action is irreversible!') }}
            </x-slot>

            <x-slot name="footer">
                <x-button type="button" wire:click="$set('deleteModal', false)" class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="submit" class="text-white bg-red-700 rounded-lg px-3 py-1 text-sm font-medium leading-5" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </form>

</div>
