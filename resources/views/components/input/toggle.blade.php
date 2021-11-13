{{--<label class="switch relative inline-block w-10 h-5">--}}
{{--    <input type="checkbox" {{ $attributes }}>--}}
{{--    <span class="slider bg-gray-300 cursor-pointer inset-0 absolute round rounded-full"></span>--}}
{{--</label>--}}


<div class="relative">
    <input type="checkbox" {{ $attributes->merge(['class' => "peer bg-red-600 checked:bg-green-600 focus:bg-green-600 appearance-none cursor-pointer border-0 rounded-full w-12 h-7"]) }} style="background-image: none!important;" />
    <span class="peer-checked:left-6 peer-checked:bg-white transition-all duration-500 pointer-events-none w-5 h-5 block absolute top-1 left-1 rounded-full bg-gray-300"></span>
</div>
