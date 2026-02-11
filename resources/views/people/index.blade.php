<x-app-layout>
  <x-custom.list 
  :items="$people" 
  :columns="$columns" 
  :filter="true"
  :searchBy="$searchBy" 
  :searchRoutes="$searchRoutes"/>
</x-app-layout>