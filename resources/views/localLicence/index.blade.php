@php
  use App\Global\Menus;
@endphp

<x-app-layout>
  <div>
    {{-- <x-custom.dropdown-button :title="Menus::$tests['title']" :menuItems="Menus::$tests['options']"/> --}}
    <x-custom.search :filter="true" :searchBy="$searchBy" :routes="$searchRoutes"/>
    <x-custom.list
    :items="$items"
    :columns="$columns"
    />
  </div>
</x-app-layout>