@props(['appointment'])
{{-- {{ dd($appointment->toArray()) }} --}}
<div class="flex flex-col justify-between bg-gray-800 p-6 border border-default rounded-base"
x-data="{
  appointment: @js($appointment),
}"
@items-updated.window = "this.appointment = event.detail;"
>
  <div id="info" class="text-white">
    <h1 class="text-xl font-bold text-yellow-400">Test info</h1>
    <h3>Type: <span class="font-bold text-blue-500" x-text="appointment?.test_type?.title"></span></h3>
    <h3>Fees: <span class="font-bold text-blue-500" x-text="appointment?.test_type.fees"></span>$</h3>
    <h3>Trial: <span class="font-bold text-blue-500" x-text="appointment?.trials"></span></h3>
    <h3>Date <span class="font-bold text-blue-500" x-text="appointment?.appointment_date"></span></h3>
  </div>
  <form id="test" action="/tests/{{$appointment['local_licence_id']}}/{{$appointment['test_type']['id']}}/create" method="post">
    @csrf
    <div class="flex gap-3 items-center">
     <x-input-label value="Result"/>
     <x-input-label value="Passed" for="p"/>
     <input type="radio" name="result" id="p">
     <x-input-label value="Failed" for="f"/>
     <input type="radio" name="result" id="f" checked>
   </div>
   <x-input-label for="n" value="Notes"/>
   <textarea id="n" name="notes">Flowless victory</textarea>

   <input type="hidden" name="appointment_id" value="{{ $appointment['id'] }}">
   <input type="hidden" name="test_type_id" value="{{ $appointment['test_type']['id'] }}">
   @php
    use Illuminate\Support\Facades\Auth;
     $userId = Auth::id();
   @endphp
   <input type="hidden" name="created_by_user_id" value="{{ $userId }}">
  </form>
  <x-primary-button form="test">Submit Test result</x-primary-button> 
</div>