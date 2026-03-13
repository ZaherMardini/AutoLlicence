<x-app-layout>
  <div class="flex flex-col gap-5">
    <div class="flex flex-col items-center w-full">
      <h1 class="text-white text-xl font-bold m-5">Recent issued licence</h1>
      <x-custom.search
      event_name="licence-card-updated"
      :filter="false"
      :routes="$routes"
      :searchBy="$searchBy"
      />
      <x-driving-licence-card id="licence"  :licence="$licence"/>
      <button id="btn" 
      class="cursor-pointer w-fit mt-2 bg-white text-black font-medium p-2.5 rounded-lg hover:bg-zinc-200 transition"
      onclick="downloadPDF()">Download Licence</button>
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