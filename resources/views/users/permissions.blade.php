<x-app-layout>
  <div class="flex flex-col">
    <x-custom.search event_name="user-id-updated" :filter="false" :routes="$searchRoutes" :searchBy="$searchBy"/>
    <x-custom.users.user-permissions/>
  </div>
</x-app-layout>