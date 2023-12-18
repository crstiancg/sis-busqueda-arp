<template>
    <q-btn color="negative" :label="$q.screen.lt.sm ? '' : 'Generar PDF'" icon-right="picture_as_pdf" @click="generarPDF"/>
    <!-- <img src="src/assets/img/logo_ARP.png" alt=""> -->
</template>

<script setup>
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import { ref } from "vue";

const props = defineProps({
  datosSolicitud:{default:{}},
  datosBusqueda:{default:{}},
  datosVerificacion:{default:{}},
  label:{default:'Generar PDF'}
});

function generarPDF() {
  const nombrePDF = "mi_pdf_nombre.pdf";
  // Crear un nuevo documento PDF
  const doc = new jsPDF("p", "mm", "a4");
  // Configurar el formato y estilo
  doc.setFont("times");
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0); // Color de texto: negro

  const leftMargin = 20;
  const topMargin = 20;

  const maxWidth = doc.internal.pageSize.width - leftMargin  * 2;

  // Agregar contenido al PDF con formato
  doc.addImage('src//assets/img/logo_ARP.png', 'PNG', 25, 10, 55, 0);
  // doc.text("ARCHIVO REGIONAL DE LA NACION", 25, 12);
  // doc.text("Archivo Regional de Puno", 38, 18);
  doc.text(obtenerFechaHoraActual(), 120, 18);
  doc.setLineWidth(0.5);
  doc.line(20, 24, 190, 24); // Línea separadora


  doc.text("N° Solicitud: {id_solicitud}", 120, 30);
  const parrafo1 = `      Yo, ${ props.datosSolicitud.nombre_completo } natural de ${ props.datosSolicitud.ubigeo_cod } identificado con D.N.I. ${ props.datosSolicitud.num_documento } y con domicilio en ${ props.datosSolicitud.direccion } del distrito ${ props.datosSolicitud.ubigeo_cod }, ante Usted con el debido respeto me presento y expongo:`;
  const lineas = doc.splitTextToSize(parrafo1, maxWidth);
  doc.text(lineas, 20, 40, { align: "justify" , maxWidth: maxWidth});

  doc.text("Celular: "+props.datosSolicitud.celular, 20, 60);
  doc.text("Correo: "+props.datosSolicitud.correo, 70, 60);

  const parrafo2 = `      Que amparado en los Dispositivos Legales Vigentes, Solicito se me expida el documento de acuerdo al los siguientes detalles: ` ;
  doc.text(doc.splitTextToSize(parrafo2, maxWidth), 20, 70,{ align: "justify" , maxWidth: maxWidth});

  doc.rect(25, 80, 8, 5); doc.text("Testimonio", 35, 84);
  doc.rect(60, 80, 8, 5); doc.text("Copia Certificada", 70, 84);
  doc.rect(105, 80, 8, 5);  doc.text("Copia Simple", 115, 84);

  doc.text("Otros:", 150, 80);
  doc.line(150, 86, 190, 86);

  doc.text("DATOS DEL DOCUMENTO:", 20, 95);
  doc.line(20, 97, 70, 97);

  doc.text("Escritura Pública", 30, 105); doc.text(": "+props.datosSolicitud.subserie, 65, 105);
  doc.text("Otorgado por", 30, 112);      doc.text(": "+props.datosSolicitud.otorgantes, 65, 112);
  doc.text("A Favor de", 30, 119);        doc.text(": "+props.datosSolicitud.favorecidos, 65, 119);
  doc.text("Notario Público", 30, 126);   doc.text(": "+props.datosSolicitud.notario, 65, 126);
  doc.text("Lugar y Fecha", 30, 133);     doc.text(": {lugar fecha}"+props.datosSolicitud.ubigeo_cod_soli, 65, 133);
  doc.text("Nombre del Bien", 30, 140);   doc.text(": "+props.datosSolicitud.bien, 65, 140);
  doc.text("Otros", 30, 147);             doc.text(": "+props.datosSolicitud.masdatos, 65, 147);
  doc.text("POR LO TANTO: Ruego a Usted acceder a mi solicitud por ser justa y legal.", 20, 155);
  doc.text('Puno, '+formatDate(), 120, 162);

  doc.line(40, 175, 90, 175); // firma del solicitante
  doc.text('FIRMA DEL SOLICITANTE', 40, 180);
  doc.text('IMPORTE: S/{importe}', 120, 175);

  doc.text("FASE DE BUSQUEDA:", 20, 190);
  doc.line(20, 192, 70, 192);

  doc.text("Observaciones del Buscador:{onserva}_____________________________________________________________________________________________________________________________________________", 20, 197,{align: "justify" , maxWidth: maxWidth});
  doc.text('IMPORTE: S/{importe}', 120, 175);

  window.open(doc.output("bloburl"), "_blank");
}
/*
Observaciones del Buscador:
Registro NO
Protocolo
Escritura NO
Minutura NO
Buscado por:
Verificado por:
Fotocopiado por:
Derecho de NO de
Verificacion:
NO de Fotocopias
Observaciones :
Folios del
gajo NO
Al
Firm
Firma
Firma
NO de Hojas S/
Registro NO
Protocolo
Escritura NO
Minutura NO
Buscado por:
Verificado por:
Fotocopiado por:
Derecho de NO de
Verificacion:
NO de Fotocopias
Observaciones :
Folios del
gajo NO
Al
Firm
Firma
Firma
NO de Fojas S/-
*/
// lineas.forEach((linea) => {
//   doc.text(linea, leftMargin, yPos,{ align: "justify" });
//   yPos += 5; // Ajustar el espaciado entre líneas según sea necesario
// });

