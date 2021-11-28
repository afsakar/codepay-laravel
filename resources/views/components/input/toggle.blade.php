@props([
'offColor' => "bg-gray-500",
'onColor' => "bg-green-400",
'rounded' => "rounded-full",
'size' => "20",
])

<label
    data-toggle="checkbox-toggle"
    data-handle-size="{{ $size }}"
    data-rounded="{{ $rounded }}"
    data-handle-color="bg-white"
    data-off-color="{{ $offColor }}"
    data-on-color="{{ $onColor }}">
    <input type="checkbox" {{ $attributes }} />
</label>
