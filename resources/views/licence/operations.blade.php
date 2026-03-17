<x-app-layout>
  <div class="flex gap-3 flex-wrap justify-center m-5">
    <div class="flex flex-col gap-3">
      <x-driving-licence-card :licence="$licence"/>
      <a class="font-bold text-white bg-blue-700 p-2 rounded-md w-fit"
        href="{{ route('licence.show', compact('licence')) }}">
        Return to applications page
      </a>
    </div>
    <x-custom.detain-release-licence :licence="$licence" :services="$services" :fines="$fines"/>
    <x-custom.licence-replacement :licence="$licence" :services="$services" :fines="$fines"/>
    <x-custom.renew-licence :licence="$licence" :services="$services" :fines="$fines"/>
  </div>
</x-app-layout>