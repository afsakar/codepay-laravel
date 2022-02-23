<x-main-layout>
    <x-slot name="header">
            {{ __('Profile') }}
    </x-slot>

    <div>
        @if(session()->get('company_id') == null)
            <div class="block mt-6 flex justify-end">
                <x-button.link :url="route('company.select')" class="flex items-center justify-between text-sm font-medium leading-5 dark:text-gray-400 mr-4">
                    <x-heroicon-o-arrow-left class="h-5 w-5 mr-1" /> <span>{{ __('Go back') }}</span>
                </x-button.link>
            </div>
        @endif
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-10">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-10">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-10">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-jet-section-border />

                <div class="mt-10 sm:mt-10">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-main-layout>
