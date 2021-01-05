<?php


$ruta="../fact/duplicado_factura-4204.xml";

$xml = simplexml_load_file($ruta)or die ("Error: Cannot create object");

//echo var_dump($xml);
//cho "<br><br>" . "--------------------" ."<br><br>";
//echo $xml->getDocNameSpaces(true, true) ."<br><br>";
//$ns=$xml->getNameSpaces(true);
$nombre="";
echo "<br>";
echo $xml->getName() . "<br><br>";

$tags=array();
$hijos=array();

$FECHAE="";
$DIREST="";
$OBLIG="";
$IDCOMPR="";
$RAZONS="";
$IDCLIENT="";
$DIRCLIENT="";
$TOTALSINIVA="";
$DESCUENT="";
$base="";
foreach ($xml->children() as $child){
  //for($i=0;$i<$val;$i++){

  //echo "RAZON social: " . $xml->Materiales[$i]->Codigo . "<br>";
    //echo "CODIGO: " . $child->Codigo . "<br>";
    //echo "PRODUCTO: " . $child->Producto . "<br>";
    //echo "PRECIO: " . $child->Precio . "<br><br>";
 //$table.="<tr><td>" .$child->Codigo. "</td>";
 //$table.="<td>" .$child->Producto. "</td>";
 //$table.="<td><CENTER>" .$child->Precio. "</CENTER></td></tr>";
  //}


  $nombre=$child->getName();
  $tags[]=$nombre;
    //foreach($xml->$nombre->Children() as $subnombres){
      for($x=0;$x<1;$x++){

        $hijos[]=$nombre;

        for($y=0;$y<$xml->$nombre->Children()->count();$y++){


          $hijos[$x][$y]=$nombre;
        }
      }
  //}
  $FECHAE.=$child->fechaEmision;
  $DIREST.=$child->dirEstablecimiento;
  $OBLIG.=$child->obligadoContabilidad;
  $IDCOMPR.=$child->tipoIdentificacionComprador;
  $RAZONS.=$child->razonSocialComprador;
  $IDCLIENT.=$child->identificacionComprador;
  $DIRCLIENT.=$child->direccionComprador;
  $TOTALSINIVA.=$child->totalSinImpuestos;
  $DESCUENT.=$child->totalDescuento;
  if($IDCOMPR=="04"){
    $IDCOMPR="FACTURA";
  }
/*hematuria
rectoragia
otoragia*/

}
$table="<table border=\"1\"><tr><th>FECHA DE EMISION</th><td>". $FECHAE ."</td></tr>";
$table.="<tr><th>DIR ESTABLECIMIENTO</th><td>". $DIREST ."</td></tr>";
$table.="<tr><th>OBLIGADO A CONTABILIDAD</th><td>". $OBLIG ."</td></tr>";
$table.="<tr><th>TIPO COMPROBANTE</th><td>". $IDCOMPR ."</td></tr>";
$table.="<tr><th>RAZON SOCIAL</th><td>". $RAZONS ."</td></tr>";
$table.="<tr><th>IDENTIFICACION COMPRADOR</th><td>". $IDCLIENT ."</td></tr>";
$table.="<tr><th>DIRECCION COMPRADOR</th><td>". $DIRCLIENT ."</td></tr>";
$table.="<tr><th>TOTAL SIN IMPUESTOS</th><td>". number_format($TOTALSINIVA,2,".",",") ."</td></tr>";
$table.="<tr><th>TOTAL DESCUENTO</th><td>". number_format($DESCUENT,2,".",",") ."</td></tr>";
$table.="</table>";
echo $table;


//echo "<br>"."<b>CODIGO:</b> " . $xml->children()[1]->fechaEmision ."<br>";
//echo "<b>DESCRIPCION:</b> " . $xml->children()[0]->Producto . "<br>";
//echo "<b>PRECIO:</b> " . $xml->children()[0]->Precio . "<br>";

//echo "<br><br>";
//echo $xml->getName() . "<br><br>";
//echo $xml->count() . "<br><br>";

//print_r($xml) ;
echo $nombre . "<br>";
echo $xml->children()[1]->totalConImpuestos->totalImpuesto->baseImponible . "<br>";
echo $xml->infoTributaria->Children()->getName() . "<br>";

echo $tags[0]. "<br>";
print_r($tags);
echo "<br>------------------<br>";
print_r($hijos);
echo "<br>------------------<br>";
echo $xml . "<br>";
echo $xml->infoTributaria->Children()->count(). "<br>";


 ?>
