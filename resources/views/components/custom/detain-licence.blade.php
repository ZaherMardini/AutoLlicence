@props(['licence' => null])
<div class="mx-auto my-5 flex flex-col bg-slate-800 border border-slate-700 rounded-2xl p-8 w-full max-w-3xl shadow-lg">

  <!-- TITLE -->
  <h1 class="text-2xl font-semibold text-white mb-8">
    Detain licence
  </h1>


  <!-- LICENCE INFO -->
  <div class="space-y-3 text-sm text-slate-300 mb-6">

    <div class="flex flex-wrap gap-2 border-b border-slate-700 pb-3">
      <span class="text-slate-400">Licence ID:</span>
      <span class="font-medium text-white">LIC-2006-001</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Class:</span>
      <span class="font-medium text-white">Licence Class</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Issue Reason:</span>
      <span class="font-medium text-white">New</span>
    </div>

    <div class="flex flex-wrap gap-2">
      <span class="text-slate-400">Person ID:</span>
      <span class="font-medium text-white">35</span>

      <span class="text-slate-500">|</span>

      <span class="text-slate-400">Name:</span>
      <span class="font-medium text-white">Zozo mozo</span>
    </div>

  </div>


  <!-- FORM -->
  <form action="#" 
        method="get" 
        id="detain"
        class="space-y-6">
    @csrf


      <div x-show="showDatePicker">
        <label class="block text-sm text-slate-400 mb-2">
          Select Appointment Date
        </label>
        <x-input-error :messages="$errors->get('')" class="mt-2"/>
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
      <button x-show="true"
        @click=""
        class="m-2 w-fit cursor-pointer inline-block bg-blue-600/90 border border-blue-500 text-white text-sm font-medium px-6 py-3 rounded-lg"
      >
        Release detained licence
      </button>
      <button type="submit" form="detain" x-show="true"
        class="m-2 w-fit cursor-pointer inline-block bg-red-600/90 border border-blue-500 text-white text-sm font-medium px-6 py-3 rounded-lg"
      >
        Detain licence
      </button>
    </div>
</div>