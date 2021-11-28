{{--
TODO: Add permission check on menu-item and sub-menu-item
TODO: Add Currency Module
TODO: Add Sales and Purchase Module
--}}
<aside {{ $attributes->merge(['class' => 'z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0']) }}>
    <div class="py-4 text-gray-500 dark:text-gray-400">
        {{ $header }}
        <x-menu.menu-list class="mt-6">
            <x-menu.menu-item :url="route('dashboard')" active="dashboard" :title="__('Dashboard')" permission="dashboard">
                <x-slot name="icon">
                    <x-heroicon-o-home class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
            <x-menu.menu-list x-data="{ isSettingsMenuOpen: false, toggleSettingsMenu() { this.isSettingsMenuOpen = ! this.isSettingsMenuOpen } }">
                <x-menu.menu-item active="settings" :title="__('Settings')" methodFrom="toggleSettingsMenu" methodTo="isSettingsMenuOpen" permission="settings">
                    <x-slot name="submenus">
                        <x-menu.sub-menu-item :route="route('translations')" :title="__('Translations')" permission="translations" />
                        <x-menu.sub-menu-item :route="route('currencies')" :title="__('Currencies')" permission="currencies" />
                    </x-slot>
                    <x-slot name="icon">
                        <x-heroicon-o-cog class="h-5 w-5" />
                    </x-slot>
                </x-menu.menu-item>
            </x-menu.menu-list>
            <x-menu.menu-list x-data="{ isRoleAndPermissionsMenuOpen: false, toggleRoleAndPermissionsMenu() { this.isRoleAndPermissionsMenuOpen = ! this.isRoleAndPermissionsMenuOpen } }">
                <x-menu.menu-item active="user-management" :title="__('User Management')" methodFrom="toggleRoleAndPermissionsMenu" methodTo="isRoleAndPermissionsMenuOpen" permission="user-management">
                    <x-slot name="submenus">
                        <x-menu.sub-menu-item :route="route('users')" :title="__('Users')" permission="users" />
                        <x-menu.sub-menu-item :route="route('roles')" :title="__('Roles and Permissions')" permission="roles" />
                    </x-slot>
                    <x-slot name="icon">
                        <x-heroicon-o-user-group class="h-5 w-5" />
                    </x-slot>
                </x-menu.menu-item>
            </x-menu.menu-list>
        </x-menu.menu-list>
        <x-menu.menu-list>
            <x-menu.menu-item :url="route('companies')" active="companies" :title="__('Companies')" permission="companies">
                <x-slot name="icon">
                    <x-heroicon-o-library class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isSalesMenuOpen: false, toggleSalesMenu() { this.isSalesMenuOpen = ! this.isSalesMenuOpen } }">
            <x-menu.menu-item active="sales" title="Sales" methodFrom="toggleSalesMenu" methodTo="isSalesMenuOpen" permission="sales">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('customers')" :title="__('Customers')" permission="customers" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-shopping-cart class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isPurchasesMenuOpen: false, togglePurchasesMenu() { this.isPurchasesMenuOpen = ! this.isPurchasesMenuOpen } }">
            <x-menu.menu-item active="purchases" title="Purchases" methodFrom="togglePurchasesMenu" methodTo="isPurchasesMenuOpen" permission="purchases">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('suppliers')" :title="__('Suppliers')" permission="suppliers" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-truck class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isBanksMenuOpen: false, toggleBanksMenu() { this.isBanksMenuOpen = ! this.isBanksMenuOpen } }">
            <x-menu.menu-item active="banks" :title="__('Banks')" methodFrom="toggleBanksMenu" methodTo="isBanksMenuOpen" permission="banks">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('accounts')" :title="__('Accounts')" permission="accounts" />
                    <x-menu.sub-menu-item :route="route('accounts.types')" :title="__('Account Types')" permission="account_types" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-cash class="h-5 w-5" />
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
