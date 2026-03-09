<x-app-layout>
  <div class="flex flex-col gap-5">
    <div>
      <h1 class="text-white text-xl font-bold m-5">Recent issued licence</h1>
      <x-custom.search
      event_name="licence-card-updated"
      :filter="false"
      :routes="$routes"
      :searchBy="$searchBy"
      />
      <x-driving-licence-card :licence="$licence"/>
    </div>
    <div>
      <h1 class="text-white text-xl font-bold m-5">Licence history</h1>
      <x-custom.search
      :filter="true"
      :routes="$routes"
      :searchBy="$searchBy"
      />
      <div>
        <x-custom.list :columns="$columns" :items="$licences"/>
      </div>
    </div>
  </div>
</x-app-layout>