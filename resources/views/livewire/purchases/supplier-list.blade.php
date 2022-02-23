<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Supplier List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Supplier List')}}
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
                @permission('suppliers.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('suppliers.create')
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
                            <x-input.group inline for="filter-status" :label="__('Status')">
                                <x-input.select id="filter-status" wire:model="filters.status">
                                    <option value="" disabled>{{ __('Select Status...') }}</option>
                                    @foreach (App\Models\Supplier::STATUS as $key => $value)
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
                <x-input.text wire:model="filters.search" placeholder="{{ __('Search Suppliers...') }}"  />

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
                        @permission('suppliers.delete')
                        <x-table.column class="pr-0 w-8">
                            @if($suppliers->count() != 0)
                                <x-input.checkbox wire:model="selectPage" />
                            @endif
                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['tax_number'] ?? null" wire:click="sortBy('tax_number')">{{ __('Tax Number') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['balance'] ?? null" wire:click="sortBy('balance')">{{ __('Balance') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        @permission('suppliers.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="7">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $suppliers->count(), 'totalCount' => $suppliers->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $suppliers->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($suppliers as $supplier)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $supplier->id }}">
                        @permission('suppliers.delete')
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $supplier->id }}" />
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            {{ $supplier->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $supplier->tax_number != "" ? $supplier->tax_number : "-" }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $supplier->expense[0]->sum_times_with_exchange_rate ?? number_format(0,2).' TL' }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge :color="$supplier->status_color">
                                {{ __(App\Models\Supplier::STATUS[$supplier->status]) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell title="{{ $supplier->created_at }}">
                            {{ $supplier->created_at->diffForHumans() }}
                        </x-table.cell>
                        @permission('suppliers.update')
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $supplier->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                            </div>
                        </x-table.cell>
                        @endpermission
                    </x-table.row>
                @empty
                    <x-table.cell colspan="7" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $suppliers->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Supplier') }}
                @else
                    {{ __('Create Supplier') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <div class="md:grid md:grid-cols-2 md:space-x-4">
                    <x-input.group inline for="name" :label="__('Name')" :error="$errors->first('editing.name')">
                        <x-input.text wire:model.defer="editing.name" id="name" />
                    </x-input.group>
                    <x-input.group inline for="owner" :label="__('Owner')" :error="$errors->first('editing.owner')">
                        <x-input.text wire:model.defer="editing.owner" id="owner" />
                    </x-input.group>
                </div>

                <div class="md:grid md:grid-cols-2 md:space-x-4">
                    <x-input.group inline for="tax-office" :label="__('Tax Office')" :error="$errors->first('editing.tax_office')">
                        <x-input.text wire:model.defer="editing.tax_office" id="tax-office" />
                    </x-input.group>
                    <x-input.group inline for="tax-number" :label="__('Tax Number')" :error="$errors->first('editing.tax_number')">
                        <x-input.text wire:model.defer="editing.tax_number" id="tax-number" />
                    </x-input.group>
                </div>

                <div class="md:grid md:grid-cols-2 md:space-x-4">
                    <x-input.group inline for="tel" :label="__('Phone Number')" :error="$errors->first('editing.tel_number')" :helpText="__('Please fill in without leading 0 (zero)')">
                        <x-input.text wire:model.defer="editing.tel_number" id="tel" />
                    </x-input.group>
                    <x-input.group inline for="email" :label="__('Email Address')" :error="$errors->first('editing.email')">
                        <x-input.text wire:model.defer="editing.email" id="email" />
                    </x-input.group>
                </div>

                <x-input.group inline for="address" :label="__('Address')" :error="$errors->first('editing.address')">
                    <x-input.textarea wire:model.defer="editing.address" id="address" />
                </x-input.group>

                <div class="md:grid md:grid-cols-2 md:space-x-4">
                    <x-input.group inline for="gsm" :label="__('GSM Number')" :error="$errors->first('editing.gsm_number')">
                        <x-input.text wire:model.defer="editing.gsm_number" id="gsm" />
                    </x-input.group>
                    <x-input.group inline for="fax" :label="__('Fax Number')" :error="$errors->first('editing.fax_number')">
                        <x-input.text wire:model.defer="editing.fax_number" id="fax" />
                    </x-input.group>
                </div>

                <x-input.group inline for="filter-status" :label="__('Status')" :error="$errors->first('editing.status')">
                    <x-input.select id="filter-status" wire:model.defer="editing.status">
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        @foreach (App\Models\Supplier::STATUS as $key => $value)
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
                {{ __('Are you sure you want to delete the selected records? This action is irreversible!') }}
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
