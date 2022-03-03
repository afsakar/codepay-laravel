<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Material List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Material List')}}
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
                @permission('materials.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('materials.create')
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
                                    @foreach (App\Models\Material::STATUS as $key => $value)
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
                <x-input.text wire:model="filters.search" placeholder="Search Material..."  />

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
                        @permission('materials.delete')
                        <x-table.column class="pr-0 w-8">
                            <x-input.checkbox wire:model="selectPage" />
                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['sku'] ?? null" wire:click="sortBy('sku')">{{ __('SKU Code') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['unit_id'] ?? null" wire:click="sortBy('unit_id')">{{ __('Unit') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['material_category_id'] ?? null" wire:click="sortBy('material_category_id')">{{ __('Material Category') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['sale_price'] ?? null" wire:click="sortBy('sale_price')">{{ __('Sale Price') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['purchase_price'] ?? null" wire:click="sortBy('purchase_price')">{{ __('Purchase Price') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        @if(auth()->user()->role->id == 1)
                        <x-table.column multi-column sortable :direction="$sorts['created_by'] ?? null" wire:click="sortBy('created_by')">{{ __('Created By') }}</x-table.column>
                        @endif
                        @permission('materials.update')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="10">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $materials->count(), 'totalCount' => $materials->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $materials->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($materials as $material)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $material->id }}">
                        @permission('materials.delete')
                        <x-table.cell class="pr-0">
                            <x-input.checkbox wire:model="selected" value="{{ $material->id }}" />
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            {{ $material->sku }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $material->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $material->unit->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $material->category->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $material->sale_price_with_currency }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $material->purchase_price_with_currency }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-input.toggle wire:click="toggleSwitch({{$material->id}})" :active="$material->status == 'active'" />
                        </x-table.cell>
                        @if(auth()->user()->role->id == 1)
                        <x-table.cell>
                            @if($material->created_by != 0)
                                <div class="flex items-center text-sm">
                                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full" src="{{ $material->created_user[0]->profile_photo_url }}" alt="{{ $material->created_user[0]->name }}" loading="lazy">
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    </div>
                                    <div>
                                        <p>
                                            {{ $material->created_user[0]->name }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </x-table.cell>
                        @endif
                        @permission('materials.update')
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $material->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                            </div>
                        </x-table.cell>
                        @endpermission
                    </x-table.row>
                @empty
                    <x-table.cell colspan="10" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $materials->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Material') }}
                @else
                    {{ __('Create Material') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="name" :label="__('Name')" :error="$errors->first('editing.name')">
                        <x-input.text wire:model.defer="editing.name" id="name" />
                    </x-input.group>

                    <x-input.group inline for="sku" :label="__('SKU Code')" :error="$errors->first('editing.sku')">
                        <x-input.text wire:model.defer="editing.sku" id="sku" />
                    </x-input.group>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="sale_price" :label="__('Sale Price')" :error="$errors->first('editing.sale_price')">
                        <x-input.money wire:model.defer="editing.sale_price" id="sale_price" :currency="$symbol ?? '₺'" :position="$position" />
                    </x-input.group>

                    <x-input.group inline for="purchase_price" :label="__('Purchase Price')" :error="$errors->first('editing.purchase_price')">
                        <x-input.money wire:model.defer="editing.purchase_price" id="purchase_price" :currency="$symbol ?? '₺'" :position="$position" />
                    </x-input.group>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="currency_id" :label="__('Currency')">
                        <x-input.select id="currency_id" wire:model.defer="editing.currency_id" wire:change="changeCurrency($event.target.value)">
                            <option value="" disabled>{{ __('Select Currency...') }}</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ __($currency->name)." [".$currency->symbol."]" }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>

                    <x-input.group inline for="tax" :label="__('Tax Percent')">
                        <x-input.select id="tax" wire:model.defer="editing.tax_id">
                            <option value="" disabled>{{ __('Select Tax...') }}</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id }}">{{ __($tax->name) }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="units" :label="__('Unit')">
                        <x-input.select id="units" wire:model.defer="editing.unit_id">
                            <option value="" disabled>{{ __('Select Unit...') }}</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ __($unit->name) }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>

                    <x-input.group inline for="quantity" :label="__('Quantity')" :error="$errors->first('editing.quantity')">
                        <x-input.text wire:model.defer="editing.quantity" id="quantity" />
                    </x-input.group>
                </div>

                <x-input.group inline for="material_category_id" :label="__('Category')">
                    <x-input.select id="material_category_id" wire:model.defer="editing.material_category_id">
                        <option value="" disabled>{{ __('Select Category...') }}</option>
                        @foreach ($material_categories as $category)
                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <div class="grid grid-cols-2 gap-4">
                    <x-input.group inline for="type" :label="__('Type')" :error="$errors->first('editing.type')">
                        <x-input.text wire:model.defer="editing.type" id="type" />
                    </x-input.group>

                    <x-input.group inline for="filter-status" :label="__('Status')">
                        <x-input.select id="filter-status" wire:model.defer="editing.status">
                            <option value="" disabled>{{ __('Select Status...') }}</option>
                            @foreach (App\Models\Material::STATUS as $key => $value)
                                <option value="{{ $key }}">{{ __($value) }}</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>
                </div>

                <x-input.group inline for="description" :label="__('Description')" :error="$errors->first('editing.description')">
                    <x-input.textarea wire:model.defer="editing.description" id="description" />
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
