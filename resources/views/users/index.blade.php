<x-app-layout>
  <div>
    <x-custom.search :filter="true" :searchBy="$searchBy" :routes="$searchRoutes"/>
    <x-custom.list 
    :items="$users" 
    :columns="$columns" 
    />
  </div>
</x-app-layout>