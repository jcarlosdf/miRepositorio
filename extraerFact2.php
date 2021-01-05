<?php
include "extraerGuiaRemision.php";

// $filename = "libros.xls";
// header("Content-Type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; filename=".$filename);
// $rutta = "facturas_proveedores/freddyFALCONI.xml";
// $rutta = "facturas_jose_carlos/Factura 001-100-000000018.xml";
// $rutta="attachment/12591-0809201506099237984700120040010000019340001290513.xml";
// $rutta="attachment/11088-0602201506099237984700120040010000000670000123813.xml";
// $rutta = "../fact/FactESPAM_23042020.xml";



echo "\n";
 // PREG_SPLIT_OFFSET_CAPTURE

function nombreKey($nomb){
  // $nomb = "asientoContableImprimibleActualValorizado ";
  $nombNuevo = "";
  $err = str_split($nomb);
  $countu=0;
  foreach ($err as $key => $value) {

    if (ctype_upper($value)) {
      $countu++;
      // echo " ";
      $nombNuevo .= " ";
    }
    // echo strtoupper($value);
    $nombNuevo .= strtolower($value);
  }

  return   "<span style=\"font-family:arial;size:12pt;font-weight:bold;\">" . str_pad(ucfirst($nombNuevo . " : "), 45, "-") . "</span>";
  // echo str_pad($nombNuevo, 10);
  // echo "\nse encontraron <b>" .$countu. " </b>mayusculas";
  // $er = preg_split("/[[:upper:]]/", $nomb);
  // echo ucwords(implode(" ", $er));
  // echo "\n\n";
  // print_r($err);

}



// comprobanteGuiaRemision($rutta);
// comprobanteFactura_1($rutta);
// comprobanteFactura_2($rutta);

function comprobanteFactura_1($ruta){
  $cargarXml = file_get_contents($ruta);
  $datos = new SimpleXMLElement($cargarXml, LIBXML_NOCDATA);
  $comprobante = $datos->xpath("//comprobante")[0];
  $codHTML = htmlentities($comprobante);
  $decoHTML = html_entity_decode($codHTML);
  $factura = json_decode(json_encode(simplexml_load_string($decoHTML)),true);

  procesarFactura($factura);

  // echo "<pre>";
  // print_r($factura);
}

function comprobanteFactura_2($ruta){
  $cargarXml = simplexml_load_file($ruta);
  $infoXml = $cargarXml->xpath("//factura")[0];
  $factura = json_decode(json_encode($infoXml),true);

  procesarFactura($factura);
  // echo "<pre>";
  // print_r($factura);
}

function leerComprobantes($comprobante){


  //infoTributaria
    echo "<strong>INFO TRIBUTARIA</strong><br>";
    foreach ($comprobante['infoTributaria'] as $key => $value) {
      echo nombreKey($key) . " " . $value . "<br>";
    }
    echo "<br>";

  //infoFactura
    echo "<strong>INFO FACTURA</strong><br>";
    foreach ($comprobante['infoFactura'] as $key => $value) {

      if ($key == "totalConImpuestos") {
        echo "<br><strong>TOTAL CON IMPUESTOS</strong><br>";
        totalImpuestos($comprobante);
        echo "<br>";
      }elseif ($key == "pagos") {
        echo "<br><strong>PAGOS</strong>";
        pagos($comprobante);
        echo "<br>";
      }else{
        echo nombreKey($key) . " " . $value . "<br>";
       }
    }
    echo "<br>";

  //Detalle Factura
    detalles($comprobante);

}

function totalImpuestos($factura){
  $contador=1;
  $taxes=$factura['infoFactura']['totalConImpuestos']['totalImpuesto'];
  echo "<strong>IMPUESTOS:</strong>";
   if (array_key_exists("0",$taxes)) {
     for ($i=0; $i < count($taxes) ; $i++) {
       echo "<br><strong>" . $contador++ ."</strong>)";
       foreach ($taxes[$i] as $key => $value) {
         echo "<br>" . $key . ": " .$value;
       }
         echo "<br>";
     }
   }else{
     foreach ($taxes as $key => $value) {
       echo "<br>" . $key . ": " .$value;
     }
      echo "<br>";
   }

}

function pagos($factura){
  $contador=1;
  $pays=$factura['infoFactura']['pagos']['pago'];
  echo "<br><strong>FORMAS DE PAGOS:</strong>";
  if (array_key_exists("0",$pays)) {
    for ($i=0; $i < count($pays) ; $i++) {
      echo "<br><strong>" . $contador++ ."</strong>)";
      foreach ($pays[$i] as $key => $value) {
        echo "<br>" . $key . ": " .$value;
      }
        echo "<br>";
    }
  }else{
    foreach ($pays as $key => $value) {
      echo "<br>" . $key . ": " .$value;
    }
  }

}

function detalles($factura){
  $contador=1;
  $detalles=$factura['detalles']['detalle'];
  echo "<strong>DETALLES:</strong>";
   if (array_key_exists("0", $detalles)) {
     for ($i=0; $i < count($detalles) ; $i++) {
       echo "<br><strong>" . $contador++ ."</strong>)";
       foreach ($detalles[$i] as $key => $value) {
         if ($key == "impuestos") {
           echo "<br><strong>IMPUESTO:</strong>";
           $impuesto=$factura['detalles']['detalle'][$i]['impuestos']['impuesto'];
           foreach ($impuesto as $key => $value) {
             echo "<br>" . $key . ": " .$value;
            }
         }elseif($key == "detallesAdicionales"){
           detAdicional($factura,$i);
         }else{
           echo "<br>" . $key . ": " .$value;
         }
       }
         echo "<br>";
     }

   }else{
     foreach ($detalles as $key => $value) {
       if ($key == "impuestos") {
         echo "<br><strong>IMPUESTO:</strong>";
         $impuesto=$factura['detalles']['detalle']['impuestos']['impuesto'];
         foreach ($impuesto as $key => $value) {
           echo "<br>" . $key . ": " .$value;
         }
       }elseif($key == "detallesAdicionales"){
         echo "<br><strong>DET ADICIONAL:</strong>";
         $detAddicional=$factura['detalles']['detalle']['detallesAdicionales']['detAdicional']['@attributes'];
         foreach ($detAddicional as $key => $value) {
           echo "<br>" . $key . ": " .$value;
         }
       }else{
         echo "<br>" . nombreKey($key) . ": " .$value;
       }
     }
      echo "<br>";
   }
}

function detAdicional($factura,$i){
  echo "<br><strong>DETALLE ADICIONAL:</strong>";
  $detAddicional=$factura['detalles']['detalle'][$i]['detallesAdicionales']['detAdicional'];
  if (array_key_exists("0",$detAddicional)) {
    for ($i=0; $i < count($detAddicional) ; $i++) {
      echo "<br>";
      foreach ($detAddicional[$i]['@attributes'] as $key => $value) {
        echo "<br>" . $key . ": " .$value;
      }
    }
  }else{

    foreach ($detAddicional['@attributes'] as $key => $value) {
      echo "<br>" . $key . ": " .$value;
    }
  }

}
