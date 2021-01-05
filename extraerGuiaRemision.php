<?php

function comprobanteGuiaRemision($ruta){
  $cargarXml=file_get_contents($ruta);
  $datos = new SimpleXMLElement($cargarXml,LIBXML_NOCDATA);
  $comprobante=$datos->xpath("//comprobante")[0];
  $codHTML = htmlentities($comprobante);
  $decoHTML = html_entity_decode($codHTML);
  $guiaR = json_decode(json_encode(simplexml_load_string($decoHTML)),true);

  procesarGuiaRemision($guiaR);
  // echo "<pre>";
  // print_r($guiaR);
}

function procesarGuiaRemision($comprobante){
  //infoTributaria
    echo "<strong>INFO TRIBUTATIA</strong><br>";
    foreach ($comprobante['infoTributaria'] as $key => $value) {
      echo $key . ": " . $value . "<br>";
    }
    echo "<br>";

  //infoGuiaRemision
    echo "<strong>INFO GUIA REMISION</strong><br>";
    foreach ($comprobante['infoGuiaRemision'] as $key => $value) {
        echo $key . ": " . $value . "<br>";
    }
    echo "<br>";

  //Detalle destinatario
    echo "<strong>DESTINATARIO</strong><br>";
    detalleGuiaRemision($comprobante);

    // echo "<br>";

}

function detalleGuiaRemision($comprobante){
  foreach ($comprobante['destinatarios']['destinatario'] as $destinatario => $value) {
    if ($destinatario == "detalles") {
      echo "<br><strong>DETALLES</strong><br>";
      detallesGR($comprobante);
    }else{

      echo $destinatario . ": " . $value . "<br>";
    }
  }
}

function detallesGR($destinatario){
  $contador = 1;
  $detalle = $destinatario['destinatarios']['destinatario']["detalles"]["detalle"];
  if (array_key_exists("0", $detalle)) {
    for ($i=0; $i < count($detalle) ; $i++) {
      echo "<br><strong>DETALLE " . $contador++ . ":</strong><br>";
      foreach ($detalle[$i] as $detalles => $value) {
        if ($detalles == "detallesAdicionales") {
          echo "<br><strong>DETALLE ADICIONAL</strong><br>";
          detAdicionalGR($destinatario, $contador, $i);
        }else{
          echo $detalles . ": " . $value . "<br>";
        }
      }
      echo "<br>";
    }
  }else{
    foreach ($detalle as $detalles => $value) {
      if ($detalles == "detallesAdicionales") {
        echo "<br><strong>DETALLE ADICIONAL</strong><br>";
        detAdicionalGR($destinatario);
      }else{
        echo $detalles . ": " . $value . "<br>";
      }
    }
  }
}

function detAdicionalGR($destinatario, $cantidad,$i){
  if ($cantidad = 1) {
    $detAdicional = $destinatario['destinatarios']['destinatario']["detalles"]["detalle"][$i]['detallesAdicionales']['detAdicional'];
    if (array_key_exists("0", $detAdicional)) {
      for ($i=0; $i < count($detAdicional); $i++) {
        foreach ($detAdicional[$i]['@attributes'] as $key => $value) {
          echo $key . ": " . $value . "<br>";
        }
      echo "<br>";
      }
    }else{
      foreach ($detAdicional as $key => $value) {
        echo $key . ": " . $value . "<br>";
      }
    }
  }else{
    $detAdicional = $destinatario['destinatarios']['destinatario']["detalles"]["detalle"]['detallesAdicionales']['detAdicional'];
    if (array_key_exists("0", $detAdicional)) {
      for ($i=0; $i < count($detAdicional); $i++) {
        foreach ($detAdicional[$i] as $key => $value) {
          echo $key . ": " . $value . "<br>";
        }
      }
    }else{
      foreach ($detAdicional as $key => $value) {
        echo $key . ": " . $value . "<br>";
      }
    }

  }

}
