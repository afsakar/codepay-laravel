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
                @permission('accounts.delete')
                <x-dropdown.item type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                    <span>{{ __('Delete') }}</span>
                </x-dropdown.item>
                @endpermission
            </x-dropdown>
            @endempty
            @permission('accounts.create')
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
                        @foreach (App\Models\Account::STATUS as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
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
        </x-filter-bar>

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
                        @permission('accounts.delete')
                        <x-table.column class="pr-0 w-8">
                            <x-input.checkbox wire:model="selectPage" />
                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['owner'] ?? null" wire:click="sortBy('owner')">{{ __('Owner') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['description'] ?? null" wire:click="sortBy('description')">{{ __('Description') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['account_type_id'] ?? null" wire:click="sortBy('account_type_id')">{{ __('Account Type') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['balance'] ?? null" wire:click="sortBy('balance')">{{ __('Balance') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        @permission('accounts.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                    <x-table.cell colspan="9">
                        @unless ($selectAll)
                            <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $accounts->count(), 'totalCount' => $accounts->total()]) !!}
                                </span>
                                <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                            </div>
                        @else
                        <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $accounts->total()]) !!}
                        </span>
                        @endif
                    </x-table.cell>
                </x-table.row>
                @endif
                @forelse ($accounts as $account)
                <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $account->id }}">
                    @permission('accounts.delete')
                    <x-table.cell class="pr-0">
                        <x-input.checkbox wire:model="selected" value="{{ $account->id }}" />
                    </x-table.cell>
                    @endpermission
                    <x-table.cell>
                        {{ $account->name }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->owner }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->description }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->account_type()->first()->name }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $account->balance_with_currency }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-input.toggle wire:click="toggleSwitch({{$account->id}})" :active="$account->status == 'active'" />
                    </x-table.cell>
                    <x-table.cell title="{{ $account->created_at }}">
                        {{ $account->created_at->diffForHumans() }}
                    </x-table.cell>
                    @permission('accounts.update')
                    <x-table.cell>
                        <div class="flex items-center space-x-4 text-sm">
                            <x-button wire:click="edit({{ $account->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                <x-heroicon-o-pencil class="h-5 w-5" />
                            </x-button>
                        </div>
                    </x-table.cell>
                    @endpermission
                </x-table.row>
                @empty
                <x-table.cell colspan="9" class="dark:text-gray-400 dark:bg-gray-700">
                    <div class="flex items-center justify-center text-gray-400">
                     <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                    </div>
                </x-table.cell>
                @endforelse
            </x-table>
            {{ $accounts->links() }}
        </x-card>
    </div>

    @if(permission_check('accounts','create') || permission_check('accounts','update'))
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
                <x-input.group inline for="owner" :label="__('Account Owner')" :error="$errors->first('editing.owner')">
                    <x-input.text wire:model.defer="editing.owner" id="owner" />
                </x-input.group>
                <x-input.group inline for="filter-account-type" :label="__('Account Type')">
                    <x-input.select id="filter-account-type" wire:model.defer="editing.account_type_id">
                        @foreach ($this->accountTypes as $type)
                            <option value="{{ $type->id }}">{{ __($type->name) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="description" :label="__('Account Description')" :error="$errors->first('editing.description')">
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
                    <x-input.select id="currency" wire:model.defer="editing.currency_id">
                        @foreach ($this->currencies as $currency)
                            <option value="{{ $currency->id }}">{{ __($currency->name)." [".$currency->symbol."]" }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="currency-status" :label="__('Currency Status')">
                    <x-input.select wire:click="changeCurrencyPosition($event.target.value)" id="currency-status" wire:model.defer="editing.currency_status">
                        <option value="after">{{ __('After') }}</option>
                        <option value="before">{{ __('Before') }}</option>
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="balance" :label="__('Account Balance')" :error="$errors->first('editing.balance')">
                    <x-input.money wire:model.defer="editing.balance" id="balance" :currency="$editing['currency']['symbol']" :position="$currencyPosition" />
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
    @endif

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

</div>
