@props(['mode' => 'new'])
@php
  use App\Models\Person;
  use App\Models\Country;
  $countries = Country::get();
  $modesLable = ['new' => 'Add new person', 'edit' => 'Update person info', 'read' => 'Show person info']; 
  $searchRoutes = Person::$searchRoutes; 
  $searchBy = Person::searchBy();
@endphp
<div class="flex flex-col justify-between bg-black p-6 border border-default rounded-base w-250"
    x-data="{
    mode: @js($mode),
    person: null,
    get isReadMode(){return this.mode === 'read'},
    get isEditMode(){return this.mode === 'edit'},
    get isNewMode() {return this.mode === 'new'},
    img:{
      male: '/images/defaults/male.png',
      female: '/images/defaults/female.png',
      previewImage: null,
      },
      defaults:{
        name:        @js(old('name', 'default_blade')),
        email:       @js(old('email', 'default@test.com')),
        phone:       @js(old('phone', 'default099999')),
        national_no: @js(old('national_no', 'old-00')),
        date:        @js(old('date_of_birth', '2008-01-01')),
        gender:      @js(old('gender', 'male')),
        address:     @js(old('address', 'def')),
        countryId:   @js(old('country_id', '1')),
    },
    get setImage(){
      if(this.img.previewImage){
        return this.img.previewImage
      }
      if (this.person?.image_path) {
        return '/' + this.person.image_path.replace(/^\/+/, '')
      }      
      return this.defaults.gender === 'male' ? this.img.male : this.img.female;
    },
    handleImageUpload(event){
      const file = event.target.files[0];
      if(!file) return;
      this.img.previewImage = `${URL.createObjectURL(file)}`;
    },
  }"
  @items-updated.window = "person = event.detail"
>
  <x-custom.search :searchBy="$searchBy" :filter="false" :routes="$searchRoutes" x-bind:hidden="isNewMode"/>
  <h4 class="m-2 text-white">Person_ID: <span x-text="person?.id"></span></h4>
  <h4 class="m-2 text-white">Mode: {{ $modesLable[$mode] }}</h4>
  <div class="flex flex-1 p-3 bg-gray-500">
    <form id="form" x-bind:action="isEditMode ? `/people/update/${person?.id}` : `/people/store`" method="post" class="" enctype="multipart/form-data" x-bind:disabled="isReadMode">
      @csrf
      <div id="input_fields" class="flex">
        <div class="mx-2">
          <x-input-label for="name" value="Name"/>        
          <x-text-input id="name" name="name" type="text" x-bind:value="person ? person.name : defaults.name" x-bind:readonly="isReadMode" required autofocus autocomplete="name" class="mt-1 block w-full"/>
          <x-input-error :messages="$errors->get('name')"/>
          </div>
        <div class="mx-2">
          <x-input-label for="email" value="Email"/>        
          <x-text-input id="email" name="email" type="text" x-bind:value="person ? person.email : defaults.email" x-bind:readonly="isReadMode" required autocomplete="name" class="mt-1 block w-full"/>
          <x-input-error :messages="$errors->get('email')"/>
        </div>
        <div class="mx-2">
          <x-input-label for="phone" value="Phone"/>        
          <x-text-input id="phone" name="phone" type="text" x-bind:value="person ? person.phone : defaults.phone" x-bind:readonly="isReadMode" required autocomplete="name" class="mt-1 block w-full"/>
          <x-input-error :messages="$errors->get('phone')"/>
        </div>
        <div class="mx-2">
          <x-input-label for="nationalno" value="Nationalno"/>        
          <x-text-input id="nationalno" name="national_no" type="text" x-bind:value="person ? person.national_no : defaults.national_no" x-bind:readonly="isReadMode" required autocomplete="name" class="mt-1 block w-full"/>
          <x-input-error :messages="$errors->get('national_no')"/>
        </div>
        <div class="mx-2">
          <x-input-label for="date" value="date of birth"/>        
          <x-text-input 
          id="date" name="date_of_birth" type="date" 
          x-bind:readonly="isReadMode" autocomplete="name" 
          class="mt-1 block w-full bg-[#cdcdcd]"
          :max="now()->subYears(18)->format('Y-m-d')"
          x-bind:value="person ? person.date_of_birth : defaults.date"
          />
          <x-input-error :messages="$errors->get('date_of_birth')"/>
        </div>
      </div>
      <div id="country_Address" class="flex">
        <div class="mx-2">
          <x-input-label for="" value="address"/> 
          <textarea name="address" id="" cols="30" x-bind:readonly="isReadMode" x-model="person ? person.address : defaults.address"></textarea>
          <x-input-error :messages="$errors->get('address')"/> 
        </div>
        <div id="country" class="mx-2 flex items-center">
          <select name="country_id" id="" x-model="person ? person.country_id : defaults.countryId" x-bind:disabled="isReadMode">
            <option value="0" disabled selected>Select country</option>
            @foreach ($countries as $country)
            <option value="{{ $country['id'] }}">{{$country['name']}}</option>
            @endforeach
          </select>
          <input type="hidden" name="country_id" x-show="isReadMode" x-bind:value="defaults.countryId">
        </div>
        <div class="flex gap-3 items-center ml-5" x-bind:hidden="isReadMode">
          <input name="gender" type="radio" id="male" x-model="defaults.gender" value="male" x-bind:disabled="isReadMode"/>
          <x-input-label for="male" value="Male"/>        
          <input name="gender" type="radio" id="female" x-model="defaults.gender" value="female" x-bind:disabled="isReadMode"/>
          <x-input-label for="female" value="Female"/>        
        </div>
        <div x-show="!isNewMode" class="flex items-center mx-2">
          <h4 class="m-2 text-white">Gender: <span class="bold" x-text="person?.gender"></span></h4>
        </div>
      </div>
      <input id="file" type="file" name="file" hidden @change="handleImageUpload">
    </form>
    <div class="flex flex-col m-2 p-1">
      <div id="img_box" class="p-5 w-35 h-35 bg-gray-700 rounded-md">
        <img x-bind:src="setImage" alt="Person_image" class="object-cover w-full rounded-base md:h-auto md:w-48 mb-4 md:mb-0">
      </div>
      <div>
        <label for="file" class="underline bold text-[15px]" x-bind:hidden="isReadMode">Set Image</label>
        <x-input-error :messages="$errors->get('file')" class="mt-2" />
      </div>
    </div>
  </div>
  <button x-bind:hidden="isReadMode" type="submit" form="form" class="p-2 m-3 w-fit bg-white text-black rounded-md">Submit</button>
</div>