<x-app-layout>
  <div class="flex flex-col gap-5"
  x-data="{
    licence: @js($licence),
    serviceId: '',
    get activeLicence() { return this.licence?.status !== 'Deactivated' },
    get route() { return `/licence/${this.licence?.id}/operations` },  
    get action(){ return `/licenceOperationApplication/${this.licence?.id}/${this?.serviceId}` },
  }"
  @licence-card-updated.window="licence = event.detail; route; activeLicence"
  >
    <div class="flex flex-col items-center w-full">
      <h1 class="text-white text-xl font-bold m-5">
        Recent issued licence for <span class="text-cyan-300">{{ $licence['person']['name'] }}</span>
      </h1>
      <x-custom.search
      event_name="licence-card-updated"
      :filter="false"
      :routes="$routes"
      :searchBy="$searchBy"
      />
      <x-driving-licence-card id="licence"  :licence="$licence"/>
      <div id="options" class="m-3">
        <div id="selectionSubmit" class="flex gap-5 m-3">
          <form x-bind:action="action" method="post">
            @csrf
            <input type="hidden" name="licence_id" value="{{ $licence['id'] }}">
            <select
                x-show="activeLicence"
                name="service_type"
                x-model="serviceId"
                class="mb-4 w-full text-white bg-[#1e2838] border border-zinc-700 rounded-xl text-sm px-4 py-2.5
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                hover:border-zinc-500 transition">
              <option value="" disabled>Select Licence service</option>
              <option value="{{ $renew }}">Renew Licence</option>
              <option value="{{ $lost }}">Replace lost licence</option>
              <option value="{{ $damaged }}">Replace damaged licence</option>
              <option value="{{ $release }}">Release detained licence</option>
            </select>

            <button
                x-show="activeLicence"
                type="submit"
                class="w-full bg-blue-500 text-white font-semibold rounded-xl px-4 py-2.5
                      hover:bg-blue-400 active:scale-[0.98]
                      focus:outline-none focus:ring-2 focus:ring-blue-500
                      transition shadow-lg shadow-blue-900/40">
                Submit Application
            </button>
          </form>
        </div>
        <div id="pdfOperations" class="flex gap-5 m-3">
          <a
            x-show="activeLicence"
            x-bind:href="route"
            class="text-center p-2.5 rounded-md bg-[#0979a5] text-white font-bold">
            Licence operations
          </a>
          <button
            id="btn"
            class="cursor-pointer rounded-md w-fit bg-red-500 text-white font-medium p-2.5 hover:bg-red-400 transition"
            onclick="downloadPDF()">
            Download Licence pdf
          </button>
        </div>
      </div>
      <x-input-error :messages="$errors->get('licence_id')"/>
      <x-input-error :messages="$errors->get('licence_action')"/>
    </div>

    <div>
      <h1 class="text-white text-xl font-bold m-5">Licence history</h1>
      <x-custom.search
      :filter="true"
      :routes="$routes"
      :searchBy="$searchBy"
      />
      <div>
        <x-custom.list :columns="$columns" :items="$licences"/>
      </div>
    </div>
  </div>
  <script>
    function downloadPDF() {
      const element = document.getElementById("licence");
      html2canvas(element, {
        backgroundColor: null,
        useCORS: true,
      }).then(canvas => {
          const imgData = canvas.toDataURL("image/png");
          const pdf = new jspdf.jsPDF();
          pdf.addImage(imgData, 'PNG', 10, 10);
          pdf.save("licence.pdf");
        });
    }
  </script>
</x-app-layout>