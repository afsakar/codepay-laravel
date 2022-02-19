<div class="w-full overflow-x-auto">
    <x-slot name="header">
        {{__('Role and Permission List')}}
    </x-slot>

    {{-- Header --}}
    <h4 class="flex items-center justify-between my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200" >
        <div class="flex items-center justify-between">
            {{__('Role and Permission List')}}
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center justify-between">
            @empty(!$selected)
                @permission('roles.delete')
                <x-dropdown :label="__('Bulk Actions')">
                    <x-dropdown.item  type="button" wire:click="$set('deleteModal', true)" class="flex items-center space-x-2">
                        <span>{{ __('Delete') }}</span>
                    </x-dropdown.item>
                </x-dropdown>
                @endpermission
            @endempty
            @permission('roles.create')
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
                <x-input.text wire:model="filters.search" placeholder="Search Roles..."  />

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
                        @permission('roles.edit')
                        <x-table.column class="pr-0 w-8">

                        </x-table.column>
                        @endpermission
                        <x-table.column multi-column sortable :direction="$sorts['name'] ?? null" wire:click="sortBy('name')">{{ __('Name') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['description'] ?? null" wire:click="sortBy('description')">{{ __('Description') }}</x-table.column>
                        <x-table.column multi-column sortable :direction="$sorts['created_at'] ?? null" wire:click="sortBy('created_at')">{{ __('Created At') }}</x-table.column>
                        @permission('roles.edit')
                        <x-table.column>{{ __('Actions') }}</x-table.column>
                        @endpermission
                    </x-table.row>
                </x-slot>
                @if($selectPage)
                    <x-table.row class="text-gray-600 dark:text-gray-400 dark:bg-gray-700 text-center text-sm bg-cool-gray-100">
                        <x-table.cell colspan="5">
                            @unless ($selectAll)
                                <div>
                                <span>
                                    {!! __('You have selected <strong>:selectedCount</strong> items. Do you want to select all <strong>:totalCount</strong> items?', ['selectedCount' => $roles->count(), 'totalCount' => $roles->total()]) !!}
                                </span>
                                    <button wire:click="selectAll" class="text-blue-600 ml-1">{{ __('Select All') }}</button>
                                </div>
                            @else
                                <span>
                            {!! __('You are currently selecting all <strong>:totalCount</strong> items.', ['totalCount' => $roles->total()]) !!}
                        </span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endif
                @forelse ($roles as $role)
                    <x-table.row wire:loading.class="opacity-80" class="text-gray-600 dark:text-gray-400 dark:bg-gray-700" wire:key="row-{{ $role->id }}">
                        @permission('roles.delete')
                        <x-table.cell class="pr-0">
                            @if(!in_array($role->id, [1,2,3]))
                            <x-input.checkbox wire:model="selected" value="{{ $role->id }}" />
                            @endif
                        </x-table.cell>
                        @endpermission
                        <x-table.cell>
                            {{ $role->name }}
                        </x-table.cell>
                        <x-table.cell>
                            {{ $role->description }}
                        </x-table.cell>
                        <x-table.cell title="{{ $role->created_at }}">
                            {{ $role->created_at->diffForHumans() }}
                        </x-table.cell>
                        @permission('roles.edit')
                        <x-table.cell>
                            <div class="flex items-center space-x-4 text-sm">
                                <x-button wire:click="edit({{ $role->id }})" aria-label="Edit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-pencil class="h-5 w-5" />
                                </x-button>
                            </div>
                        </x-table.cell>
                        @endpermission
                    </x-table.row>
                @empty
                    <x-table.cell colspan="5" class="dark:text-gray-400 dark:bg-gray-700">
                        <div class="flex items-center justify-center text-gray-400">
                            <x-heroicon-o-search class="h-5 w-5 mr-2" /> <span class="text-medium py-6 text-lg">{{ __('No records found matching your search term or no records have been added yet!') }}</span>
                        </div>
                    </x-table.cell>
                @endforelse
            </x-table>
            {{ $roles->links() }}
        </x-card>
    </div>

    {{-- Create/Edit Modal --}}
    <form wire:submit.prevent="save">
        <x-jet-dialog-modal wire:model.defer="editingModal">
            <x-slot name="title">
                @if(!$createMode)
                    {{ __('Editing Role') }} <span class="text-sm text-gray-400">({{ $editing->name }})</span>
                @else
                    {{ __('Create Role') }}
                @endif
            </x-slot>

            <x-slot name="content">
                <x-tab :tabs="[__('General'), __('Permissions')]">
                    <x-tab.item key="1">
                        <x-input.group inline for="name" :label="__('Role Name')" :error="$errors->first('editing.name')">
                            <x-input.text wire:model.defer="editing.name" id="name" />
                        </x-input.group>
                        <x-input.group inline for="description" :label="__('Description')" :error="$errors->first('editing.description')">
                            <x-input.textarea wire:model.defer="editing.description" id="description" />
                        </x-input.group>
                    </x-tab.item>
                    <x-tab.item key="2">
                        @if($editing->id != 1)
                            @foreach (config('permissions') as $menu)
                                @if(isset($menu['submenus']))
                                    <div class="flex justify-center mt-2">
                                        <div class="w-full">
                                            <label>
                                                <h4 class="text-md font-medium text-gray-600 dark:text-gray-400">{{ $menu['title'] }}</h4>
                                                @if($menu['description'] || $menu['description'] != "")
                                                    <div>
                                                        <em class="text-sm text-gray-400">({{ $menu['description'] }})</em>
                                                    </div>
                                                @endif
                                            </label>
                                            @foreach ($menu['permissions'] as $key => $permission)
                                                @php $menu_gate = $menu['gate']; @endphp
                                                <div class="flex items-center">
                                                    <label for="{{ $menu['title'].$loop->iteration }}" class="ml-2">
                                                        {{ $permission }}
                                                    </label>
                                                    <x-input.checkbox wire:model="permissions.{{ $menu_gate }}.{{ $key }}" wire:key="permissions.{{ $key }}" value="true" class="ml-3" />
                                                </div>
                                            @endforeach
                                            @foreach ($menu['submenus'] as $submenu)
                                            <div class="bg-gray-100 dark:bg-gray-800 rounded-md p-2 mt-2">
                                                <label>
                                                    <h4 class="text-md font-medium text-gray-600 dark:text-gray-400">{{ $submenu['title'] }}</h4>
                                                    @if($submenu['description'] || $submenu['description'] != "")
                                                        <div>
                                                            <em class="text-sm text-gray-400">({{ $submenu['description'] }})</em>
                                                        </div>
                                                    @endif
                                                </label>
                                                @foreach ($submenu['permissions'] as $key => $permission)
                                                    @php $gate = $submenu['gate']; @endphp
                                                    <div class="flex items-center inline-flex">
                                                        <label for="{{ $submenu['title'].$loop->iteration }}" class="ml-2">
                                                            {{ $permission }}
                                                        </label>
                                                        <x-input.checkbox wire:model="permissions.{{ $gate }}.{{ $key }}" wire:key="permissions.{{$key}}" value="true" class="ml-3" />
                                                    </div>
                                                @endforeach
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="flex justify-center mt-2">
                                        <div class="w-full">
                                            <label>
                                                <h4 class="text-md font-medium text-gray-600 dark:text-gray-400">{{ $menu['title'] }}</h4>
                                                @if($menu['description'] || $menu['description'] != "")
                                                    <div>
                                                        <em class="text-sm text-gray-400">({{ $menu['description'] }})</em>
                                                    </div>
                                                @endif
                                            </label>
                                            @foreach ($menu['permissions'] as $key => $permission)
                                                @php $menu_gate = $menu['gate']; @endphp
                                                <div class="flex items-center inline-flex">
                                                    <label for="{{ $menu['title'].$loop->iteration }}" class="ml-2">
                                                        {{ $permission }}
                                                    </label>
                                                    <x-input.checkbox wire:model="permissions.{{ $menu_gate }}.{{ $key }}" wire:key="permissions.{{$key}}" value="true" class="ml-3" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="flex items-center align-center">
                                <h4>{{ __('Super Admin have all permissions!') }}</h4>
                            </div>
                        @endif
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
