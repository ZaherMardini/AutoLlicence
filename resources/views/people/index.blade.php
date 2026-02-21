<x-app-layout>
  <div>
    <x-custom.search :filter="true" :searchBy="$searchBy" :routes="$searchRoutes"/>
    <x-custom.list 
    :items="$people" 
    :columns="$columns" 
    :filter="true"
    />
  </div>
</x-app-layout>