@php
  use App\Enums\CardMode;
@endphp
@props(['local_licence_id', 'person_id'])
<div>
  <form action="{{ route('appointments.store', ['licence_id' => $local_licence_id]) }}" method="post" id="test">
    @csrf
    <input type="date" name="appointment_date" class="m-2 block"
    max="{{ now()->addMonths(2)->format('Y-m-d') }}" min="{{now()->format('Y-m-d')}}"/>
    <x-input-error :messages="$errors->get('appointment_date')"/>
    <input type="hidden" x-show="false" name="person_id" value="{{ $person_id }}"/>
    <x-input-error :messages="$errors->get('person_id')"/>
    <input type="hidden" x-show="false" name="local_licence_id" value="{{ $local_licence_id }}"/>
    <x-input-error :messages="$errors->get('local_licence_id')"/>
  </form>
  <x-custom.person-card :mode="CardMode::read->value"/>
  <button type="submit" form="test" class="p-2 m-2 bg-[#6a7282] text-white rounded-md">Schedule test</button>
</div>