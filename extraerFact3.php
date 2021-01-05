<?php

$cdata = "";
$ruta = glob("facturas_jose_carlos/*.xml");
// $ruta = "attachment/12755-0810201501179171577200121870010001195345658032311.xml";
// $ruta = "attachment/12639-2209201506099237984700120040010000020700001381812.xml";
// $ruta = "attachments/16931-Factura-20-10-2020-026-100-033310146.xml";
// $ruta = "facturas_clientes/0712202001130658959700120011000000008360712202014.xml";
// $ruta = "facturas_jose_carlos/Factura-23-04-2020-026-100-030069432.xml";
// $ruta = "facturas_proveedores/MEGAPRODUCTOS.xml";
// $ruta = "notaCredito/0207202004130658959700120011000000000010207202010.xml";
// $ruta = "retenciones/0812202007130658959700120011000000003320812202014.xml";
foreach ($ruta as $xml) {
  procesarComprobante($xml);
  echo "<br>/////////////////////////////////////////////////////<br>";
}

function procesarComprobante($ruta){
$path = "";
  if (!isset($ruta)) {
    echo "No has elegido ningun archivo";
    exit;
  }

  $xml = simplexml_load_file($ruta, "SimpleXMLElement", LIBXML_NOCDATA);
  if (isset($xml->xPath("//factura/infoTributaria")[0])) {
    $path = "//factura";
    $rutaXml = $xml->xpath($path)[0];
    $comprobante = json_decode(json_encode($rutaXml), true);
    leerComprobante($comprobante);
  }else{
    $path = "//comprobante";
    $cdata = " con CDATA";
    $rutaXml = $xml->xpath($path)[0];
    $codHTML = htmlentities($rutaXml);
    $decoHTML = html_entity_decode($codHTML);
    $comprobante = json_decode(json_encode(simplexml_load_string($decoHTML)), true);
    leerComprobante($comprobante);
  }
}

