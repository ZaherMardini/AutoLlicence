<x-app-layout>
  <div>
    <h1 class="text-center text-white bold p-3 m-3 bg-black rounded-md outline-white">Schedule Test</h1>
    <h3 class="p-2 m-2 text-white">Licence ID: {{ $localLicence['id'] }} | Type: {{ $localLicence['licenceClass']['title'] }}</h3>
    <x-custom.search x-show="false" :filter="false" :initial_id="$person['id']" :searchBy="$searchBy" :routes="$searchRoutes"/>
    <x-custom.appointment-card :local_licence_id="$localLicence['id']" :person_id="$person['id']"/>
  </div>
</x-app-layout>