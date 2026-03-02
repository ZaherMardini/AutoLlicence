<x-app-layout>
  <div>
    <x-custom.search event_name="person-id-updated" :searchBy="$searchBy" :routes="$searchRoutes" :filter="false"/>
    <x-custom.person-card :mode="$mode"/>
  </div>
</x-app-layout>