@props(['licence', 'services', 'fines'])
<div class="max-w-md w-full bg-zinc-900 border border-zinc-800 rounded-2xl shadow-lg p-6">
    @php
      use App\Enums\LicenceActions;
      use App\Enums\ApplicationTypes;
      use App\Enums\FineActions;
      $detain = LicenceActions::detain->value;
      $release = LicenceActions::release->value;
      $releaseFee = $services[ApplicationTypes::ReleaseDetained->value]['fees'];
      $fine = $fines[FineActions::release->value]['ammount'];
      $routeName = 'licence.detainRelease';
    @endphp
    <!-- Title -->
    <h2 class="text-lg font-semibold text-white mb-5">
        Detain or Release Licence
    </h2>

    <!-- Form -->
    <form method="POST" action="{{ route($routeName, compact('licence')) }}" class="space-y-5">
        @csrf
        @method('patch')
        <!-- Options -->
        <div class="space-y-3">
            <input type="hidden" name="licence_id" value="{{ $licence['id'] }}"/>
            <!-- Detain -->
            <label class="flex items-center gap-3 p-3 rounded-lg border border-zinc-800 hover:border-zinc-700 cursor-pointer transition">
                <input 
                    type="radio" 
                    name="licence_action" 
                    value="{{ $detain }}"
                    class="w-4 h-4 text-red-500 bg-zinc-900 border-zinc-700 focus:ring-red-500"
                />
                <span class="text-sm text-zinc-200">Detain licence</span>
            </label>

            <!-- Release -->
            <label class="flex items-center gap-3 p-3 rounded-lg border border-zinc-800 hover:border-zinc-700 cursor-pointer transition">
                <input 
                    type="radio" 
                    name="licence_action" 
                    value="{{ $release }}"
                    class="w-4 h-4 text-emerald-500 bg-zinc-900 border-zinc-700 focus:ring-emerald-500"
                >
                <span class="text-sm text-zinc-200">Release licence</span>
            </label>
            <x-input-error :messages="$errors->get('licence_action')"/>
        </div>

        <!-- Fees Preview -->
        <div class="rounded-xl border border-zinc-800 bg-zinc-950 p-4">

            <div class="flex justify-between text-sm text-zinc-400">
                <span>Release fine</span>
                <span>{{ $releaseFee }}$</span>
            </div>

            <div class="flex justify-between text-sm text-zinc-400 mt-2">
                <span>Administrative fee</span>
                <span>{{ $fine }}$</span>
            </div>

            <div class="border-t border-zinc-800 mt-3 pt-3 flex justify-between font-medium text-white">
                <span>Total</span>
                <span>{{ $releaseFee + $fine }}$</span>
            </div>

        </div>



        <!-- Submit -->
        <button 
            type="submit"
            class="cursor-pointer w-full mt-2 bg-white text-black font-medium py-2.5 rounded-lg hover:bg-zinc-200 transition"
        >
            Submit
        </button>

    </form>

</div>