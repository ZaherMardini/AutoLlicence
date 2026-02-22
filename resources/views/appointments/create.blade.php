<x-app-layout>
  <div>
    <x-custom.appointment-card :test_type="$testType" :local_licence="$localLicence" :person="$person"/>
    <x-custom.list :columns="$columns" :items="$appointments"/>
  </div>
</x-app-layout>