function obtenerFechaHoraActual() {
  const fecha = new Date();
  // Opciones para la fecha
  const opcionesFecha = { year: "numeric", month: "long", day: "numeric" };
  const fechaFormateada = fecha.toLocaleDateString("es-ES", opcionesFecha);
  // Opciones para la hora
  const opcionesHora = { hour: "numeric", minute: "numeric", second: "numeric", hour12: true };
  const horaFormateada = fecha.toLocaleTimeString("es-ES", opcionesHora);
  // Combinar fecha y hora
  const fechaHoraFormateada = `${fechaFormateada} , ${horaFormateada}`;
  return fechaHoraFormateada;
}

function formatDate(date = new Date()) {
  const daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
  const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

  const dayOfWeek = daysOfWeek[date.getDay()];
  const dayOfMonth = date.getDate();
  const month = months[date.getMonth()];
  const year = date.getFullYear();
  const dayMomth = dayOfMonth < 10 ? `0${dayOfMonth}` : `${dayOfMonth}`;

  return `${dayOfWeek} ${dayMomth} de ${month} ${year}`;
};

function descargarPDF() {
  window.htm12canvas = html2canvas;
  let doc = new jsPDF("p", "pt", "a4");
  doc.html(document.querySelector("#documento"), {
    callback: function (pdf) {
      // pdf.save("mypdf.pdf");
      window.open(pdf.output("bloburl"), "_blank");
    }
  });
}
// doc.save(nombrePDF);
</script>

<!-- function makePDF() {
  window.htm12canvas = html2canvas;
  let doc = new jsPDF("p","pt", "a4");
  doc.html(document.querySelector("#documento"),{
    callback: function(pdf){
      let dataUri = pdf.output('datauristring');
      let iframe = document.createElement('iframe');
      iframe.src = dataUri;
      let destinoDiv = document.querySelector("#ver");
      iframe.style.width = "100%";
      destinoDiv.appendChild(iframe);

      let downloadButton = document.createElement('button');
      downloadButton.textContent = 'Descargar PDF';
      downloadButton.addEventListener('click', function() {
        pdf.save('mypdf.pdf');
      });
      destinoDiv.appendChild(downloadButton);
      // document.body.appendChild(iframe);
    }
  });

} -->
