<x-app-layout>
  <div class="">
    <x-custom.search event_name="licence-id-updated" 
    :filter="false" 
    :routes="$searchRoutes" 
    :searchBy="['Local licence application ID' => 'local_licences.id']"
    />
    <x-custom.local-licence-card mode="read"/>
  </div>
</x-app-layout>