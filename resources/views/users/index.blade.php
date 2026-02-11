<x-app-layout>
  {{-- {{ dd($searchBy) }} --}}
  <x-custom.list 
  :items="$users" 
  :columns="$columns" 
  :filter="true"
  :searchBy="$searchBy" 
  :searchRoutes="$searchRoutes"/>
</x-app-layout>