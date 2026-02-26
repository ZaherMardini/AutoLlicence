@props(['title', 'menuItems', 'namedRoutes' => true])
@php
  $dropdownDefaultButtonId = 'dropdownDefaultButton_' . uuid_create();
  $dropdownId = 'dropdown_' . uuid_create();
@endphp
<button id="{{ $dropdownDefaultButtonId }}" data-dropdown-toggle="{{ $dropdownId }}" class="inline-flex items-center justify-center text-white bg-[#1e2838] shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5" type="button">
  {{ $title }}
  <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
  </button>
<!-- Dropdown menu -->
<div id="{{ $dropdownId }}" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="{{ $dropdownDefaultButtonId }}">
    @if($namedRoutes)
      @foreach ($menuItems as $title => $link)
      <li>
        <a href="{{route($link)}}" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
          {{ $title }}
        </a>
      </li>
      @endforeach
    @else
      @foreach ($menuItems as $title => $link)
      <li>
        <a href="{{ $link }}" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
          {{ $title }}
        </a>
      </li>
      @endforeach
    @endif
    </ul>
</div>
