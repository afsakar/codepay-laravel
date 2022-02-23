@props([
'label' => "",
'id' => "",
'active' => "",
])

<div class="form-check form-switch">
    <input
        {{ $attributes->merge(['class' => "form-check-input appearance-none w-9 -ml-10 rounded-full bg-gray-300 float-left h-5 align-top bg-white bg-no-repeat bg-contain focus:ring-transparent focus:outline-none cursor-pointer shadow-sm border-0"]) }} type="checkbox"
        role="switch" id="{{ $id }}" @if($active) checked @endif >
    @if($label)
        <label class="form-check-label inline-block text-gray-800 dark:text-gray-400 ml-1"
               for="{{ $id }}">{{ $label }}</label>
    @endif
</div>
