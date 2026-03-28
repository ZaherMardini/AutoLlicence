@php
    use Illuminate\Support\Facades\Auth;
    $loggedIn = Auth::check();
    $formAction = $loggedIn ? route('user.store') : route('register');
    @endphp
<div
  class="w-fit mr-3 px-8 py-6
  bg-blue-600/30 backdrop-blur-lg  
  border border-white/20
  shadow-xl overflow-hidden sm:rounded-2xl self-center">
  <div
    class="flex justify-center"
    x-data="{ person: '' }"
    @person-id-updated = " person = event.detail "
    >
    <div class="w-full max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg">
      <h1 class="text-white text-2xl font-bold text-center">Register new user</h1>
      <form id="registerForm" method="POST" action="{{ $formAction }}" class="mt-25">
        @csrf
  
        <!-- Name -->
            <div>
              <x-input-label for="name" :value="__('Name')" />
              <input id="name"
              class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
              type="text" name="name"
              value="{{ old('name') }}"
              required autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
  
            <!-- Email Address -->
            <div class="mt-4">
              <x-input-label for="email" :value="__('Email')" />
              <input id="email"
                class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required autocomplete="email" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
  
            <!-- Password -->
            <div class="mt-4">
              <x-input-label for="password" :value="__('Password')" />
              <input id="password"
                class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                type="password"
                name="password"
                required autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
  
            <!-- Confirm Password -->
            <div class="mt-4">
              <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
              <input id="password_confirmation"
                class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                type="password"
                name="password_confirmation"
                required autocomplete="new-password" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="my-4">
              <x-input-label for="person_id" :value="__('Person ID (select from card)')" />
              <input id="person_id"
                style="width:fit-content"
                class="block mt-1 w-full bg-white/20 border-white/30 text-white placeholder-gray-300 focus:border-cyan-200 focus:ring-cyan-200 rounded-lg"
                type="text"
                x-bind:value="person?.id"
                name="person_id" required readonly />
                <x-input-error :messages="$errors->get('person_id')" class="mt-2" />
            </div>
  
            <div class="flex gap-3 items-center ml-5">
              <x-input-label value="Activate user"/>
              <input name="isActive" type="radio" id="active" value="1" checked/>
              <x-input-label for="active" value="Yes"/>
              <input name="isActive" type="radio" id="inactive" value="0"/>
              <x-input-label for="inactive" value="No"/>
              <x-input-error :messages="$errors->get('isActive')" class="mt-2" />
            </div>
  
            <div class="flex items-center justify-end mt-4">
              @if (!$loggedIn)
                <a
                href="{{ route('login') }}"
                class="underline text-sm text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Already registered?') }}
                </a>
              @endif
              <x-primary-button class="ms-4">
                {{ __('Register') }}
              </x-primary-button>
            </div>
          </form>
        </div>
    <div class="m-3">
      {{ $slot }}
    </div>
  </div>
</div>
