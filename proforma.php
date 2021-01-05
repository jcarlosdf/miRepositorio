<?php

$fact=simplexml_load_file("fact/factextra.xml", null
    , LIBXML_NOCDATA) or die("NO SE PUDO CARGAR XML");
$products=array();
$items=array();
//$t=array("a"=>"aeiou","b"=>"avion","c"=>"tren","d"=>"autobus","e"=>"barco");
$t=array("a","b","c","d","e");

$t[0]=array("avion"=>"boeing737","animal"=>array("gato","perro","pato","pollo"),"cosa"=>"florero");

//echo  $fact->getName() . "<br>";

foreach ($fact->Children() as $detail) {
  // code...
  //$productos=$detalles->getName();
  $productos=$detail;
  $items[]=$detail->count();
  $products[]=$productos;



  //echo $detail->getName() . "<br>";
  //print_r($detail). "<br><br>";
  }

//print_r($products);

//echo "<br>------------<br>";
//var_dump($products);
//print_r($items);
//echo "<br>------------<br>";
$table='<table><tr><th>CODIGO PRODUCTO INEC</th><th>CODIGO PRODUCTO</th>';
$table.='<th>DESCRIPCION PRODUCTO</th><th>INFORMACION ADICIONAL</th>';
$table.='<th>PRECIO UNITARIO</th><th>CODIGO ICE</th><th>CODIGO IVA</th></tr>';

for ($i=0; $i <$items[2] ; $i++) {

  // VER DATOS EN EL NAVEGADOR
  //$x = $i + 1 ;
  //echo $x .". " . $products[2][0]->detalle[$i]->descripcion . "<br>";

  // FORMATO PARA EXPORTAR ESTOS DATOS A EXCEL
  //echo utf8_decode($products[2][0]->detalle[$i]->descripcion) . "\n";

  //TABLA HTML PARA EXPORTAR A excel
  $codINEC="0";
  $codProd=$products[2][0]->detalle[$i]->codigoPrincipal;
  $Prod=$products[2][0]->detalle[$i]->descripcion;
  $info="";
  $Precio=(float)$products[2][0]->detalle[$i]->precioUnitario;
  $codICE=0;
  $codIVA=2;


  $table.='<tr align="CENTER"><td>'.$codINEC.'</td>';
  $table.='<td>'.$codProd.'</td>';
  $table.='<td>'.(string)$Prod.'</td>';
  $table.='<td>'.$info.'</td>';
  $table.='<td>'. number_format($Precio,2,'.','').'</td>';
  $table.='<td>'.$codICE.'</td>';
  $table.='<td>'.$codIVA.'</td></tr>';

}
$table.='</table>';



//EXPORTA LOS DATOS A excel
//primero asignamos la fecha en una variable y le concatenamos el prefijo y sufijo
$timestamp= time();
$filename= 'Export_' . $timestamp . '.xls';
//Entonces exportamos con header y le damos el nombre del archivo

header("Content-type: application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
//se debe colocar el output de datos luego de los getallheaders
// si no se hace esto el echo se imprimira en pantalla y los datos no se exportaran
echo utf8_decode($table);
//echo $t[0]["animal"][3]  ."<br>"

//foreach ($t as $key => $value) {
//  echo $key . " igual a " .$value . "<br>";
  // code...

//}

// PARA PARSEAR UN XML CON CDATA
/*$doc = new DOMDocument();
$doc->load('test.xml');
$destinations = $doc->getElementsByTagName("Destination");
foreach ($destinations as $destination) {
    foreach($destination->childNodes as $child) {
        if ($child->nodeType == XML_CDATA_SECTION_NODE) {
            echo $child->textContent . "<br/>";
        }
    }
} */

//OTRAS OPCIONES
//$parseFile = simplexml_load_file($myXML,'SimpleXMLElement', LIBXML_NOCDATA);
// ...foreach ($parseFile->yourNode as $node ){etc...}

//BEST WAY AND EASY
//$xml = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
//$xmlJson = json_encode($xml);
//$xmlArr = json_decode($xmlJson, 1); // Returns associative array

// Use replace CDATA before parsing PHP DOM element after that you can get the innerXml or innerHtml:
// str_replace(array('<\![CDATA[',']]>'), '', $xml);

//but also convert xml object to php associative array. So we can apply loop on the data.
// $xml_file_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA),true), true);



 ?>
