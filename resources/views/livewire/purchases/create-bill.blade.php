<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto dark:text-gray-400">
    <x-slot name="header">
        {{__('Add/Edit Bill Items for Bill #:bill_number', ['bill_number' => $bill->bill_number])}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Add/Edit Bill Items')}}
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            <div class="relative mr-4 inline-block">
                <div class="text-gray-500 cursor-pointer w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-300 inline-flex items-center justify-center">
                    <x-heroicon-o-save class="w-7 h-7 text-gray-500" />
                </div>
            </div>
            <x-button.link :url="route('bills')" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-arrow-left class="h-5 w-5 mr-1" /> <span>{{ __('Go Back') }}</span>
            </x-button.link>
        </div>
    </h4>
    {{-- Filters --}}
    <div class="w-full">

        @foreach ($errors->all() as $key => $error)
            <li>{{ $error }}</li>
        @endforeach

        <div id="printTemplate">
            <x-card>
                <div class="grid grid-cols-3 gap-4 mt-8 px-4">
                    <div class="text-left space-y-6">
                        <ul>
                            <li class="font-bold">{{ $bill->corporation->name }}</li>
                            <li>{{ $bill->corporation->address }}</li>
                            <li>{{ phoneFormat($bill->corporation->tel_number == null ? $bill->corporation->gsm_number : $bill->corporation->tel_number) }}</li>
                            <li>{{ $bill->corporation->tax_office }} {{ $bill->corporation->tax_number ? ', '.$bill->corporation->tax_number : "" }}</li>
                            <li>{{ $bill->corporation->email }}</li>
                        </ul>

                        <ul>
                            <li class="font-bold">{{  $company->name }}</li>
                            <li>{{  $company->address }}</li>
                            <li>{{ phoneFormat( $company->tel_number) }}</li>
                            <li>{{  $company->tax_office }} {{  $company->tax_number ? ', '. $company->tax_number : "" }}</li>
                            <li>{{  $company->email }}</li>
                        </ul>
                    </div>
                    <div class="grid grid-cols-1 gap-4 text-center">
{{--                        <div class="flex items-start justify-center">--}}
{{--                            <img src="{{ asset('assets/gib-logo.png') }}" alt="" class="w-[8rem]">--}}
{{--                        </div>--}}
{{--                        <span class="block mt-6">{{ __('e-Arşiv Fatura') }}</span>--}}
                    </div>
                    <div class="text-right space-y-6">
                        <div class="flex items-start justify-end mb-12">
                            <img src="{{  $company->company_logo }}" alt="" class="w-48">
                        </div>
                        <table class="border w-full rounded-lg">
                            <tr class="border-b">
                                <td class="pr-2 border-r">
                                    {{ __('Bill Type') }}:
                                </td>
                                <td class="px-2">
                                    {{ __('Purchase') }}
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="pr-2 border-r">
                                    {{ __('Bill Number') }}:
                                </td>
                                <td class="px-2">
                                    {{ $bill->bill_number }}
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="pr-2 border-r">
                                    {{ __('Bill Date') }}:
                                </td>
                                <td class="px-2">
                                    {{ dateFormat($bill->issue_date) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 px-4">
                    <div class="block my-3 flex justify-end items-center print:hidden">
                        <x-button wire:click="addItem()" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                            <x-heroicon-o-plus class="h-5 w-5 mr-1" />  Add Item
                        </x-button>
                    </div>
                    <form wire:submit.prevent="save">
                    <x-table>
                        <x-slot name="head">
                            <x-table.row class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase dark:border-gray-400 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                                <x-table.column style="width: 400px;">{{ __('Material') }}</x-table.column>
                                <x-table.column>{{ __('Unit') }}</x-table.column>
                                <x-table.column>{{ __('Quantity') }}</x-table.column>
                                <x-table.column>{{ __('Price') }}</x-table.column>
                                <x-table.column>{{ __('Tax') }} (%)</x-table.column>
                                <x-table.column>{{ __('Total') }}</x-table.column>
                            </x-table.row>
                        </x-slot>
                        @forelse($items as $key => $item)
                            <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $key }}">
                                <x-table.cell style="width: 400px;">
                                    <x-input.group inline for="items.{{ $key }}.material_id" label="" :error="$errors->first('items.{{ $key }}.material_id')">
                                        <x-input.select style="background-image: none!important;"
                                            wire:change="changeMaterial($event.target.value, {{ $key }})"
                                            wire:model.defer="items.{{ $key }}.material_id"
                                            wire:key="items.{{ $key }}.material_id"
                                            id="items.{{ $key }}.material_id"
                                            class="w-full border-0 appearance-none bg-gray-100"
                                        >
                                            <option value="">{{ __('Select Material...') }}</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                            @endforeach
                                        </x-input.select>
                                    </x-input.group>
                                </x-table.cell>
                                <x-table.cell>
                                    <x-input.text
                                        class="w-full border-0 bg-gray-100"
                                        readonly
                                        wire:model.defer="unit_name.{{ $key }}"
                                        wire:key="unit_name.{{ $key }}"
                                        id="unit_name.{{ $key }}"
                                    />
                                </x-table.cell>
                                <x-table.cell>
                                    <x-input.group inline for="items.{{ $key }}.quantity" label="" :error="$errors->first('items.{{ $key }}.quantity')">
                                        <x-input.text
                                            class="w-full border-0 bg-gray-100"
                                            wire:change="changeElements({{ $key }})"
                                            wire:model.defer="items.{{ $key }}.quantity"
                                            wire:key="items.{{ $key }}.quantity"
                                            id="items.{{ $key }}.quantity"
                                        />
                                    </x-input.group>
                                </x-table.cell>
                                <x-table.cell>
                                    <x-input.group inline for="items.{{ $key }}.price" label="" :error="$errors->first('items.{{ $key }}.price')">
                                        <x-input.text
                                            class="w-full border-0 bg-gray-100"
                                            wire:change="changeElements({{ $key }})"
                                            wire:model.defer="items.{{ $key }}.price"
                                            wire:key="items.{{ $key }}.price"
                                            id="items.{{ $key }}.price"
                                        />
                                    </x-input.group>
                                </x-table.cell>
                                <x-table.cell>
                                    <x-input.text
                                        class="w-full border-0 bg-gray-100"
                                        readonly
                                        wire:model.defer="tax.{{ $key }}"
                                        wire:key="tax.{{ $key }}"
                                        id="tax.{{ $key }}"
                                    />
                                </x-table.cell>
                                <x-table.cell>
                                    <div class="relative">
                                        <x-input.text
                                            class="w-full border-0 bg-gray-100"
                                            readonly
                                            wire:model.defer="total.{{ $key }}"
                                            wire:key="total.{{ $key }}"
                                            id="total.{{ $key }}"
                                        />
                                        <div class="absolute right-0 top-[-1rem]">
                                            <div class="flex items-center justify-end space-x-4 text-sm">
                                                <x-button wire:click.prevent="removeItem({{ $key }})" aria-label="Delete Item" class="shadow-md bg-red-100 dark:text-gray-400 flex focus:outline-none focus:shadow-outline-gray font-medium items-center justify-between leading-5 px-1 py-1 rounded-full text-gray-600 text-sm">
                                                    <x-heroicon-o-trash class="h-5 w-5 text-red-400" />
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                </x-table.cell>
                            </x-table.row>
                        @empty
                            <x-table.cell colspan="7" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-center text-gray-400">
                                    <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                                </div>
                            </x-table.cell>
                        @endforelse
                        <x-table.row>
                            <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ __('Total') }}
                                </div>
                            </x-table.cell>
                            <x-table.cell colspan="2" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ number_format($totalPrice, 2) }} ₺
                                </div>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row>
                            <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ __('Total Discount') }}
                                </div>
                            </x-table.cell>
                            <x-table.cell colspan="2" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    <x-input.text
                                        class="w-full border-0 bg-gray-100 text-right"
                                        wire:model.lazy="discount"
                                        wire:key="discount"
                                        id="discount"
                                    />
                                </div>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row>
                            <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ __('Total Tax') }}
                                </div>
                            </x-table.cell>
                            <x-table.cell colspan="2" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ number_format($taxTotal, 2) }} ₺
                                </div>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row>
                            <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ __('Withholding') }} ({{ $withholding->rate }}%)
                                </div>
                            </x-table.cell>
                            <x-table.cell colspan="2" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ number_format(($withholding->rate * $taxTotal / 100), 2) }} ₺
                                </div>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row>
                            <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ __('Bill Total') }}
                                </div>
                            </x-table.cell>
                            <x-table.cell colspan="2" class="dark:text-gray-400 dark:bg-gray-700">
                                <div class="flex items-center justify-end text-gray-400">
                                    {{ number_format($grandTotal, 2) }} ₺
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    </x-table>
                        <div class="flex justify-end items-center mt-10 print:hidden">
                            <x-button type="submit" class="text-white bg-green-700 rounded-lg px-10 py-2 text-sm font-medium leading-5" wire:loading.attr="disabled">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
                <div class="px-4 my-6">
                    @if($bill->notes != "")
                        <div class="text-lg">
                            {{__('Notes')}}:
                        </div>
                        <div class="text-sm">
                            {!! $bill->notes !!}
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

</div>
