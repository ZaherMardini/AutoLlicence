  @php
    use App\Models\LicenceClass;
    use App\Enums\CardMode;
    use App\Enums\TestTypes;
    $classes = LicenceClass::get();
  @endphp
  @props(['mode' => CardMode::new->value, 'initial_id' => '', 'viewPersonCard' => false])
  <div
  x-data="
  {
   testTypes: {
    vision: @js(TestTypes::VisionTest->value),
    written: @js(TestTypes::WrittenTest->value),
    street: @js(TestTypes::StreetTest->value),
    },
    testOptionVisibility: {
      vision:  false,
      written: false,
      street:  false,
   },
    person: '',
    local_licence: '',
    formRoute: '{{ route('localLicence.store') }}',
    get showLicencePath(){ return `/licence/${this.local_licence?.licence_number}` },
    get localLicenceRoute(){
      if(this.local_licence?.passedTests === 3 && !this.local_licence?.licence_issued){
        return `/licence/${this.local_licence.id}/create`; 
      } 
      return '#'
    },
    get visionTestRoute() {
        return this.local_licence?.id
            ? `/appointments/${this.local_licence.id}/${this.testTypes.vision}/create`
            : '#';
    },
    get writtenTestRoute() {
        return this.local_licence?.id
            ? `/appointments/${this.local_licence.id}/${this.testTypes.written}/create`
            : '#';
    },
    get streetTestRoute() {
        return this.local_licence?.id
            ? `/appointments/${this.local_licence.id}/${this.testTypes.street}/create`
            : '#';
    },
    mode: @js($mode),
    classId: @js(old('licence_class_id', default: '1')),
    get isReadMode(){ return this.mode === '{{ CardMode::read->value }}' },
    get isNewMode() { return this.mode === '{{ CardMode::new->value }}' },
    handlePersonId(){
      if(this.person){
        return this.person.id;
        }
        return this.local_licence?.person_id;
    },
    handleOptionVisibility(){
      if(this.local_licence && this.local_licence.passedTests === 0){
        this.testOptionVisibility.vision = true;
      }
      if(this.local_licence && this.local_licence.passedTests === 1){
        this.testOptionVisibility.written = true;
      }
      if(this.local_licence && this.local_licence.passedTests === 2){
        this.testOptionVisibility.street = true;
      }
    },
    disableAllOptions(){
      this.testOptionVisibility.street = false;
      this.testOptionVisibility.written = false;
      this.testOptionVisibility.vision = false;
    },
  }" 
    @person-id-updated.window = "person = event.detail; handlePersonId()"

    @licence-id-updated.window="
    local_licence = event.detail;
    if (local_licence?.person) {
      $dispatch('person-id-updated', local_licence.person);
    };
    handlePersonId();
    disableAllOptions();
    handleOptionVisibility();
  "  
  >

  {{-- Chat gpt HTML --}}
  <div x-show="isReadMode" class="flex gap-5">
    <!-- Title -->
    <div id="dataContainer" class="bg-gray-900 border border-gray-700 rounded-lg p-6 w-full max-w-md m-5">
      <h1 class="text-xl font-semibold text-white mb-4">
        Licence Information
      </h1>
      <!-- Licence ID -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">Local licence application ID</p>
          <x-text-input x-bind:readonly="isReadMode" x-bind:value="local_licence?.id" class="text-base text-white font-medium"/>
        </div>
        <!-- Licence Class Title -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">Licence Class</p>
          <x-text-input x-bind:readonly="isReadMode" x-bind:value="local_licence?.licence_class?.title" class="text-base text-white font-medium"/>
        </div>
        <div class="mb-3">
          <p class="text-sm text-gray-400">Passed tests</p>
          <x-text-input x-bind:readonly="isReadMode" x-bind:value="local_licence?.passedTests" class="text-base text-white font-medium"/>
        </div>
        <!-- Class Fees -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">Class Fees</p>
          <x-text-input x-bind:readonly="isReadMode" x-bind:value="local_licence?.licence_class?.fees" class="text-base text-white font-medium"/>
        </div>
        <div class="mb-3">
          <p class="text-sm text-gray-400">Minimum Allowed Age</p>
          <x-text-input x-bind:readonly="isReadMode" x-bind:value="local_licence?.licence_class?.minimum_allowed_age" class="text-base text-white font-medium">21 Years</x-text-input>
        </div>
    </div>

    <div id="menu-container" class="bg-gray-900 border border-gray-700 rounded-lg p-6 w-full max-w-sm m-5">
      <h1 x-show="!local_licence?.licence_issued" class="text-center text-xl font-semibold text-white mb-4">Schedule Test</h1>
      <h1 x-show="local_licence?.licence_issued" class="text-center text-xl font-semibold text-white mb-4">Issue Licence</h1>
      <ul class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm divide-y divide-gray-700 text-gray-200 text-center">
        <li x-show="testOptionVisibility.vision">
          <a x-bind:href="visionTestRoute"
            class="block px-4 py-3 hover:bg-gray-800">
            Vision Test
          </a>
        </li>
    
        <li x-show="testOptionVisibility.written">
          <a x-bind:href="writtenTestRoute"
            class="block px-4 py-3 hover:bg-gray-800">
            Written Test
          </a>
        </li>
    
        <li x-show="testOptionVisibility.street">
          <a x-bind:href="streetTestRoute"
            class="block px-4 py-3 hover:bg-gray-800">
            Street Test
          </a>
        </li>
        <li x-show="local_licence.passedTests === 3" class="p-3">
          <div>
            <h1 class="text-2xl text-cyan-500 font-bold text-center">All tests passed</h1>
            <form x-bind:action="localLicenceRoute" method="post">
            @csrf
            <x-text-input type="hidden" name="licence_class_id" x-bind:value="local_licence?.licence_class?.id"/>
            <x-text-input type="hidden" name="person_id" x-bind:value="local_licence?.person?.id"/>
            <button
              x-show="!local_licence?.licence_issued"
              type="submit"
              class="cursor-pointer m-2 px-6 py-3 text-lg font-semibold
                    text-cyan-400 bg-zinc-800
                    rounded-xl
                    border border-zinc-700
                    shadow-lg shadow-cyan-500/10
                    transition-all duration-300
                    hover:bg-zinc-700 hover:text-cyan-300 hover:shadow-cyan-400/20
                    active:scale-95
                    focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
              Issue Licence
            </button>
            </form>
          </div>
        </li>    
        <div x-show="local_licence?.licence_issued" class="text-2xl text-white p-3 rounded-md font-bold text-center bg">
          <a x-bind:href="showLicencePath" >Show licence</a>
        </div>
      </ul>  
    </div>
  </div>
    {{-- Chat gpt HTML --}}
    

    <form id="local" x-bind:action="isNewMode ? formRoute : '#'" method="post">
      @csrf
      <select name="licence_class_id" x-model="classId" x-show="isNewMode" x-bind:disabled="isReadMode" class="m-5 rounded-md">
        <option value="0" selected disabled>Select class</option>
        @foreach ($classes as $class)
        <option value="{{ $class['id'] }}">{{ $class['title'] }}</option>
        @endforeach
      </select>
      <x-input-error :messages="$errors->get('licence_class_id')"/>
        <input name="person_id" type="hidden" x-bind:value="handlePersonId"/>
        <x-input-error :messages="$errors->get('person_id')"/>
        </form>
        <x-custom.person-card :mode="CardMode::read->value"/>
    <button x-show="isNewMode" type="submit" form="local" class="p-2 m-2 bg-[#6a7282] text-white rounded-md">create</button>
  </div>