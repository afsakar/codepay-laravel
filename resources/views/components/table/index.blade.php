<div class="w-full overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'table w-full whitespace-normal table-auto']) }}>
            <thead>
                {{ $head }}
            </thead>
            <tbody class="divide-y border-t dark:divide-gray-600 dark:bg-gray-700">

                {{ $slot }}

            </tbody>
        </table>
    </div>
</div>
