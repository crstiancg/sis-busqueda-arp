<template>
    <q-btn color="negative" :label="$q.screen.lt.sm || vericon? '' : label" :icon-right="vericon?'':'picture_as_pdf'" @click="VerificaDatos"/>
    <!-- <img src="src/assets/img/logo_ARP.png" alt=""> -->
</template>

<script setup>
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import { convertDate } from "src/utils/ConvertDate";
import { ref } from "vue";

const props = defineProps({
  datosSolicitud:{default:null},
  datosSolicitudRow:{default:null},
  datosBusqueda:{default:null},
  datosVerificacion:{default:null},
  label:{default:'Generar PDF'},
  vericon:{default:false},
});

function generarPDF(datos) {
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
  const parrafo1 = `      Yo, ${ datos.nombres } ${ datos.apellido_paterno } ${ datos.apellido_paterno } natural de ${ datos.ubigeo_pers } identificado con D.N.I. ${ datos.num_documento } y con domicilio en ${ datos.direccion } del distrito ${ datos.ubigeo_pers }, ante Usted con el debido respeto me presento y expongo:`;
  const lineas = doc.splitTextToSize(parrafo1, maxWidth);
  doc.text(lineas, 20, 40, { align: "justify" , maxWidth: maxWidth});

  doc.text("Celular: "+datos.celular, 20, 60);
  doc.text("Correo: "+datos.correo, 70, 60);

  const parrafo2 = `      Que amparado en los Dispositivos Legales Vigentes, Solicito se me expida el documento de acuerdo al los siguientes detalles: ` ;
  doc.text(doc.splitTextToSize(parrafo2, maxWidth), 20, 70,{ align: "justify" , maxWidth: maxWidth});

  doc.rect(25, 80, 8, 5); doc.text("Testimonio", 35, 84);
  doc.rect(60, 80, 8, 5); doc.text("Copia Certificada", 70, 84);
  doc.rect(105, 80, 8, 5);  doc.text("Copia Simple", 115, 84);

  doc.text("Otros:", 150, 80);
  doc.line(150, 86, 190, 86);

  doc.text("DATOS DEL DOCUMENTO:", 20, 95);
  doc.line(20, 97, 70, 97);

  doc.text("Escritura Pública", 30, 105); doc.text(": "+datos.subserie, 65, 105);
  doc.text("Otorgado por", 30, 112);      doc.text(": "+datos.otorgantes, 65, 112);
  doc.text("A Favor de", 30, 119);        doc.text(": "+datos.favorecidos, 65, 119);
  doc.text("Notario Público", 30, 126);   doc.text(": "+datos.notario, 65, 126);
  doc.text("Lugar y Fecha", 30, 133);     doc.text(": "+datos.ubigeo_soli+", "+ convertDate(datos.fecha,"d 'de' MMMM, yyyy"), 65, 133);
  doc.text("Nombre del Bien", 30, 140);   doc.text(": "+datos.bien, 65, 140);
  doc.text("Otros", 30, 147);             doc.text(": "+datos.mas_datos, 65, 147);
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

function VerificaDatos(){
  if(props.datosSolicitud){
    generarPDF(props.datosBusqueda)
  }else if(props.datosSolicitudRow){
    // console.log(props.datosSolicitudRow);
    const datosSolici = {
      nombres: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.nombres:'',
      apellido_paterno: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.apellido_paterno:'',
      apellido_materno: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.apellido_materno:'',
      num_documento: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.num_documento:'',
      direccion: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.direccion:'',
      correo: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.correo:'',
      celular: props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.celular:'',
      ubigeo_pers:props.datosSolicitudRow.solicitante?props.datosSolicitudRow.solicitante.ubigeo?props.datosSolicitudRow.solicitante.ubigeo.nombre:'':'',

      otorgantes: props.datosSolicitudRow.otorgantes,
      favorecidos: props.datosSolicitudRow.favorecidos,
      fecha:props.datosSolicitudRow.fecha,
      bien: props.datosSolicitudRow.bien,
      mas_datos: props.datosSolicitudRow.mas_datos,
      notario:props.datosSolicitudRow.notario?props.datosSolicitudRow.notario.nombre_completo:'',
      subserie:props.datosSolicitudRow.subserie?props.datosSolicitudRow.subserie.nombre:'',
      ubigeo_soli:props.datosSolicitudRow.ubigeo_nombre,
      testimonio: "",
      copiaCertificada: "",
      copiaSimple: "",
    };
    generarPDF(datosSolici);
  }
}

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
