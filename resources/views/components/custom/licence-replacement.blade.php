@props(['licence', 'services', 'fines'])
@php
  use App\Enums\ApplicationTypes;
  $lost = $services[ApplicationTypes::LostReplacement->value];
  $damaged = $services[ApplicationTypes::DamagedReplacement->value];
@endphp
<div class="max-w-md w-full bg-zinc-900/90 backdrop-blur border border-zinc-800 rounded-2xl shadow-xl p-6 space-y-6"
  x-data="{ 
  lost_service: @js($lost),
  damaged_service: @js($damaged),
  selected_service: '', 
  total: 0,
  get currentService() {
    return this.selected_service === 'lost'
        ? this.lost_service
        : this.damaged_service;
  },
  setFees(){
    this.total = +this.currentService.fees + +this.currentService.base_application_fee;
  },
  }"
  x-init="setFees()"
>

    <!-- Title -->
    <div>
        <h2 class="text-lg font-semibold text-white">
            Replace Licence
        </h2>
        <p class="text-sm text-zinc-400 mt-1">
            Request a replacement licence and review the applicable fees.
        </p>
    </div>

    <form form method="POST" action="{{ route('licence.replace', ['old_licence' => $licence['id']]) }}" class="space-y-5">
        @csrf

        <!-- Replacement Options -->
        <div class="space-y-3">
            <input type="hidden" name="licence_id" value="{{ $licence['id'] }}">
            <!-- Lost -->
            <label class="flex items-center gap-3 p-4 rounded-xl border border-zinc-800 bg-zinc-900 hover:border-amber-500/60 hover:bg-zinc-800/70 transition cursor-pointer">

                <input
                    type="radio"
                    name="licence_replacement_service"
                    value="lost"
                    x-model="selected_service"
                    class="w-4 h-4 text-amber-500 border-zinc-700 bg-zinc-900 focus:ring-amber-500"
                    @change="setFees();"
                >

                <span class="text-sm font-medium text-zinc-200">
                    Replacement for lost licence
                </span>

            </label>
            <!-- Damaged -->
            <label class="flex items-center gap-3 p-4 rounded-xl border border-zinc-800 bg-zinc-900 hover:border-amber-500/60 hover:bg-zinc-800/70 transition cursor-pointer">

                <input
                    type="radio"
                    name="licence_replacement_service"
                    value="damaged"
                    x-model="selected_service"
                    class="w-4 h-4 text-amber-500 border-zinc-700 bg-zinc-900 focus:ring-amber-500"
                    @change="setFees();"
                >

                <span class="text-sm font-medium text-zinc-200">
                    Replacement for damaged licence
                </span>

            </label>
            <x-input-error :messages="$errors->get('licence_replacement_service')"/>
        </div>

        <!-- Fees Preview -->
        <div class="rounded-xl border border-zinc-800 bg-zinc-950 p-4">

            <div class="flex justify-between text-sm text-zinc-400">
              <span><span x-text="currentService?.title"></span> fee</span>
              <span><span x-text="currentService?.fees"></span> $</span>
            </div>

            <div class="flex justify-between text-sm text-zinc-400 mt-2">
              <span>Administrative fee</span>
              <span><span x-text="currentService?.base_application_fee"></span> $</span>
            </div>

            <div class="border-t border-zinc-800 mt-3 pt-3 flex justify-between font-medium text-white">
              <span>Total</span>
              <span><span x-text="total"></span> $</span>
            </div>

        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-amber-500 text-black font-medium hover:bg-amber-400 transition shadow-lg shadow-amber-900/40"
        >
            Submit Replacement Request
        </button>

    </form>

</div>