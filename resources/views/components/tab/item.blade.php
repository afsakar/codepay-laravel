@props(['key'])

<div {{ $attributes }} x-show="openTab === {{ $key }}">{{ $slot }}</div>
