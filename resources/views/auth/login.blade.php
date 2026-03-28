<x-guest-layout>
    <!-- Session Status -->
<div
  class="w-fit mr-3 px-8 py-6
  bg-blue-600/30 backdrop-blur-lg  
  border border-white/20
  shadow-xl overflow-hidden sm:rounded-2xl self-end">
    <div class="self-end w-fit mr-3">
      <x-auth-session-status class="mb-4" :status="session('status')" />
      <form method="POST" action="{{ route('login') }}">
          @csrf
          <!-- Email Address -->
          <div>
              <x-input-label class="text-white" for="email" :value="__('Email')" />
              <input
                  id="email"
                  class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                  type="email"
                  name="email"
                  :value="old('email')"
                  required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>
          <!-- Password -->
          <div class="mt-4">
              <x-input-label class="text-white" for="password" :value="__('Password')" />
              <input
                id="password"
                class ="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                type="password"
                name="password"
                required autocomplete="current-password" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>
          <!-- Remember Me -->
          <div class="block mt-4">
              <label for="remember_me" class="inline-flex items-center">
                  <input id="remember_me" type="checkbox" class="rounded dark:bg-blue-600/30 border-gray-300 dark:border-gray-700 text-cyan-600 shadow-sm focus:ring-cyan-500 dark:focus:ring-cyan-600 dark:focus:ring-offset-gray-800" name="remember">
                  <span class="ms-2 text-sm dark:text-white">{{ __('Remember me') }}</span>
              </label>
          </div>
          <div class="flex items-center justify-end mt-4">
            <a class="px-3 underline text-sm dark:text-white dark:hover:text-cyan-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                {{ __('New user?') }}
            </a>
              @if (Route::has('password.request'))
                  <a class="underline text-sm  dark:text-white dark:hover:text-cyan-400 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                      {{ __('Forgot your password?') }}
                  </a>
              @endif
              <x-primary-button class="ms-3">
                  {{ __('Log in') }}
              </x-primary-button>
          </div>
      </form>
    </div>
</div>
</x-guest-layout>
