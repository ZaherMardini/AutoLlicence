<x-app-layout>
  <div class="flex flex-col gap-5">
    <x-driving-licence-card id="licence"  :licence="$licence"/>
    <button id="btn" class="text-white bg-blue-400 p-3 m-3 cursor-pointer" onclick="downloadPDF()">Download Licence</button>
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