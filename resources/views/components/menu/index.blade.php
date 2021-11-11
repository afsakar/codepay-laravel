<aside {{ $attributes->merge(['class' => 'z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0']) }}>
    <div class="py-4 text-gray-500 dark:text-gray-400">
        {{ $header }}
        <x-menu.menu-list class="mt-6">
            <x-menu.menu-item :url="route('dashboard')" active="dashboard" title="Dashboard">
                <x-slot name="icon">
                    <x-heroicon-o-home class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>   
            <x-menu.menu-item :url="route('profile.show')" active="profile.show" title="Profile">
                <x-slot name="icon">
                    <x-heroicon-o-user class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
            <x-menu.menu-item :url="route('translations')" active="translations" title="Translations">
                <x-slot name="icon">
                    <x-heroicon-o-translate class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>   
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isPagesMenuOpen: false, togglePagesMenu() { this.isPagesMenuOpen = ! this.isPagesMenuOpen } }">
            <x-menu.menu-item active="pages" title="Pages" methodFrom="togglePagesMenu" methodTo="isPagesMenuOpen">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('login')" title="Login" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-collection  class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isAccountsMenuOpen: false, toggleAccountsMenu() { this.isAccountsMenuOpen = ! this.isAccountsMenuOpen } }">
            <x-menu.menu-item active="accounts" title="Accounts" methodFrom="toggleAccountsMenu" methodTo="isAccountsMenuOpen">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('accounts')" title="Accounts" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-library class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list class="px-6 my-6">
            <x-button class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                Create account
                <span class="ml-2" aria-hidden="true">+</span>
            </x-button>
        </x-menu.menu-list> 
      </div>
</aside>