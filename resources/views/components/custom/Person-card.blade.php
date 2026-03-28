@php
  use App\Models\Person;
  use App\Models\Country;
  use App\Enums\CardMode;
  $countries = Country::get();
  $modesLable = [
      CardMode::new->value => 'Add new person',
      CardMode::edit->value => 'Update person info',
      CardMode::read->value => 'Show person info',
      CardMode::locked->value => 'Selected person info'
  ]; 
  $searchRoutes = Person::$searchRoutes; 
  $searchBy = Person::searchBy();
@endphp
@props(['mode' => CardMode::new->value, 'initial_id'])
<div class="flex flex-col justify-between p-6 min-w-150 bg-blue-600/30 backdrop-blur-lg rounded-xl shadow-lg"
    x-data="{
    mode: @js($mode),
    person: null,
    get isReadMode(){return this.mode === 'read'},
    get isEditMode(){return this.mode === 'edit'},
    get isNewMode() {return this.mode === 'new'},
    defaults:{
      name: @js(old('name', 'default_blade')),
      email: @js(old('email', 'default@test.com')),
      phone: @js(old('phone', 'default099999')),
      national_no: @js(old('national_no', 'old-00')),
      date: @js(old('date_of_birth', '2008-01-01')),
      gender: @js(old('gender', 'male')),
      address: @js(old('address', 'def')),
      countryId: @js(old('country_id', '1')),
      img:{
        male: '/images/defaults/male.png',
        female: '/images/defaults/female.png',
        previewImage: null,
      },
    },
    get setImage(){
      if(this.defaults.img.previewImage){
        return this.defaults.img.previewImage
      }
      if (this.person?.image_path){
        return '/' + this.person.image_path.replace(/^\/+/, '')
      } 
      return this.defaults.gender === 'male' ? this.defaults.img.male : this.defaults.img.female;
    },
    get form_action(){ return this.isEditMode ? `/people/update/${this.person?.id}` : `/people/store` },
    handleImageUpload(event){
      const file = event.target.files[0];
      if(!file) return;
      this.defaults.img.previewImage = `${URL.createObjectURL(file)}`;
    },
  }"
  @person-id-updated.window = "person = event.detail;"
>
  <h1 class="mb-4 text-2xl font-semibold text-gray-100">{{ $modesLable[$mode] }}</h1>
  <a x-show="!isNewMode" href="{{ route('person.create') }}" class="w-fit rounded-md mb-4 text-md font-semibold bg-cyan-400 p-2 text-white">Add new person</a>
  <h4 class="mb-6 text-gray-300" x-show="!isNewMode">Person ID: <span x-text="person?.id"></span></h4>

  <div class="flex flex-col md:flex-row gap-6">
    <form id="form" x-bind:action="form_action" 
          method="post" enctype="multipart/form-data" class="flex-1 space-y-4"
          x-bind:disabled="isReadMode">
      @csrf

      <!-- Inputs stacked nicely -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <x-input-label for="name" value="Name"/>
          <x-text-input id="name" name="name" type="text" x-bind:value="person ? person.name : defaults.name" 
                        x-bind:readonly="isReadMode" required autocomplete="name"
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
          <x-input-error :messages="$errors->get('name')"/>
        </div>

        <div>
          <x-input-label for="email" value="Email"/>
          <x-text-input id="email" name="email" type="email" x-bind:value="person ? person.email : defaults.email" 
                        x-bind:readonly="isReadMode" required autocomplete="email"
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
          <x-input-error :messages="$errors->get('email')"/>
        </div>

        <div>
          <x-input-label for="phone" value="Phone"/>
          <x-text-input id="phone" name="phone" type="text" x-bind:value="person ? person.phone : defaults.phone" 
                        x-bind:readonly="isReadMode" required autocomplete="tel"
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
          <x-input-error :messages="$errors->get('phone')"/>
        </div>

        <div>
          <x-input-label for="nationalno" value="National No"/>
          <x-text-input id="nationalno" name="national_no" type="text" x-bind:value="person ? person.national_no : defaults.national_no" 
                        x-bind:readonly="isReadMode" required
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
          <x-input-error :messages="$errors->get('national_no')"/>
        </div>

        <div>
          <x-input-label for="date" value="Date of Birth"/>
          <x-text-input id="date" name="date_of_birth" type="date" 
                        x-bind:readonly="isReadMode" autocomplete="bday"
                        :max="now()->subYears(18)->format('Y-m-d')"
                        x-bind:value="person ? person.date_of_birth : defaults.date"
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
          <x-input-error :messages="$errors->get('date_of_birth')"/>
        </div>

        <!-- Gender (readonly) -->
        <div>
          <x-input-label for="gender" value="Gender"/>
          <x-text-input id="gender" type="text" 
                        x-show="isReadMode" readonly
                        x-bind:value="person?.gender"
                        class="mt-1 block w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"/>
        </div>
      </div>

      <!-- Address and country -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <x-input-label for="address" value="Address"/>
          <textarea name="address" x-bind:readonly="isReadMode" x-model="person ? person.address : defaults.address" 
                    class="w-full mt-1 rounded-md bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 p-2 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20"></textarea>
          <x-input-error :messages="$errors->get('address')"/> 
        </div>

        <div class="flex flex-col gap-2">
          <x-input-label for="country_id" value="Country"/>
          <select name="country_id" x-model="person ? person.country_id : defaults.countryId" 
                  x-bind:disabled="isReadMode"
                  class="w-full rounded-md bg-gray-800 border border-gray-700 text-gray-100 p-2 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20">
            <option value="0" disabled selected>Select country</option>
            @foreach ($countries as $country)
              <option value="{{ $country['id'] }}">{{$country['name']}}</option>
            @endforeach
          </select>
          <div class="flex items-center gap-4 mt-2" x-bind:hidden="isReadMode">
            <label class="flex items-center gap-1 text-white font-bold">
              <input name="gender" type="radio" x-model="defaults.gender" value="male" class="accent-indigo-500"/>
              Male
            </label>
            <label class="flex items-center gap-1 text-white font-bold">
              <input name="gender" type="radio" x-model="defaults.gender" value="female" class="accent-indigo-500"/>
              Female
            </label>
          </div>
        </div>
      </div>
    </form>

    <!-- Image preview -->
    <div class="flex flex-col items-center justify-start gap-4">
      <div class="w-48 h-48 bg-gray-800 rounded-lg overflow-hidden shadow-inner">
        <img x-bind:src="setImage" alt="Person Image" class="w-full h-full object-cover"/>
      </div>
      <label for="f" class="mt-2 text-indigo-400 underline cursor-pointer" x-bind:hidden="isReadMode">Set Image</label>
      <input id="f" type="file" name="file" form="form" hidden @change="handleImageUpload">
      <x-input-error :messages="$errors->get('file')" class="mt-1"/>
    </div>
  </div>

  <button x-bind:hidden="isReadMode" type="submit" form="form" 
          x-bind:formaction="form_action"
          class="cursor-pointer mt-6 px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
    Submit
  </button>
</div>