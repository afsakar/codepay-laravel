<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Invoice List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Invoice List')}}
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
                @permission('invoices.delete')
                <x-button wire:click="$set('deleteModal', true)" class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 text-red-600 bg-red-100 border border-transparent rounded-lg focus:outline-none">
                    <span class="flex items-center justify-between"><x-heroicon-o-trash class="h-5 w-5 mr-1" /> {{ __("Delete") }}</span>
                </x-button>
                @endpermission
            @endempty
            @permission('invoices.create')
            <x-button wire:click="create" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-plus class="h-5 w-5 mr-1" /> <span>{{ __('New') }}</span>
            </x-button>
            @endpermission
        </div>
    </h4>

    <div class="w-full">
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
                                    <option value="" disabled>{{ __('Select Status...') }}</option>
                                    @foreach (App\Models\Invoice::STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ __($value) }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>

                        <div class="md:w-1/2 pl-2 space-y-4">
                            <x-button.link wire:click="resetFilters" class="md:absolute right-0 bottom-0 p-4 dark:text-gray-400">{{ __('Reset Filters') }}</x-button.link>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <x-card>
            {{-- Search Area --}}
            <div class="grid grid-cols-2 gap-4 py-4 dark:text-gray-400 dark:bg-gray-800">
                <x-input.text wire:model="filters.search" placeholder="Search Invoice..."  />

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
                        @permission('invoices.delete')
                        <x-table.column class="pr-0 w-8">
                            <x-input.checkbox wire:model="selectPage" />
                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['invoice_number'] ?? null" wire:click="sortBy('invoice_number')">{{ __('Invoice Number') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['corporation_id'] ?? null" wire:click="sortBy('corporation_id')">{{ __('Customer') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['issue_date'] ?? null" wire:click="sortBy('issue_date')">{{ __('Issue Date') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['withholding_id'] ?? null" wire:click="sortBy('withholding_id')">{{ __('Withholding') }}</x-table.column>
                        <x-table.column>{{ __('Amount') }}</x-table.column>
                        <x-table.column>{{ __('Total Tax') }}</x-table.column>
                        <x-table.column>{{ __('Total Withholding') }}</x-table.column>
                        <x-table.column>{{ __('Discount') }}</x-table.column>
                        <x-table.column>{{ __('Total') }}</x-table.column>
                        @permission('invoices.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="11">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $invoices->count(), 'totalCount' => $invoices->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $invoices->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($invoices as $invoice)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $invoice->id }}">
                        @permission('invoices.delete')
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $invoice->id }}" />
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            {{ $invoice->invoice_number }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $invoice->corporation->name }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge :color="$invoice->status_color">
                                {{ __(App\Models\Invoice::STATUS[$invoice->status]) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell>
                            {{ dateFormat($invoice->issue_date) }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $invoice->withholding->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ number_format($invoice->totalAmountWithOutTax, 2) }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            {{ number_format($invoice->totalTax, 2) }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            {{ number_format($invoice->totalWithholding, 2) }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            {{ number_format($invoice->discount, 2) }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            {{ number_format($invoice->totalAmount, 2) }} ₺
                        </x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                @permission('invoices.update')
                                    <x-button wire:click="edit({{ $invoice->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                        <x-heroicon-o-pencil class="h-5 w-5" />
                                    </x-button>
                                @endpermission
                                @permission('invoices.create')
                                    <x-button.link :url="route('create.invoice', $invoice->id)" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                        <x-heroicon-o-collection class="h-5 w-5" />
                                    </x-button.link>
                                @endpermission
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.cell colspan="11" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $invoices->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal" maxWidth="2xl">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Invoice') }}
                @else
                    {{ __('Create Invoice') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-input.group inline for="invoice_number" :label="__('Invoice Number')" :error="$errors->first('editing.invoice_number')">
                    <x-input.text wire:model.defer="editing.invoice_number" id="invoice_number" />
                </x-input.group>
                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="corporation_id" :label="__('Customer')" :error="$errors->first('editing.corporation_id')">
                        <x-input.select id="corporation_id" wire:model.defer="editing.corporation_id">
                            <option value="" disabled>{{ __('Select Customer...') }}</option>
                            @foreach ($corporations as $corporation)
                                <option value="{{ $corporation->id }}">{{ __($corporation->name) }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                    <x-input.group inline for="withholding_id" :label="__('Withholding Tax Rate')" :error="$errors->first('editing.withholding_id')">
                        <x-input.select id="withholding_id" wire:model.defer="editing.withholding_id">
                            <option value="0">{{ __('Select Withholding') }}</option>
                            @foreach ($withholdings as $withholding)
                                <option value="{{ $withholding->id }}">{{ __($withholding->name) }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                </div>
                    <x-input.group inline for="issue_date" :label="__('Issue Date')" :error="$errors->first('editing.issue_date')">
                        <x-input.date wire:model.lazy="editing.issue_date" id="issue_date" placeholder="MM/DD/YYYY" />
                    </x-input.group>
                <x-input.group inline for="status" :label="__('Status')">
                    <x-input.select id="status" wire:model.defer="editing.status">
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        @foreach (App\Models\Invoice::STATUS as $key => $value)
                            <option value="{{ $key }}">{{ __($value) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                <x-input.group inline for="notes" :label="__('Notes')" :error="$errors->first('editing.notes')">
                    <x-input.textarea wire:model.defer="editing.notes" id="notes" />
                </x-input.group>
                <x-input.group inline for="discount" :label="__('Discount')" :error="$errors->first('editing.discount')">
                    <x-input.money wire:model.defer="editing.discount" id="discount" />
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
