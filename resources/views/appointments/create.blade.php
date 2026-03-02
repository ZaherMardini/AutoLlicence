<x-app-layout>
  <div class="flex flex-col gap-4">
    <x-custom.appointment-card 
    :activeAppointmentExist="$activeAppointmentExists"
    :testIsPassed="$testIsPassed"
    :test_type="$testType" 
    :local_licence="$localLicence" 
    :person="$person"
    />
    <x-custom.list :columns="$columns" :items="$appointments"/>
  </div>
</x-app-layout>