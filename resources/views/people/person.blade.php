<x-app-layout>
  <div>
    <x-custom.search :searchBy="$searchBy" :routes="$searchRoutes" :filter="false"/>
    <x-custom.person-card :mode="$mode"/>
  </div>
</x-app-layout>