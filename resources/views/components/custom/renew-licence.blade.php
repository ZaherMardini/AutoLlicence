@props(['licence', 'services', 'fines'])
@php
  use App\Enums\ApplicationTypes;
  $formAction = "/licence/{$licence['id']}/renew";
  $service = $services[ApplicationTypes::RenewLicence->value];
@endphp
<div class="max-w-md w-full bg-zinc-900/90 backdrop-blur border border-zinc-800 rounded-2xl shadow-xl p-6 space-y-6">

    <!-- Title -->
    <div>
        <h2 class="text-lg font-semibold text-white">
            Renew Licence
        </h2>
        <p class="text-sm text-zinc-400 mt-1">
            Renew the selected licence and review the renewal fees.
        </p>
    </div>

    <form method="POST" action="{{ route('licence.renew', ['licence' => $licence['id']]) }}" class="space-y-5">
        @csrf
        @method('patch')
        <!-- Renewal Option -->
        <div class="space-y-3">

            <label class="group flex items-center gap-3 p-4 rounded-xl border border-zinc-800 bg-zinc-900 hover:border-indigo-500/60 hover:bg-zinc-800/70 transition cursor-pointer">
                <input
                    type="radio"
                    name="licence_renew_service"
                    value="3"
                    class="w-4 h-4 text-indigo-500 border-zinc-700 bg-zinc-900 focus:ring-indigo-500"
                />
                <span class="text-zinc-200 text-sm font-medium">
                    Renew licence
                </span>
                <input type="hidden" name="licence_id" value="{{ $licence['id'] }}">
              </label>
              <x-input-error :messages="$errors->get('licence_renew_service')"/>
              <x-input-error :messages="$errors->get('licence_id')"/>
        </div>

        <!-- Fees Section -->
        <div class="rounded-xl border border-zinc-800 bg-zinc-950 p-4">

            <div class="flex items-center justify-between text-sm text-zinc-400">
                <span>Base renewal fee</span>
                <span>{{ $service['fees'] }} $</span>
            </div>

            <div class="flex items-center justify-between text-sm text-zinc-400 mt-2">
                <span>Processing fee</span>
                <span>{{ $service['base_application_fee'] }} $</span>
            </div>

            <div class="border-t border-zinc-800 mt-3 pt-3 flex justify-between font-medium text-white">
                <span>Total</span>
                <span>{{ $service['base_application_fee'] + $service['fees'] }} $</span>
            </div>

        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-2.5 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-500 transition shadow-lg shadow-indigo-900/40"
        >
            Renew Licence
        </button>

    </form>

</div>