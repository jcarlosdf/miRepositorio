<?php

//'fact\factura_001-100-000004204.xml'
//'fact\FactESPAM_23042020.xml'
//'fact\duplicado_factura-4204.xml'
//'fact\factextra.xml'
// echo "<pre>";
// var_dump($lote=glob("attachment/*.xml"));

//print_r($lote);
// $facts=count($lote);
// foreach ($lote as $xmls) {
//
//   // obtenerDetalleXml($xmls);
// echo $xmls . "<br>";
// }
// echo "<br>";
// echo '<b style="font-size:14pt;">'.$facts." </b>documentos";
// echo "<br><br>";

// $file=file_get_contents("attachment/10877-0801201501099237984700120040010000000080000009513.xml");
// $jsonencode = json_encode($file,JSON_PRETTY_PRINT);
// $jsondecode = json_decode($jsonencode, true);
// echo "<br>";
// echo $jsondecode[3];
// print_r($jsondecode);
$remp = array
(
  '<?xml version="1.0"?>',
  '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">',
  '<soap:Body>',
  '<ns2:autorizacionComprobanteResponse xmlns:ns2="http://ec.gob.sri.ws.autorizacion">',
  '</ns2:autorizacionComprobanteResponse>',
  '</soap:Body>',
  '</soap:Envelope>'
);
// $rempl = str_replace($remp,'',$file);
// $fact = simplexml_load_string($file);

// $rutta = "facturas_proveedores\CHINGA.xml";
// $rutta = "facturas_proveedores\juanELJURI.xml";
$rutta="attachment/10927-0801201506099237984700120040010000000070000009511.xml";
// $rutta="attachment/11088-0602201506099237984700120040010000000670000123813.xml";
// $rutta = "../fact/FactESPAM_23042020.xml";

// print_r($nspaces);
// echo "<br><br>";
// var_dump($hok);
// echo "<br><br>";
// print_r($nspaces);
// echo "<br><br>";

// obtenerDetalleXmlEnvelope($rutta);
obtenerDetalleXmlAutorizacion($rutta);


 function verStructuraXml($archivoo){
   $cargarXml = simplexml_load_file($archivoo);
   // $f = htmlentities($cargarXml);
   // $y = html_entity_decode($f);
   $cargarXmll=$cargarXml->xpath("//detalle/*");
   print_r($cargarXmll);

   // $children = json_decode(json_encode($nuevoXml=simplexml_load_string($cargarXml),true),true);
   echo "<pre>";
   // print_r($children);
 }

 function obtenerDetalleXmlEnvelope($comprobante) {

   // $yy = file_get_contents($comprobante);

  $cargarXml = simplexml_load_file($comprobante);
  echo $cargarXml->getName();
  echo "<br>";
  $nspaces=$cargarXml->getDOCNamespaces(true,true);
  // print_r($nspaces);
  $cargarXml->registerXPathNamespace('a',$nspaces['ns2']);
// // $detalles = $cargarXml->xpath('//a:autorizacionComprobanteResponse/*')[0];
$ejemplo = $cargarXml->xpath('//a:autorizacionComprobanteResponse/RespuestaAutorizacionComprobante/autorizaciones/autorizacion/comprobante')[0];
// $ejemplo = $cargarXml->xpath('//a:autorizacionComprobanteResponse//a:RespuestaAutorizacionComprobante');
// $quebrado = explode(" ",$ejemplo);
// $bremplaza = str_replace('<factura>','',$ejemplo);
// ['factura']['infoTributaria']['ambiente']

// echo $ejemplo->getName();
// echo "<br><br>";

$f = htmlentities($ejemplo);
$y = html_entity_decode($f);
// $nuevoXml=simplexml_load_string($y);
$children = json_decode(json_encode(simplexml_load_string($y),true),true);

echo "<b>infoTributaria</b></br>";
foreach ($children['infoTributaria'] as $key => $value) {
  // code...
  echo $key . " " .$value;
  echo "<br>";
}
echo "<br>";
echo "<b>infoFactura</b></br>";
foreach ($children['infoFactura'] as $key => $value) {
  // code...

  if ($key == "totalConImpuestos") {
    echo "totalConImpuestos<pre>totalImpuesto<br>" ;
    foreach ($children['infoFactura']['totalConImpuestos']['totalImpuesto'] as $llave => $valll) {
      echo "  ".$llave. " " .$valll;
      echo "<br>";
    }
    echo "</pre>";
    }else{
      echo $key ." " . $value;
      echo "<br>";
    }
    continue;

}


// foreach ($children as $nietos => $vals) {
//   echo $vals;
//   echo "<pre>";
//   // echo $vals ." ";
// }
echo "<br>";
echo "<pre>";
print_r($children);

// foreach ($ejemplo->xpath("//detalle/*") as $hijos => $valores) {
//
//   echo $hijos . " " .$valores;
//
//   // echo $hijos;
//   // echo "<br><br>";
//   echo "<br>";
// }
// print_r($ejemplo);
// echo $ejemplo;
echo "<br>--------------------------------------------------<br>";
}
// print_r($quebrado);
// echo $ejemplo[0] . "<br><br>";

// foreach ($cargarXml->xpath('//a:autorizacionComprobanteResponse/RespuestaAutorizacionComprobante/autorizaciones/autorizacion')[0] as $value) {
//
//   echo "<br>";
//   echo $value->estado;
//
// }

