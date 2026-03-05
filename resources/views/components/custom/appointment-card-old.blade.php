@props(['test_type','local_licence', 'person', 'activeAppointmentExist' => false, 'testIsPassed' => false])
<div class="flex flex-col justify-between bg-black p-6 border border-default rounded-base">
  <h1 class="text-center w-fit text-white bold p-3 m-3 bg-[#6a7282] rounded-md text-2xl">Schedule <span class="text-yellow-500 bold">{{ $test_type['title'] }}</span> appointment</h1>
  <h3 class="p-2 m-2 text-white text-xl">Licence ID: {{ $localLicence['id'] }} | Class: {{ $localLicence['licenceClass']['title'] }} | Fees: {{ $localLicence['licenceClass']['fees'] }}$</h3>
  <h3 class="p-2 m-2 text-white text-xl">Person ID: {{ $person['id'] }} | Person name: {{ $person['name'] }}</h3>
  <form action="{{ route('appointments.store', ['licence_id' => $local_licence['id']]) }}" method="post" id="test">
    @csrf
    @if(!$testIsPassed && !$activeAppointmentExist)
      <input type="date" name="appointment_date" class="m-2 block"
      max="{{ now()->addMonths(2)->format('Y-m-d') }}" min="{{now()->format('Y-m-d')}}"/>
      <x-input-error :messages="$errors->get('appointment_date')"/>
    @endif
    <input type="hidden" x-show="false" name="person_id" value="{{ $person['id'] }}"/>
    <x-input-error :messages="$errors->get('person_id')"/>
    <input type="hidden" x-show="false" name="local_licence_id" value="{{ $local_licence['id'] }}"/>
    <x-input-error :messages="$errors->get('local_licence_id')"/>
    <input type="hidden" x-show="false" name="test_type_id" value="{{ $test_type['id'] }}"/>
    <x-input-error :messages="$errors->get('test_type_id')"/>
    </form>
    @if ($testIsPassed)
    <p class="text-xl font-bold text-red-500 m-2 p-2">Person passed this test</p>
    @elseif (!$activeAppointmentExist)  
    <button type="submit" form="test" class="p-2 m-2 w-fit bg-[#6a7282] text-white rounded-md cursor-pointer">Schedule test</button>
    @else
    @php
      $path = "/tests/{$localLicence['id']}/{$testType['id']}/create";
    @endphp
      <a href="{{ $path }}" class="text-white text-xl font-bold p-2 m-2 bg-green-600 rounded-sm w-fit">Go to test</a>   
    @endif
</div>