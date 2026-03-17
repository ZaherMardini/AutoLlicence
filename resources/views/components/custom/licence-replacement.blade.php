@props(['licence', 'services', 'fines'])
<div class="max-w-md w-full bg-zinc-900/90 backdrop-blur border border-zinc-800 rounded-2xl shadow-xl p-6 space-y-6">

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
                    class="w-4 h-4 text-amber-500 border-zinc-700 bg-zinc-900 focus:ring-amber-500"
                >

                <span class="text-sm font-medium text-zinc-200">
                    Replacement for lost licence
                </span>

            </label>
            <x-input-error :messages="$errors->get('licence_replacement_service')"/>
            <!-- Damaged -->
            <label class="flex items-center gap-3 p-4 rounded-xl border border-zinc-800 bg-zinc-900 hover:border-amber-500/60 hover:bg-zinc-800/70 transition cursor-pointer">

                <input
                    type="radio"
                    name="licence_replacement_service"
                    value="damaged"
                    class="w-4 h-4 text-amber-500 border-zinc-700 bg-zinc-900 focus:ring-amber-500"
                >

                <span class="text-sm font-medium text-zinc-200">
                    Replacement for damaged licence
                </span>

            </label>
        </div>

        <!-- Fees Preview -->
        <div class="rounded-xl border border-zinc-800 bg-zinc-950 p-4">

            <div class="flex justify-between text-sm text-zinc-400">
                <span>Replacement fee</span>
                <span>€20</span>
            </div>

            <div class="flex justify-between text-sm text-zinc-400 mt-2">
                <span>Administrative fee</span>
                <span>€4</span>
            </div>

            <div class="border-t border-zinc-800 mt-3 pt-3 flex justify-between font-medium text-white">
                <span>Total</span>
                <span>€24</span>
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