function leerComprobante($comprobante){
  // atrapar las claves principales de documentos
  $partesComprobantes = [];
  foreach ($comprobante as $key => $value) {
    $partesComprobantes[] = $key;
  }
  // declarar variables para organizar datos
  $tablaHtml = "<table border=\"1\"><theader><tr><th>CodigoPrincipal</th><th>CodigoAuxiliar</th><th>Descripcion</th><th>Cantidad</th><th>PrecioUnitario</th><th>Descuento</th><th>Subtotal</th></tr></theader>";

  $totalConImpuestos = [];
  $pagos = [];
  $impuestos = [];
  $detAdicional = [];
  $detallesDestinatarios = [];

  echo "Elejistes una " . substr($partesComprobantes[2], 4) . $GLOBALS["cdata"] . "<br>";
  // Si existe un total con impuesto en info del comprobante actual
  if (isset($comprobante[$partesComprobantes[2]]['totalConImpuestos'])) {
    echo "SE ENCONTRO TOTAL CON IMPUESTOS<br>";
    $totalConImpuestos = [$comprobante[$partesComprobantes[2]]['totalConImpuestos']['totalImpuesto']];
    unset($comprobante[$partesComprobantes[2]]['totalConImpuestos']);
  }
  // Si existe algun metodo de pago  en info del comprobante actual
  if (isset($comprobante[$partesComprobantes[2]]['pagos'])) {
    echo "SE ENCONTRARON PAGOS<br>";
    $pagos = [$comprobante[$partesComprobantes[2]]['pagos']['pago']];
    unset($comprobante[$partesComprobantes[2]]['pagos']);
  }
  // Si existe detalle con impuesto en el comprobante actual
  if (isset($comprobante[$partesComprobantes[3]]['detalle'])) {
    $contador = 0;
    if (array_key_exists("0", $comprobante[$partesComprobantes[3]]['detalle'])) {
      echo "SE ENCONTRARON IMPUESTOS DE CADA ITEM<br>";
      for ($i=0; $i < count($comprobante[$partesComprobantes[3]]['detalle']) ; $i++) {
        $impuestos[] = $comprobante[$partesComprobantes[3]]['detalle'][$i]['impuestos']['impuesto'];
        unset($comprobante[$partesComprobantes[3]]['detalle'][$i]['impuestos']);
        // Si existe detalles con detalles adicional en el comprobante actual
        if (isset($comprobante[$partesComprobantes[3]]['detalle'][$i]['detallesAdicionales']['detAdicional'])) {
            $contador++;
            $detAdicional[] = $comprobante[$partesComprobantes[3]]['detalle'][$i]['detallesAdicionales']['detAdicional'];
            unset($comprobante[$partesComprobantes[3]]['detalle'][$i]['detallesAdicionales']);

        }
        // Tabla de detalle de varios items
        $tablaHtml .= "<tr>";
        if (!array_key_exists('codigoPrincipal', $comprobante[$partesComprobantes[3]]['detalle'][$i])) {
          $tablaHtml .= "<td>000000</td>";
        }
        if (!array_key_exists('codigoAuxiliar', $comprobante[$partesComprobantes[3]]['detalle'][$i])) {
          $tablaHtml .= "<td>000000</td>";
        }
        foreach ($comprobante[$partesComprobantes[3]]['detalle'][$i] as $key => $value) {
          $tablaHtml .= "<td>" .$value. "</td>";
        }
        $tablaHtml .= "</tr>";
      }
      echo "SE ENCONTRARON " . $contador . " DETALLES ADICIONALES PARA CADA ITEM<br>";
    }else{
        echo "SE ENCONTRO IMPUESTO DEL ITEM<br>";
        $impuestos = [$comprobante[$partesComprobantes[3]]['detalle']['impuestos']['impuesto']];
        unset($comprobante[$partesComprobantes[3]]['detalle']['impuestos']);
        if (isset($comprobante[$partesComprobantes[3]]['detalle']['detallesAdicionales'])) {
          echo "SE ENCONTRO DETALLES ADICIONALES DEL ITEM<br>";
          $detAddicional[] = $comprobante[$partesComprobantes[3]]['detalle']['detallesAdicionales']['detAdicional'];
          unset($comprobante[$partesComprobantes[3]]['detalle']['detallesAdicionales']);
        }
        // Tabla de detalle de un solo item
        $tablaHtml .= "<tr>";
        if (!array_key_exists('codigoPrincipal', $comprobante[$partesComprobantes[3]]['detalle'])) {
          $tablaHtml .= "<td>000000</td>";
        }
        if (!array_key_exists('codigoAuxiliar', $comprobante[$partesComprobantes[3]]['detalle'])) {
          $tablaHtml .= "<td>000000</td>";
        }
        foreach ($comprobante[$partesComprobantes[3]]['detalle'] as $key => $value) {
          $tablaHtml .= "<td>" .$value. "</td>";
        }
        $tablaHtml .= "</tr>";
    }
  }elseif (isset($comprobante[$partesComprobantes[3]]['destinatario'])) {
    echo "SE ENCONTRARON DESTINATARIOS Y DETALLES DE CADA ITEM<br>";
    $detallesDestinatarios = [$comprobante[$partesComprobantes[3]]['destinatario']['detalles']['detalle']];
    unset($comprobante[$partesComprobantes[3]]['destinatario']['detalles']);
  }else{
    echo "SE ENCONTRARON IMPUESTOS RETENIDOS<br>";
  }
  // INFORMACION ADICIONAL DE TODOS LOS COMPROBANTES
  if (array_key_exists('campoAdicional', $comprobante)) {
    echo "TIENE CAMPOS ADICIONALES<br>";
    $infoAdicional = [$comprobante[$partesComprobantes[4]]['campoAdicional']];
  }
  $tablaHtml .= "</table>";
  echo $tablaHtml;
  echo "<pre>";
  print_r($comprobante['detalles']['detalle']);
  // echo "<br>Mezcla de arrays:<br><br>";
  // print_r($detallesDestinatarios);


}
















 ?>
