
<?php


$doc='fact/FactESPAM_23042020.xml';

$xml = simplexml_load_file($doc, 'SimpleXMLElement', LIBXML_NOCDATA);
$xmlJson = json_encode($xml);
$xmlArr = json_decode($xmlJson, true); // Returns associative array

var_dump($xmlJson);
echo "<br><br><p align=\"center\">--------------------------------------------------------------------------------------------------------</p><br>";
echo "<h3 align=\"center\">OTRA INSTRUCCION</h3><br><br>";
foreach ($xmlArr as $fact) {
  // code...
  echo "<pre>";
  print_r($fact);
  echo "</pre>";
}

echo "<br><br>";


/*$entities="<b>Agamenón</b>";
$ent=htmlspecialchars(htmlentities($entities));
echo $ent;
echo "<br>------------<br>";

$ent=html_entity_decode($ent);
echo $ent;
echo "<br>------------<br>"; */


?>
