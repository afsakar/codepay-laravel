<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Account List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Account List')}}
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
            <x-dropdown :label="__('Bulk Actions')">
                <x-dropdown.item type="button" wire:click="exportExcel" class="flex items-center space-x-2">
                    <span>{{ __('Export with Excel') }}</span>
                </x-dropdown.item>
                <x-dropdown.item type="button" wire:click="exportPdf" class="flex items-center space-x-2">
                    <span>{{ __('Export with PDF') }}</span>
                </x-dropdown.item>
                <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                    <span>{{ __('Delete') }}</span>
                </x-dropdown.item>
            </x-dropdown>
            @endempty
            <x-button wire:click="create" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-plus class="h-5 w-5 mr-1" /> <span>{{ __('New') }}</span>
            </x-button>
        </div>
    </h4>

    <div class="w-full overflow-hidden">
        {{-- Filters --}}
        <div>
            <div x-show="showFilters"
            x-transition:enter="transition-all ease-in-out duration-300"
            x-transition:enter-start="opacity-25 max-h-0"
            x-transition:enter-end="opacity-100 max-h-xl"
            x-transition:leave="transition-all ease-in-out duration-300"
            x-transition:leave-start="opacity-100 max-h-xl"
            x-transition:leave-end="opacity-0 max-h-0">
                <x-card class="mb-4" color="bg-cool-gray-200 dark:bg-gray-700">
                    <div class="md:flex md:relative">
                        <div class="md:w-1/2 pr-2 space-y-4">
                            <x-input.group inline for="filter-status" label="Status">
                                <x-input.select id="filter-status" wire:model="filters.status">
                                    <option value="" disabled>Select Status...</option>
                                    @foreach (App\Models\Account::STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>

                            <x-input.group inline for="filter-balance-min" label="Minimum Balance">
                                <x-input.money wire:model.lazy="filters.balance-min" id="filter-balance-min" />
                            </x-input.group>

                            <x-input.group inline for="filter-balance-max" label="Maximum Balance">
                                <x-input.money wire:model.lazy="filters.balance-max" id="filter-balance-max" />
                            </x-input.group>
                        </div>

                        <div class="md:w-1/2 pl-2 space-y-4">
                            <x-input.group inline for="filter-date-min" label="Minimum Date">
                                <x-input.date wire:model="filters.date-min" id="filter-date-min" placeholder="MM/DD/YYYY" />
                            </x-input.group>

                            <x-input.group inline for="filter-date-max" label="Maximum Date">
                                <x-input.date wire:model="filters.date-max" id="filter-date-max" placeholder="MM/DD/YYYY" />
                            </x-input.group>

                            <x-button.link wire:click="resetFilters" class="md:absolute right-0 bottom-0 p-4 dark:text-gray-400">{{ __('Reset Filters') }}</x-button.link>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <x-card>
            {{-- Search Area --}}
            <div class="grid grid-cols-2 gap-4 py-4 dark:text-gray-400 dark:bg-gray-800">
                <x-input.text wire:model="filters.search" placeholder="Search Accounts..."  />

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
                            <x-input.checkbox wire:model="selectPage" />
                        </x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">Name</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['description'] ?? null" wire:click="sortBy('description')">Description</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['balance'] ?? null" wire:click="sortBy('balance')">Balance</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">Status</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">Created At</x-table.column>
                        <x-table.column>Actions</x-table.column>
                    </x-table.row>
                </x-slot>
                <x-slot name="body">
                    @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="7">
                            @unless ($selectAll)
                                <div>
                                    <span>
                                        You have selected <strong>{{ $accounts->count() }}</strong> items. Do you want to select all <strong>{{ $accounts->total() }}</strong> items?
                                    </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">Select All</button>
                                </div>
                            @else
                            <span>
                                You are currently selecting all <strong>{{ $accounts->total() }}</strong> items.
                            </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                    @endif
                    @forelse ($accounts as $account)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $account->id }}">
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $account->id }}" />
                        </x-table.cell>
                        <x-table.cell>
                            {{ $account->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $account->description }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $account->balance_with_currency }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge :color="$account->status_color">
                                {{ __(App\Models\Account::STATUS[$account->status]) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell title="{{ $account->created_at }}">
                            {{ $account->created_at->diffForHumans() }}
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $account->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                                <x-button wire:click="$toggle('singleDelete', true)" aria-label="Delete" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </x-button>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @empty
                    <x-table.cell colspan="7" class="dark:text-gray-400 dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                         <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                    @endforelse
                </x-slot>
            </x-table>
            {{ $accounts->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                {{ __('Editing Account') }}
                @else
                {{ __('Create Account') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-input.group inline for="name" :label="__('Account Name')" :error="$errors->first('editing.name')">
                    <x-input.text wire:model.defer="editing.name" id="name" />
                </x-input.group>
                <x-input.group inline for="description" :label="__('Account description')" :error="$errors->first('editing.description')">
                    <x-input.textarea wire:model.defer="editing.description" id="description" />
                </x-input.group>
                <x-input.group inline for="filter-status" :label="__('Status')">
                    <x-input.select id="filter-status" wire:model.defer="editing.status">
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        @foreach (App\Models\Account::STATUS as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="currency" :label="__('Account Currency')" :error="$errors->first('editing.currency')">
                    <x-input.text wire:model.defer="editing.currency" id="currency" />
                </x-input.group>
                <x-input.group inline for="currency-status" :label="__('Currency Status')">
                    <x-input.select id="currency-status" wire:model.defer="editing.currency_status">
                        <option value="after">{{ __('After') }}</option>
                        <option value="before">{{ __('Before') }}</option>
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="balance" :label="__('Account Balance')" :error="$errors->first('editing.balance')">
                    <x-input.text wire:model.defer="editing.balance" id="balance" />
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
                {{ __('Are you sure you want to delete the selected accounts? This action is irreversible!') }}
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

    {{-- Single Delete Modal --}}
    <form wire:submit.prevent="deleteSingle({{$account->id}})">
        <x-jet-confirmation-modal wire:model.defer="singleDelete">
            <x-slot name="title">

            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete row? This action is irreversible!') }}
            </x-slot>

            <x-slot name="footer">
                <x-button type="button" wire:click="$set('singleDelete', false)" class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="submit" class="text-white bg-red-700 rounded-lg px-3 py-1 text-sm font-medium leading-5" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </form>

</div>
