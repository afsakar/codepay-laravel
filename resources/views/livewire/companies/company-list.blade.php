<div x-data="{ showFilters: false,  openFilters() { this.showFilters = ! this.showFilters } }" class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Company List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Company List')}}
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            @if(session()->get('company_id') == null)
                <x-button.link :url="route('company.select')" class="flex items-center justify-between text-sm font-medium leading-5 dark:text-gray-400 mr-4">
                    <x-heroicon-o-arrow-left class="h-5 w-5 mr-1" /> <span>{{ __('Go back') }}</span>
                </x-button.link>
            @endif
            @empty(!$selected)
                @permission('companies.delete')
                    <x-button wire:click="$set('deleteModal', true)" class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 text-red-600 bg-red-100 border border-transparent rounded-lg focus:outline-none">
                        <span class="flex items-center justify-between"><x-heroicon-o-trash class="h-5 w-5 mr-1" /> {{ __("Delete") }}</span>
                    </x-button>
                @endpermission
            @endempty
            @permission('companies.create')
            <x-button wire:click="create" class="flex items-center justify-between px-3 py-1 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none bg-gray-700 active:bg-gray-600 hover:bg-gray-800">
                <x-heroicon-o-plus class="h-5 w-5 mr-1" /> <span>{{ __('New') }}</span>
            </x-button>
            @endpermission
        </div>
    </h4>

    <div class="w-full">

        <x-card>
            {{-- Search Area --}}
            <div class="grid grid-cols-2 gap-4 py-4 dark:text-gray-400 dark:bg-gray-800">
                <x-input.text wire:model="filters.search" placeholder="{{ __('Search Companies...') }}"  />

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
                        @permission('companies.delete')
                        <x-table.column class="pr-0 w-8">
{{--                            @if($companies->count() != 0)--}}
{{--                            <x-input.checkbox wire:model="selectPage" />--}}
{{--                            @endif--}}
                        </x-table.column>
                        @endpermission
                        <x-table.column></x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['owner'] ?? null" wire:click="sortBy('owner')">{{ __('Owner') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['tax_number'] ?? null" wire:click="sortBy('tax_number')">{{ __('Tax / ID Number') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['status'] ?? null" wire:click="sortBy('status')">{{ __('Status') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        @permission('companies.update')
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
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $companies->count(), 'totalCount' => $companies->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $companies->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($companies as $company)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $company->id }}">
                        @permission('companies.delete')
                        <x-table.cell class="pr-0">
                            @if(session()->get('company_id') != $company->id)
                                <x-input.checkbox wire:model="selected" value="{{ $company->id }}" />
                            @endif
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            <img src="{{ $company->company_logo }}" alt="{{ $company->name }}" class="h-[3rem] w-auto">
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->owner }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $company->tax_number != "" ? $company->tax_number : $company->tc_number }}
                        </x-table.cell>
                        <x-table.cell>
                            <x-input.toggle wire:click="toggleSwitch({{$company->id}})" :active="$company->status == 'active'" :disabled="(session()->get('company_id') !== null && get_company_info()->id == $company->id)" />
                        </x-table.cell>
                        <x-table.cell title="{{ $company->created_at }}">
                            {{ $company->created_at->diffForHumans() }}
                        </x-table.cell>
                        @permission('companies.update')
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $company->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
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
            {{ $companies->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Company') }}
                @else
                    {{ __('Create Company') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <div class="md:grid md:grid-cols-2 md:space-x-4">
                    <div class="flex items-start">
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="{{ $editing['name'] }}" class="w-[10rem] py-2">
                        @else
                            <img src="{{ $editing['company_logo'] }}" alt="{{ $editing['name'] }}" class="w-[10rem] py-2">
                            @if($logo != "" || $editing['logo'])
                                <x-button wire:click.prevent="deleteImage({{ $editing['id'] }})" class="mt-2 bg-red-200 mt-2 p-1 rounded text-red-700 ml-auto">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </x-button>
                            @endif
                        @endif
                    </div>
                    <x-input.group inline for="logo" :label="__('Logo')" :error="$errors->first('editing.logo')">
                        <x-input.text type="file" wire:model.defer="logo" id="logo" class="pt-2" />
                    </x-input.group>
                </div>
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
                    <x-input.group inline for="tc" :label="__('Identification Number')" :error="$errors->first('editing.tc_number')">
                        <x-input.text wire:model.defer="editing.tc_number" id="tc" />
                    </x-input.group>
                    <x-input.group inline for="email" :label="__('Email Address')" :error="$errors->first('editing.email')">
                        <x-input.text wire:model.defer="editing.email" id="email" />
                    </x-input.group>
                </div>

                <x-input.group inline for="address" :label="__('Address')" :error="$errors->first('editing.address')">
                    <x-input.textarea wire:model.defer="editing.address" id="address" />
                </x-input.group>

                <x-input.group inline for="tel" :label="__('Phone Number')" :error="$errors->first('editing.tel_number')" :helpText="__('Please fill in without leading 0 (zero)')">
                    <x-input.text wire:model.defer="editing.tel_number" id="tel" />
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
                        @foreach (App\Models\Company::STATUS as $key => $value)
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