function obtenerDetalleXmlAutorizacion($ruta){
  $xml=file_get_contents($ruta);
  $datos = new SimpleXMLElement($xml,LIBXML_NOCDATA);
  $x=$datos->xpath("//comprobante")[0];

  $a = htmlentities($x);
  $b = html_entity_decode($a);

  $transf = json_decode(json_encode(simplexml_load_string($b)),true);
  // echo $transf['infoTributaria']['nombreComercial']."<br>";
  // echo($x);
  echo "<br>";
  echo "<b>infoTributaria</b><br>";
  foreach ($transf['infoTributaria'] as $key => $value) {
    echo $key . " : " .$value;
    echo "<br>";
  }
  echo "<br><b>infoFactura</b><br>";
  $totales=count($transf['infoFactura']['totalConImpuestos']['totalImpuesto']);
  // echo "<br>" . $totales . "<br>";
  $arraaay=array_keys($transf['infoFactura']['totalConImpuestos']['totalImpuesto']);
  // print_r($arraaay);
  // echo "<br><br>";
  foreach ($transf['infoFactura'] as $key => $value) {
    if ($key == "totalConImpuestos") {
      echo "<b>totalConImpuestos</b><br>totalImpuesto<pre>";
      if (in_array("0",$arraaay)) {
        for ($i=0; $i <count($transf['infoFactura']['totalConImpuestos']['totalImpuesto']) ; $i++) {
          foreach ($transf['infoFactura']['totalConImpuestos']['totalImpuesto'][$i] as $key => $value) {
            echo $key . " : " . $value;
            echo "<br>";
        }
          echo "<br>";
        }
      }else{
        foreach ($transf['infoFactura']['totalConImpuestos']['totalImpuesto'] as $key => $value) {
          echo $key . " : " . $value;
          echo "<br>";
      } }
      echo "</pre>";
    }else{
      echo $key ." : " . $value;
      echo "<br>";
    }
  }
  $totalDetalles=count($transf['detalles']['detalle']);
  $arrayValus=array_keys($transf['detalles']['detalle']);
  $contador=1;
  echo "<br><b>detalles</b>";

  // print_r($arrayValus);
   echo "<br>";

  if (in_array("0",$arrayValus)) {
    echo   "<strong>".$totalDetalles . " </strong>Detalles:<br><br>";
    for ($i=0; $i < count($transf['detalles']['detalle']); $i++) {
      echo "<b>detalle ".$contador++."</b><br>";
      foreach ($transf['detalles']['detalle'][$i] as $key => $value) {
        if ($key == "detallesAdicionales" || $key == "impuestos") {
          // echo "<b>totalConImpuestos</b><br>totalImpuesto<pre>";
          // foreach ($transf['detalles']['detalle']['detallesAdicionales']['detAdicional']as $key => $value) {
          //     echo $key . " " . $value;
          //     echo "<br>";
          // }
          break;
        }else{
          echo $key . " : " . $value;
          echo "<br>";
        } // fin del else
      } // fin del foreach
  } // fin del for
      // if (is_array($transf['detalles']['detalle'])) {
        // code...
  } //fin del if

  else{
    foreach ($transf['detalles']['detalle'] as $key => $value) {
      if ($key == "detallesAdicionales" || $key == "impuestos") {
        break;
    }else{
      echo $key . " : " . $value;
      echo "<br>";

    }
       }

  }
    echo "<br>";
    // echo print_r($transf['detalles']['detalle'][$i]);
    //     echo "<br>";

    echo "<pre>";
    print_r($transf);

  } // function end










// $datosXml = json_decode(json_encode(simplexml_load_string($string,'SimpleXMLElement',LIBXML_NOCDATA),true),true);



/*

//echo $file;
$repley=str_replace(array('<![CDATA[',']]>','<?xml version="1.0" encoding="UTF-8"?>'),'', $file);
//echo $repley;

$detail=array();
$xmlSt=simplexml_load_string($repley);

if($xmlSt->getName() == "Autorizacion"){
  foreach ($xmlSt->comprobante->factura->detalles->detalle as $hijas) {
    echo $hijas->descripcion . '<br>';
  }
}elseif($xmlSt->getName() == "factura"){
  foreach ($xmlSt->detalles->detalle as $hijas) {
    echo $hijas->descripcion . '<br>';
  }
}
echo "<br><hr><br>";
}
 //print_r($hijos);
echo 'Se encontraron un total de <strong style="font-size:16pt;">' . $facts . '</strong> comprobantes de Facturas';

 /*echo "<br><hr>";
 var_dump($detail);
 echo "<br><hr><br>"; */

 //echo $detail[0];

 /* function listarArchivos( $path ){
     // Abrimos la carpeta que nos pasan como parámetro
     $dir = opendir($path);
     // Leo todos los ficheros de la carpeta
     while ($elemento = readdir($dir)){
         // Tratamos los elementos . y .. que tienen todas las carpetas
         if( $elemento != "." && $elemento != ".."){
             // Si es una carpeta
             if( is_dir($path.$elemento) ){
                 // Muestro la carpeta
                 echo "<p><strong>CARPETA: ". $elemento ."</strong></p>";
             // Si es un fichero
             } else {
                 // Muestro el fichero
                 echo "<br />". $elemento;
             }
         }
     }
 }
 // Llamamos a la función para que nos muestre el contenido de la carpeta gallery
 listarArchivos("fact/"); */
