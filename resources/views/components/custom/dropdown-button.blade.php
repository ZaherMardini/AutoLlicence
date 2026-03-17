@props(['title', 'menuItems', 'enableNamedRoutes' => true])

@php
    $dropdownDefaultButtonId = 'dropdownDefaultButton_' . uuid_create();
    $dropdownId = 'dropdown_' . uuid_create();
@endphp

<button id="{{ $dropdownDefaultButtonId }}"
        data-dropdown-toggle="{{ $dropdownId }}"
        class="inline-flex items-center justify-center text-white bg-[#1e2838] shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5"
        type="button">
    {{ $title }}

    <svg class="w-4 h-4 ms-1.5 -me-0.5"
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="m19 9-7 7-7-7"/>
    </svg>
</button>

<div id="{{ $dropdownId }}"
     class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">

    <ul class="p-2 text-sm text-body font-medium"
        aria-labelledby="{{ $dropdownDefaultButtonId }}">
        @foreach ($menuItems as $item)
          {{-- {{ dd($menuItems) }} --}}
            <x-custom.menu-item :item="$item" :enableNamedRoutes="$enableNamedRoutes" />
        @endforeach
    </ul>
</div>