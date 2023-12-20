<template>
    <q-btn color="negative" :label="$q.screen.lt.sm || vericon? '' : label" :icon-right="vericon?'':'picture_as_pdf'" @click="VerificaDatos"/>
    <!-- <img src="src/assets/img/logo_ARP.png" alt=""> -->
</template>

<script setup>
import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import { convertDate } from "src/utils/ConvertDate";
const props = defineProps({
  datosSolicitud:{default:null},
  datosSolicitudRow:{default:null},
  datosBusqueda:{default:null},
  datosVerificacion:{default:null},
  label:{default:'Generar PDF'},
  vericon:{default:false},
});
function limitarLineas(Doc,Text,maxWidth,num_lineas){
  const lineas = Doc.splitTextToSize(Text, maxWidth);
  const lin = [];
  for (let index = 0; index < lineas.length; index++) {
    if (index+1<=num_lineas)
      lin[index] = lineas[index];
    else
      break;
  }
  return lin;
}
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
  doc.text(convertDate(datos?.created_at ?datos.created_at:new Date,"d 'de' MMMM, yyyy h:mm:ss a"), 120, 18);
  doc.setLineWidth(0.5);
  doc.line(20, 24, 190, 24); // Línea separadora


  doc.text("N° Solicitud: {id_solicitud}", 120, 30);
  const parrafo1 = `        Yo, ${ datos.nombres } ${ datos.apellido_paterno } ${ datos.apellido_paterno } natural de ${ datos.ubigeo_pers } identificado con D.N.I. ${ datos.num_documento } y con domicilio en ${ datos.direccion } del distrito ${ datos.ubigeo_pers }, ante Usted con el debido respeto me presento y expongo:`;
  // const lineas = doc.splitTextToSize(parrafo1, maxWidth);
  doc.text(parrafo1, 20, 40, { align: "justify" , maxWidth: maxWidth});
  doc.text("Celular: "+datos.celular+'\t'+"Correo: "+datos.correo, 30, 60);

  const parrafo2 = `        Que amparado en los Dispositivos Legales Vigentes, Solicito se me expida el documento de acuerdo al los siguientes detalles: `;
  doc.text(parrafo2, 20, 67,{ align: "justify" , maxWidth: maxWidth});

  doc.rect(25, 76, 8, 5); doc.text("Testimonio", 35, 80);
  doc.rect(60, 76, 8, 5); doc.text("Copia Certificada", 70, 80);
  doc.rect(105, 76, 8, 5);  doc.text("Copia Simple", 115, 80);

  doc.text("Otros:", 145, 77);
  doc.line(145, 83, 190, 83);

  doc.text("DATOS DEL DOCUMENTO:", 20, 90);
  doc.line(20, 92, 70, 92);
  const lineas = doc.splitTextToSize(parrafo1, maxWidth);
  doc.text("Escritura Pública", 25, 98);  doc.text(": "+limitarLineas(doc,datos.subserie,120,2), 60, 98,{ align: "justify" , maxWidth: 130});
  doc.text("Otorgado por", 25, 108);      doc.text(": "+limitarLineas(doc,datos.otorgantes,120,2), 60, 108,{ align: "justify" , maxWidth: 130});
  doc.text("A Favor de", 25, 118);        doc.text(": "+limitarLineas(doc,datos.favorecidos ,120,2), 60, 118,{ align: "justify" , maxWidth: 130});
  doc.text("Notario Público", 25, 128);   doc.text(": "+limitarLineas(doc,datos.notario ,120,2), 60, 128,{ align: "justify" , maxWidth: 130});
  doc.text("Lugar y Fecha", 25, 138);     doc.text(": "+datos.ubigeo_soli+", "+ convertDate(datos.fecha,"d 'de' MMMM, yyyy"), 60, 138,{ align: "justify" , maxWidth: 130});
  doc.text("Nombre del Bien", 25, 145);   doc.text(": "+limitarLineas(doc,datos.bien ,120,2), 60, 145,{ align: "justify" , maxWidth: 130});
  doc.text("Otros", 25, 155);             doc.text(": "+limitarLineas(doc,datos.mas_datos,120,2), 60, 155,{ align: "justify" , maxWidth: 130});
  doc.text("POR LO TANTO:  Ruego a Usted acceder a mi solicitud por ser justa y legal.", 20, 165);

  doc.line(40, 180, 90, 180); // firma del solicitante
  doc.text('FIRMA DEL SOLICITANTE', 40, 184);
  doc.text('IMPORTE: S/{importe}', 120, 175);
  doc.text('Puno, '+convertDate(datos?.created_at?datos.created_at:new Date,"EEEE d 'de' MMMM y"), 120, 183);

  doc.text("FASE DE BUSQUEDA:", 20, 190);
  doc.line(20, 192, 70, 192);
  doc.text("Buscado por:", 25, 197);    doc.text("Firma:__________", 135, 197);
  doc.text("Protocolo:", 25, 204);    doc.text("Registro N°:", 80, 204);    doc.text("Legajo N°:", 135, 204);
  doc.text(true?"Escritura N°:":"Minuta N°:", 25, 211);   doc.text("Folio, del:", 80, 211);    doc.text("al:", 135, 211);
  doc.text("Observaciones del Buscador:{oBserva}____________________________________________________________________________________________________________", 25, 218,{align: "justify" , maxWidth: maxWidth-10});

  doc.text("FASE DE VERIFICACIÓN:", 20, 232);
  doc.line(20, 234, 70, 234);
  doc.text("Verificado por:", 25, 241);    doc.text("Firma:__________", 135, 241);
  doc.text("Derecho N° Copias:", 25, 248);    doc.text("N° de hojas:", 80, 248);    doc.text("s/:", 135, 248);
  doc.text("Verificación:", 25, 255);    doc.text("s/:", 135, 255);
  doc.text("N° de copias:", 80, 262);    doc.text("s/:", 135, 262);
  doc.text("Observaciones:{oBserva}____________________________________________________________________________________________________________", 25, 269,{align: "justify" , maxWidth: maxWidth-10});

  window.open(doc.output("bloburl"), "_blank");
}

// lineas.forEach((linea) => {
//   doc.text(linea, leftMargin, yPos,{ align: "justify" });
//   yPos += 5; // Ajustar el espaciado entre líneas según sea necesario
// });

function VerificaDatos(){
  if(props.datosSolicitud){
    console.log(props.datosSolicitud);
    generarPDF(props.datosSolicitud)
  }else if(props.datosSolicitudRow){
    console.log(props.datosSolicitudRow);
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
      created_at:props.datosSolicitudRow.created_at,
      testimonio: "",
      copiaCertificada: "",
      copiaSimple: "",
    };
    generarPDF(datosSolici);
  }
}

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
