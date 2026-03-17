@props(['test_type','local_licence', 'person', 'activeAppointmentExist' => false, 'testIsPassed' => false, 'mode' => 'new', 'appointment_to_edit' => null])
<div class="mx-auto my-5 flex flex-col bg-slate-800 border border-slate-700 rounded-2xl p-8 w-full max-w-3xl shadow-lg"
x-data="{
  activeAppointmentExist: @js($activeAppointmentExist),
  testIsPassed: @js($testIsPassed),
  appointmentToEdit: @js($appointment_to_edit),
  get isEditMode(){ return this.mode === 'edit' }, 
  get dateValue(){
    if(this.mode === 'edit' && this.appointmentToEdit){
      return this.appointmentToEdit.appointment_date;
    }
  },
  mode: @js($mode),
  routes: {
    new: @js(route(   'appointments.store',    [ 'licence_id'  => $local_licence['id']  ])),
    edit: @js(route(  'appointments.update',   [ 'licence_id'  => $local_licence['id']  ])),
    cancel: @js(route('appointments.cancel',   [ 'licence_id'  => $local_licence['id']  ])),
  },
  get formAction_addEdit() {
    return this.routes[this.mode];
  },
  get formAction_cancel() {
    return this.routes['cancel'];
  },
  get showDatePicker(){
    return this.isEditMode && this.activeAppointmentExist || !this.activeAppointmentExist && !this.testIsPassed;
  },
}"
>

  <!-- TITLE -->
  <h1 class="text-2xl font-semibold text-white mb-8">
    Schedule 
    <span class="text-yellow-400">{{ $test_type['title'] }}</span>
    Appointment
  </h1>


  <!-- LICENCE INFO -->
  <div class="space-y-3 text-sm text-slate-300 mb-6">

    <div class="flex flex-wrap gap-2 border-b border-slate-700 pb-3">
      <span class="text-slate-400">Licence ID:</span>
      <span class="font-medium text-white">{{ $localLicence['id'] }}</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Class:</span>
      <span class="font-medium text-white">{{ $localLicence['licenceClass']['title'] }}</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Fees:</span>
      <span class="font-medium text-white">{{ $localLicence['licenceClass']['fees'] }}$</span>
    </div>

    <div class="flex flex-wrap gap-2">
      <span class="text-slate-400">Person ID:</span>
      <span class="font-medium text-white">{{ $person['id'] }}</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Name:</span>
      <span class="font-medium text-white">{{ $person['name'] }}</span>
    </div>

  </div>  

    <!-- FORM -->
    {{-- <form id="addEdit" method="post" x-bind:action="formRoute_addEdit" class="space-y-6">
      @csrf
    </form> --}}

    <form id="add_edit_cancel" method="post">
        @csrf
        <div x-show="showDatePicker">
          <label class="block text-sm text-slate-400 mb-2">
            Select Appointment Date
          </label>

          <input
            type="date"
            name="appointment_date"
            x-bind:value="dateValue"
            max="{{ now()->addMonths(2)->format('Y-m-d') }}"
            min="{{ now()->format('Y-m-d') }}"
            class="w-full max-w-xs bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
          />

          <x-input-error :messages="$errors->get('appointment_date')" class="mt-2"/>
        </div>

        <!-- Hidden Fields (unchanged) -->
        <input type="hidden" name="person_id" value="{{ $person['id'] }}"/>
        <x-input-error :messages="$errors->get('person_id')"/>

        <input type="hidden" name="local_licence_id" value="{{ $local_licence['id'] }}"/>
        <x-input-error :messages="$errors->get('local_licence_id')"/>

        <input type="hidden" name="test_type_id" value="{{ $test_type['id'] }}"/>
        <x-input-error :messages="$errors->get('test_type_id')"/>
    </form>

    <div id="options">
      <button x-show="activeAppointmentExist"
        @click="mode = 'edit'; showDatePicker;"
        class="m-2 w-fit cursor-pointer inline-block bg-blue-600/90 border border-blue-500 text-white text-sm font-medium px-6 py-3 rounded-lg"
      >
        Edit appointment
      </button>
      <button type="submit" form="add_edit_cancel" x-bind:formaction="formAction_cancel" x-show="activeAppointmentExist"
        class="m-2 w-fit cursor-pointer inline-block bg-red-600/90 border border-blue-500 text-white text-sm font-medium px-6 py-3 rounded-lg"
      >
        Cancle appointment
      </button>
    </div>
  <div class="mt-8">
    <!-- PASSED MESSAGE -->
    <p
      x-show="testIsPassed"
      class="text-base font-semibold text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg px-4 py-3 w-fit"
    >
        Person already passed this test
    </p>

    <!-- SCHEDULE / EDIT BUTTON -->
    <button
        x-show="!testIsPassed && !activeAppointmentExist"
        type="submit"
        form="add_edit_cancel"
        x-bind:formaction="formAction_addEdit"
        class="cursor-pointer bg-slate-700 border border-slate-600 hover:bg-slate-600 text-white text-sm font-medium px-6 py-3 rounded-lg"
    >
    Schedule Appointment
    </button>
    <button
        x-show="activeAppointmentExist && isEditMode"
        type="submit"
        form="add_edit_cancel"
        x-bind:formaction="formAction_addEdit"
        class="cursor-pointer bg-slate-700 border border-slate-600 hover:bg-slate-600 text-white text-sm font-medium px-6 py-3 rounded-lg"
    >
    Save
    </button>

    @php
      $path = "/tests/{$localLicence['id']}/{$testType['id']}/create";
    @endphp

    <!-- GO TO TEST LINK -->
    <a
        x-show="!testIsPassed && activeAppointmentExist"
        href="{{ $path }}"
        class="m-2 inline-block bg-green-600/90 border border-green-500 text-white text-sm font-medium px-6 py-3 rounded-lg"
    >
        Go to Test
    </a>
  </div>
</div>