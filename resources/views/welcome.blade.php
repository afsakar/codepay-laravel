<x-main-layout>
    <x-slot name="header">
        {{ __('TALL STACK DASHBOARD') }}
    </x-slot>

    <h4 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('TALL STACK DASHBOARD') }}
    </h4>
    <div class="w-full rounded-lg">

        <x-card>
            <x-slot name="header">
                Tab Content
            </x-slot>

            <x-tab :tabs="['Genel', 'Parola', 'Sosyal Medya']">
                <x-tab.item key="1" class="text-gray-400">
                    Genel Ayarlar
                </x-tab.item>
                <x-tab.item key="2" class="text-gray-400">
                    Parola Ayarları
                </x-tab.item>
                <x-tab.item key="3" class="text-gray-400">
                    Sosyal Medya Hesapları
                </x-tab.item>
            </x-tab>

            <x-alert dismissible color="red" id="alert">
                <x-slot name="icon">
                    <x-heroicon-s-check class="w-6 h-6" />
                </x-slot>
                A simple info alert with an <a href="#" class="font-semibold underline">example link</a>. Give it a click if you like.
            </x-alert>

            @php
            /* $doviz = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');

            $usd_alis = $doviz ->Currency[0]->BanknoteBuying;
            $usd_satis = $doviz ->Currency[0]->BanknoteSelling;

            $euro_alis = $doviz ->Currency[3]->BanknoteBuying;
            $euro_satis = $doviz ->Currency[3]->BanknoteSelling;

            echo 'USD Alış: '.$usd_alis.'<br>USD Satış: '.$usd_satis.'<br>';
            echo 'EUR Alış: '.$euro_alis.'<br>EUR Satış: '.$euro_satis; */
            @endphp

        </x-card>

    </div>
</x-main-layout>
