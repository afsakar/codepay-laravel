<div class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('User  List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('User List')}}
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            @empty(!$selected)
                @permission('users.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('users.create')
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
                <x-input.text wire:model="filters.search" placeholder="Search Users..."  />

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
                        @permission('users.edit')
                        <x-table.column class="pr-0 w-8">

                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Full Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['email'] ?? null" wire:click="sortBy('email')">{{ __('Email Address') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        <x-table.column>
                            @permission('users.edit')
                                {{ __('Actions') }}
                            @endpermission
                        </x-table.column>
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="5">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $users->count(), 'totalCount' => $users->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $users->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($users as $user)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $user->id }}">
                        @permission('users.delete')
                        <x-table.cell class="pr-0">
                            @if($user->id != auth()->user()->id)
                                <x-input.checkbox wire:model="selected" value="{{ $user->id }}" />
                            @endif
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            <div class="flex items-center text-sm">
                                <!-- Avatar with inset shadow -->
                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                    <img class="object-cover w-full h-full rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" loading="lazy">
                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $user->role->name }}
                                    </p>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->email }}
                        </x-table.cell>
                        <x-table.cell title="{{ $user->created_at }}">
                            {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                        </x-table.cell>
                        <x-table.cell>
                            @permission('users.edit')
                            @if($user->role->id != 1)
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $user->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                            </div>
                            @endif
                            @endpermission
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $users->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing User') }} <span class="text-sm text-gray-400">({{ $editing->name }})</span>
                @else
                    {{ __('Create User') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-tab :tabs="[__('General'), __('Password')]">
                    <x-tab.item key="1">
                        <x-input.group inline for="name" :label="__('Full Name')" :error="$errors->first('editing.name')">
                            <x-input.text wire:model.defer="editing.name" id="name" />
                        </x-input.group>
                        <x-input.group inline for="email" :label="__('Email Address')" :error="$errors->first('editing.email')">
                            <x-input.text type="email" wire:model.defer="editing.email" id="email" />
                        </x-input.group>
                        <x-input.group inline for="role" :label="__('Role')" :error="$errors->first('editing.role_id')">
                            <x-input.select id="role" wire:model.defer="editing.role_id">
                                @foreach (App\Models\Role::all() as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>
                    </x-tab.item>
                    <x-tab.item key="2">
                        <x-input.group inline for="king" :label="__('Password')" :error="$errors->first('editing.king')">
                            <x-input.text type="password" wire:model.defer="editing.king" id="king" />
                        </x-input.group>
                        <x-input.group inline for="king_confirmation" :label="__('Confirm Password')" :error="$errors->first('editing.king_confirmation')">
                            <x-input.text type="password" wire:model.defer="editing.king_confirmation" id="king_confirmation" />
                        </x-input.group>
                    </x-tab.item>
                </x-tab>
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
