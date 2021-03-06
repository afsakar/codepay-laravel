<x-slot name="header">
    {{ __('Select Company') }}
</x-slot>

<div class="flex items-center">
    <div class="m-auto translate-y-1/2">
        <x-card class="shadow-xl md:min-w-[400px]">
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <img src="{{ $logo != null ? asset('storage/'.$logo) : defaultImage() }}" class="h-20 w-auto mb-4">
                </div>

                <form wire:submit.prevent="contiune">
                    <ul>
                        @foreach($companies as $company)
                            <li class="relative my-2">
                                <input wire:model="selectedCompany" class="sr-only peer" type="radio"
                                       value="{{ $company->id }}" name="answer" id="company-{{ $company->id }}">
                                <label
                                    class="text-center flex col-auto py-3 px-5 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:bg-gray-400 peer-checked:ring-transparent peer-checked:ring-2 peer-checked:border-transparent peer-checked:text-white peer-checked:font-medium dark:bg-gray-700 dark:border-0 dark:text-gray-400 dark:hover:bg-gray-800"
                                    for="company-{{ $company->id }}">
                                    {{ $company->name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>

                    <x-button :disabled="$isDisabled" type="submit"
                              class="disabled:opacity-50 mt-5 bg-gray-700 text-white rounded-lg py-2 px-10 text-sm font-medium leading-5 w-full">
                        {{ __('Continue') }}
                        <x-heroicon-s-arrow-right class="inline-block w-4 h-4 ml-2"/>
                    </x-button>

                    <div class="flex items-center justify-center">
                        <x-button.link :url="route('companies')" class="mt-4 dark:text-gray-400">
                            {{ __('Add new company') }}
                        </x-button.link>
                    </div>

                </form>
            </div>
        </x-card>
    </div>
</div>
