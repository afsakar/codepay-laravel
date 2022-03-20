<aside {{ $attributes->merge(['class' => 'z-20 hidden w-[18.5rem] overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0']) }}>
    <div class="py-4 text-gray-500 dark:text-gray-400">
        {{ $header }}
        <x-menu.menu-list class="mt-6">
            <x-menu.menu-item :url="route('dashboard')" active="dashboard" :title="__('Dashboard')" permission="dashboard">
                <x-slot name="icon">
                    <x-heroicon-o-home class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
            <x-menu.menu-list x-data="{ isSettingsMenuOpen: {{ toggle_menu('settings') }}, toggleSettingsMenu() { this.isSettingsMenuOpen = ! this.isSettingsMenuOpen } }">
                <x-menu.menu-item active="settings" :title="__('Settings')" methodFrom="toggleSettingsMenu" methodTo="isSettingsMenuOpen" permission="settings">
                    <x-slot name="submenus">
                        <x-menu.sub-menu-item :route="route('translations')" :title="__('Translations')" permission="translations" />
                        <x-menu.sub-menu-item :route="route('currencies')" :title="__('Currencies')" permission="currencies" />
                        <x-menu.sub-menu-item :route="route('categories')" :title="__('Categories')" permission="categories" />
                        <x-menu.sub-menu-item :route="route('taxes')" :title="__('Taxes')" permission="taxes" />
                        <x-menu.sub-menu-item :route="route('with_holdings')" :title="__('Withholding Taxes')" permission="with-holdings" />
                    </x-slot>
                    <x-slot name="icon">
                        <x-heroicon-o-cog class="h-5 w-5" />
                    </x-slot>
                </x-menu.menu-item>
            </x-menu.menu-list>
            <x-menu.menu-list x-data="{ isMaterialsMenuOpen: {{ toggle_menu('material-management') }}, toggleMaterialsMenu() { this.isMaterialsMenuOpen = ! this.isMaterialsMenuOpen } }">
                <x-menu.menu-item active="material-management" :title="__('Material Management')" methodFrom="toggleMaterialsMenu" methodTo="isMaterialsMenuOpen" permission="material-management">
                    <x-slot name="submenus">
                        <x-menu.sub-menu-item :route="route('units')" :title="__('Units')" permission="units" />
                        <x-menu.sub-menu-item :route="route('material_category')" :title="__('Material Categories')" permission="material-category" />
                        <x-menu.sub-menu-item :route="route('materials')" :title="__('Materials')" permission="materials" />
                    </x-slot>
                    <x-slot name="icon">
                        <x-heroicon-o-tag class="h-5 w-5" />
                    </x-slot>
                </x-menu.menu-item>
            </x-menu.menu-list>
            <x-menu.menu-list x-data="{ isRoleAndPermissionsMenuOpen: {{ toggle_menu('user-management') }}, toggleRoleAndPermissionsMenu() { this.isRoleAndPermissionsMenuOpen = ! this.isRoleAndPermissionsMenuOpen } }">
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
        <x-menu.menu-list x-data="{ isBanksMenuOpen: {{ toggle_menu('banks') }}, toggleBanksMenu() { this.isBanksMenuOpen = ! this.isBanksMenuOpen } }">
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
        <x-menu.menu-list>
            <x-menu.menu-item :url="route('companies')" active="companies" :title="__('Companies')" permission="companies">
                <x-slot name="icon">
                    <x-heroicon-o-library class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isSalesMenuOpen: {{ toggle_menu('sales') }}, toggleSalesMenu() { this.isSalesMenuOpen = ! this.isSalesMenuOpen } }">
            <x-menu.menu-item active="sales" title="Sales" methodFrom="toggleSalesMenu" methodTo="isSalesMenuOpen" permission="sales">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('customers')" :title="__('Customers')" permission="customers" />
                    <x-menu.sub-menu-item :route="route('revenues')" :title="__('Revenues')" permission="revenues" />
                    <x-menu.sub-menu-item :route="route('invoices')" :title="__('Invoices')" permission="invoices" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-shopping-cart class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list x-data="{ isPurchasesMenuOpen: {{ toggle_menu('purchases') }}, togglePurchasesMenu() { this.isPurchasesMenuOpen = ! this.isPurchasesMenuOpen } }">
            <x-menu.menu-item active="purchases" title="Purchases" methodFrom="togglePurchasesMenu" methodTo="isPurchasesMenuOpen" permission="purchases">
                <x-slot name="submenus">
                    <x-menu.sub-menu-item :route="route('suppliers')" :title="__('Suppliers')" permission="suppliers" />
                    <x-menu.sub-menu-item :route="route('expenses')" :title="__('Expenses')" permission="expenses" />
                </x-slot>
                <x-slot name="icon">
                    <x-heroicon-o-truck class="h-5 w-5" />
                </x-slot>
            </x-menu.menu-item>
        </x-menu.menu-list>
        <x-menu.menu-list class="px-6 my-6">
            <livewire:change-company />
        </x-menu.menu-list>
      </div>
</aside>
