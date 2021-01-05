<?php

// Probe este scrip para poder agregar datos en cualquier lugar de un array asoc
  $array = ["codigoPrincipal" => "1234", "descripcion" => "Jugos Ya", "cantidad" => "10",
            "precioUnitario" => "1.32", "descuento" => "0", "precioTotalSinImpuesto" => "13.20"];

  $array2 = array();

   if (empty($array['codigoAuxiliar'])) {

     foreach ($array as $key => $value) {

       $array2 += [$key => $value];
       // echo $key . ": " .$value ."</br>";

       if ($key == "codigoPrincipal") {
         // code...
         $array2 += ["codigoAuxiliar" => "0"];
       }
     }
     echo "se agrego";
     // $array2 = next($array);
   }else{
     $array2 = $array;
   }

 // array_keys($array);
  // asort($array);
  echo "<pre>";
  print_r($array2);





 ?>
