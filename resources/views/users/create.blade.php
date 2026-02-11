<x-guest-layout>
  <div x-data="{person: ''}"
  @items-updated.window = "person = event.detail"
  >
  <form method="POST" action="{{ route('user.store') }}">
    @csrf
    
    <!-- Name -->
        <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <!-- Email Address -->
            <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
          <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
          
          <x-text-input id="password_confirmation" class="block mt-1 w-full"
          type="password"
          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            
            <input type="hidden" name="person_id" x-bind:value="person ? person.id : ''"/>
            <x-input-error :messages="$errors->get('person_id')" class="mt-2" />
              <div class="flex gap-3 items-center ml-5">
                <x-input-label value="Activate user"/>        
                <input name="isActive" type="radio" id="active" value="1" checked/>
                <x-input-label for="active" value="Yes"/>        
                <input name="isActive" type="radio" id="inactive" value="0"/>
                <x-input-label for="inactive" value="No"/>        
                <x-input-error :messages="$errors->get('isActive')" class="mt-2" />
            </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Submit') }}
            </x-primary-button>
        </div>
      </form>
    </x-guest-layout>
    <x-custom.person-card mode="read"/>
</div>