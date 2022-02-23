<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Expenses')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        <div class="flex items-center justify-between">
            {{__('Expenses')}}
            <x-button x-on:click="openFilters" wire:click="toggleFilters"
                      class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 dark:text-gray-400 border border-transparent rounded-lg focus:outline-none">
                <template x-if="showFilters">
                    <span class="flex items-center justify-between"><x-heroicon-o-chevron-up class="h-5 w-5 mr-1"/> {{ __("Close Filters") }}</span>
                </template>
                <template x-if="!showFilters">
                    <span class="flex items-center justify-between"><x-heroicon-o-chevron-down class="h-5 w-5 mr-1"/> {{ __("Open Filters") }}</span>
                </template>
            </x-button>
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            @empty(!$selected)
                @permission('expenses.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('expenses.create')
            <x-button wire:click="create"
                      class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-plus class="h-5 w-5 mr-1"/>
                <span>{{ __('New') }}</span>
            </x-button>
            @endpermission
        </div>
    </h4>

    <div class="w-full">
        {{-- Filters --}}
        <x-filter-bar>
            <div class="md:w-1/2 pr-2 space-y-4">
                <x-input.group inline for="filter-type" :label="__('Type')">
                    <x-input.select id="filter-type" wire:model="filters.type">
                        <option value="" disabled>{{ __('Select Type...') }}</option>
                        @foreach (App\Models\Expense::TYPES as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group inline for="filter-category_id" :label="__('Category')">
                    <x-input.select id="filter-category_id" wire:model="filters.category_id">
                        <option value="" disabled>{{ __('Select Category...') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group inline for="filter-amount-min" :label="__('Minimum Balance')">
                    <x-input.money wire:model.lazy="filters.amount-min" id="filter-amount-min" />
                </x-input.group>

                <x-input.group inline for="filter-amount-max" :label="__('Maximum Balance')">
                    <x-input.money wire:model.lazy="filters.amount-max" id="filter-amount-max" />
                </x-input.group>
            </div>

            <div class="md:w-1/2 pl-2 space-y-4">
                <x-input.group inline for="filter-supplier_id" :label="__('Supplier')">
                    <x-input.select id="filter-supplier_id" wire:model="filters.supplier_id">
                        <option value="" disabled>{{ __('Select Supplier...') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group inline for="filter-date-min" :label="__('Minimum Date')">
                    <x-input.date wire:model="filters.date-min" id="filter-date-min" placeholder="MM/DD/YYYY" />
                </x-input.group>

                <x-input.group inline for="filter-date-max" :label="__('Maximum Date')">
                    <x-input.date wire:model="filters.date-max" id="filter-date-max" placeholder="MM/DD/YYYY" />
                </x-input.group>

                <x-button wire:click="resetFilters" class="md:absolute right-0 bottom-0 p-4 dark:text-gray-400">{{ __('Reset Filters') }}</x-button>
            </div>
        </x-filter-bar>

        <x-card>
            {{-- Search Area --}}
            <div class="grid grid-cols-2 gap-4 py-4 dark:text-gray-400 dark:bg-gray-800">
                <x-input.text wire:model="filters.search" placeholder="Search Expenses..."/>

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
                    <x-table.row
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase dark:border-gray-400 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                        @permission('expenses.delete')
                        <x-table.column class="pr-0 w-8">
                            <x-input.checkbox wire:model="selectPage"/>
                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['due_at'] ?? null"
                                        wire:click="sortBy('due_at')">{{ __('Due At') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['amount'] ?? null"
                                        wire:click="sortBy('amount')">{{ __('Amount') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['exchange_rate'] ?? null"
                                        wire:click="sortBy('exchange_rate')">{{ __('Exchange Rate') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['supplier_id'] ?? null"
                                        wire:click="sortBy('supplier_id')">{{ __('Supplier') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['description'] ?? null"
                                        wire:click="sortBy('description')">{{ __('Description') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['account'] ?? null"
                                        wire:click="sortBy('account')">{{ __('Account') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['type'] ?? null"
                                        wire:click="sortBy('type')">{{ __('Type') }}</x-table.column>
                        @permission('expenses.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row
                        class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="9">
                            @unless ($selectAll)
                                <div>
                                    <span>
                                        {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $expenses->count(), 'totalCount' => $expenses->total()]) !!}
                                    </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">
                                        {{ __('Select All') }}
                                    </button>
                                </div>
                            @else
                                <span>
                                    {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $expenses->total()]) !!}
                                </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($expenses as $expense)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $expense->id }}">
                        @permission('expenses.delete')
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $expense->id }}"/>
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            {{ \Carbon\Carbon::parse($expense->due_at)->format('d/m/Y') }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $expense->amount_with_total_currency }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $expense->exchange_rate }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            {{ $expense->supplier->name }}
                        </x-table.cell>
                        <x-table.cell class="whitespace-normal">
                            {{ $expense->description }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $expense->account->name }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge color="indigo">
                                {{ __(App\Models\Expense::TYPES[$expense->type]) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell class="flex items-center items-center">
                            @permission('expenses.update')
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $expense->id }})" aria-label="Edit"
                                          class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5"/>
                                </x-button>
                            </div>
                            @endpermission
                            <x-button wire:click="toggleDetailModal({{ $expense->id }})" aria-label="Details"
                                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400 ml-4">
                                <x-heroicon-o-document-text class="h-5 w-5"/>
                            </x-button>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.cell colspan="9" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2"/>
                            <span class="text-medium py-6 text-lg">
                                {{ __('No records found matching your search term or no records have been added yet!') }}
                            </span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $expenses->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Expense') }}
                @else
                    {{ __('Create New Expense') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-input.group inline for="due-at" :label="__('Due At')" :error="$errors->first('editing.due_at')">
                    <x-input.date wire:model="editing.due_at" id="due-at" placeholder="MM/DD/YYYY" />
                </x-input.group>

                <x-input.group inline for="accounts" :label="__('Account')"
                               :error="$errors->first('editing.account_id')">
                    <x-input.select id="accounts" wire:model.defer="editing.account_id"
                                    wire:change="changeAccount($event.target.value)">
                        <option value="" disabled>{{ __('Select Account...') }}</option>
                        @foreach ($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ __($acc->name) }} ({{ $acc->balance_with_currency }})</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="suppliers" :label="__('Supplier')"
                               :error="$errors->first('editing.supplier_id')">
                    <x-input.select id="suppliers" wire:model.defer="editing.supplier_id">
                        <option value="" disabled>{{ __('Select Supplier...') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ __($supplier->name) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="categories" :label="__('Category')"
                               :error="$errors->first('editing.category_id')">
                    <x-input.select id="categories" wire:model.defer="editing.category_id">
                        <option value="" disabled>{{ __('Select Category...') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="description" :label="__('Description')"
                               :error="$errors->first('editing.description')">
                    <x-input.textarea wire:model.defer="editing.description" id="description"/>
                </x-input.group>

                <x-input.group inline for="amount" :label="__('Amount')" :error="$errors->first('editing.amount')">
                    <x-input.money wire:model.defer="editing.amount" id="amount" :currency="$symbol" :position="$currency_status"/>
                </x-input.group>

                <x-input.group inline for="exchange_rate" :label="__('Exchange Rate')" :error="$errors->first('editing.exchange_rate')">
                    <x-input.text wire:model.defer="editing.exchange_rate" id="exchange_rate" />
                </x-input.group>

                <x-input.group inline for="filter-type" :label="__('Type')" :error="$errors->first('editing.type')">
                    <x-input.select id="filter-type" wire:model.defer="editing.type">
                        <option value="" disabled>{{ __('Select Type...') }}</option>
                        @foreach (App\Models\Expense::TYPES as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button type="button" wire:click="close()"
                          class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="submit"
                          class="text-white bg-green-700 rounded-lg px-3 py-1 text-sm font-medium leading-5"
                          wire:loading.attr="disabled">
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
                <x-button type="button" wire:click="$set('deleteModal', false)"
                          class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="submit" class="text-white bg-red-700 rounded-lg px-3 py-1 text-sm font-medium leading-5"
                          wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </form>

    {{-- Detail Modal --}}
    <x-jet-dialog-modal wire:model.defer="detailModal">
        <x-slot name="title">
            {{ __('Expense Details') }}
        </x-slot>

        <x-slot name="content">
            <x-table>
                <x-slot name="head">
                    <x-table.row class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase dark:border-gray-400 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                        <x-table.column multi-column>{{ __('Due Date')  }}</x-table.column>
                        <x-table.column multi-column>{{ __('Supplier')  }}</x-table.column>
                        <x-table.column multi-column>{{ __('Category')  }}</x-table.column>
                        <x-table.column multi-column>{{ __('Amount')  }}</x-table.column>
                    </x-table.row>
                </x-slot>
                <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700">
                    <x-table.cell>
                        {{ \Carbon\Carbon::parse($detail->due_at)->format('d/m/Y') }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $supplier->name?? "" }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $category->name ?? "" }}
                    </x-table.cell>
                    <x-table.cell>
                        {{ $amount }}
                    </x-table.cell>
                </x-table.row>
            </x-table>
        </x-slot>

        <x-slot name="footer">
            <x-button type="button" wire:click="closeDetailModal()"
                      class="bg-gray-700 text-white rounded-lg px-3 py-1 text-sm font-medium leading-5">
                {{ __('Cancel') }}
            </x-button>
        </x-slot>
    </x-jet-dialog-modal>

</div>
