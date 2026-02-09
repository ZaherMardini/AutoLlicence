<x-app-layout>
  {{ dd($routes) }}
  <x-custom.list :items="$users", :columns="$columns", :searchRoutes="$routes", :searchBy="$searchBy"/>
</x-app-layout>