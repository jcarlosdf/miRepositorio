<?php

$ruta = glob("attachment/*.xml");


foreach ($ruta as $xml) {
	echo $xml ."</br>";
  leerComprobantes($xml);
  echo "<br><br><hr><br><br>";
}





// $ruta = "attachment/13159-2412201501179171577200121870010001813405658032312.xml";
// leerComprobantes($ruta);
function leerComprobantes($ruta){
$path = "//factura"; // inicialmente el path del comprobante se asume como una factura por defecto
$comprobante = []; // Almacena el comprobante en un array asoc luego de ser depurado

// Cargamos el comprobante y asignamos el path correcto en caso de que no se trate de una factura electrónica
// $xmlComp = simplexml_load_file($ruta, "SIMPLEXMLElement", LIBXML_NOCDATA, "soap", true); //or die("ocurio un error al momento de cargar el comprobante");
$xmlComp = simplexml_load_file($ruta, "SimpleXMLElement");
 //or die("ocurio un error al momento de cargar el comprobante");
if(isset($xmlComp->xpath("//notaCredito/infoTributaria")[0])){
	$path = "//notaCredito";
}
if(isset($xmlComp->xpath("//notaDebito/infoTributaria")[0])){
	$path = "//notaDebito";
}
if(isset($xmlComp->xpath("//guiaRemision/infoTributaria")[0])){
	$path = "//guiaRemision";
}
if(isset($xmlComp->xpath("//comprobanteRetencion/infoTributaria")[0])){
	$path = "//compRetencion";
}


// $atributosAdicionales = $xmlComp->xpath("//comprobante//guiaRemision/destinatarios/destinatario/detalles/detalle[0]/detallesAdicionales/detAdicional");
// $attr = $xmlComp->comprobante->guiaRemision->destinatarios->destinatario->detalles->detalle[0]->detallesAdicionales->detAdicional->attributes();
// $attr = $atributosAdicionales->attributes();

// Convertimos los datos a un array asoc.
if(isset($xmlComp->xpath("//infoTributaria")[0])) :

	$fact = $xmlComp->xpath($path)[0];
	$comprobante = json_decode(json_encode($fact), true);
	echo "se proceso con exito</br></br>";

// se usa cuando el comprobante esta como CDATA, es decir no pasa por el analizador de caracteres
elseif(isset($xmlComp->xpath("//comprobante")[0])) :
    echo "el comprobante tiene cdata</br>";
    $path = "//comprobante";
    $fact = $xmlComp->xpath($path)[0]; // devuelve un array
    $htmlencode = htmlentities($fact); // devuelve string
	$htmldecode = html_entity_decode($htmlencode); // devuelve string
  	$comprobante = json_decode(json_encode(simplexml_load_string(trim(str_replace(array("<![CDATA[", "]]>"), "", $htmldecode)))), true);

	// $xml = simplexml_load_file($ruta, "SimpleXMLElement", LIBXML_NOCDATA);
	// $json = json_encode($xml);
	// $array = json_decode($json, TRUE);
	// // return $array;
	// $array = simplexml_load_string($array["comprobante"]);
	// $json = json_encode($array);
	// $array = json_decode($json, TRUE);

    echo "se proceso con exito</br></br>";
else :
	echo "No es un comprobante electrónico valido";
	// exit;
endif;


// echo "<pre>";
// print_r($comprobante);

if (isset($comprobante['infoTributaria'])) {
	// code...


// Almacena las partes principales que componen el comprobante
$partesComp = []; // partes principales del comprobante
$tipoComp = ""; // Almacena el tipo de comprobante

foreach($comprobante as $principal =>$v){
	$partesComp[] = $principal;
	if($principal == "infoFactura")$tipoComp = "factura";
	if($principal == "infoCompRetencion")$tipoComp = "retencion";
	if($principal == "infoNotaCredito")$tipoComp = "nota de credito";
	if($principal == "infoNotaDebito")$tipoComp = "nota de debito";
	if($principal == "infoGuiaRemision")$tipoComp = "guia de remision";
}
// Almacena algunas de las subpartes del comprobante
$subpartes = "";
foreach($comprobante[$partesComp[3]] as $sub => $val){
	$subpartes = $sub;
}
// print_r($partesComp);
// echo $subpartes;

// Guarda todos los detalles
$detalles = $comprobante[$partesComp[3]][$subpartes];
$items = array();
$ContadorDetAdicionales = 0;

if($tipoComp == "guia de remision"){
	$detalles = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle'];
}
// echo "<pre>";
// print_r($detalles);
// exit;
if ($tipoComp == "factura") {
	echo "Razon Social: " . $comprobante[$partesComp[1]]['razonSocial'] . "</br>";
	echo "Fecha de emision: " . $comprobante[$partesComp[2]]['fechaEmision'] . "</br>";
	echo "Cliente: " . $comprobante[$partesComp[2]]['razonSocialComprador'] . "</br>";
}
// Crea la tabla de detalles
$table = "<table border=\"1\"><theader>";

// Comprueba si en los detalles existe más de un item
if(array_key_exists("0", $detalles)){
	echo "<br>La {$tipoComp} posee varios items</br>";



	// function ordenarDetalles($comprobante, $detalles, $tipoComp, $partesComp, $subpartes, $items){


		for($i = 0; $i < sizeof($detalles); $i++){
			// Guarda el impuesto de cada items


			if(isset($detalles[$i]['detallesAdicionales'])){
				// $detAdicional;
				$detallesAdicionales = array();
				if(array_key_exists("0", $detalles[$i]['detallesAdicionales']['detAdicional'])){
					if($tipoComp == "guia de remision"){
						$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle'][$i]['detallesAdicionales']['detAdicional'];
						$ContadorDetAdicionales = count($detAdicionalContador);
						for($j = 0; $j < $ContadorDetAdicionales; $j++){
							$detAdicional = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle'][$i]['detallesAdicionales']['detAdicional'][$j]['@attributes'];
							$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
							// print_r($detallesAdicionales);
						}

					}else{

						$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes][$i]['detallesAdicionales']['detAdicional'];
						$ContadorDetAdicionales = count($detAdicionalContador);
						for($j = 0; $j < $ContadorDetAdicionales; $j++){
							$detAdicional = $comprobante[$partesComp[3]][$subpartes][$i]['detallesAdicionales']['detAdicional'][$j]['@attributes'];
							$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
						}

					}

				}else{
					if($tipoComp == "guia de remision"){
						$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle'][$i]['detallesAdicionales']['detAdicional']['@attributes'];
						$ContadorDetAdicionales = count($detAdicionalContador);
						for($j = 0; $j < $ContadorDetAdicionales; $j++){
							$detAdicional = $comprobante[$partesComp[3]][$subpartes][0]['detallesAdicionales']['detAdicional'][$j]['@attributes'];
							// print_r($detalleAdicional);
							$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
						}
					}else{
						$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes][$i]['detallesAdicionales']['detAdicional']['@attributes'];
						$ContadorDetAdicionales = count($detAdicionalContador);
						for($j = 0; $j < $ContadorDetAdicionales; $j++){
							$detAdicional = $comprobante[$partesComp[3]][$subpartes][$i]['detallesAdicionales']['detAdicional']['@attributes'];
							// print_r($detalleAdicional);
							$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
						}
					}

				}
				// Elimina los impuestos y detalles adicionales del primer item
				unset($detalles[$i]['impuestos']);
				unset($detalles[$i]['detallesAdicionales']);
				// Agrega los detalles con los detAdicionales
				$items = $detalles[$i] + $detallesAdicionales;
				unset($detallesAdicionales);
			}else{
				// Elimina los impuestos del primer item
				unset($detalles[$i]['impuestos']);
				$items = $detalles[$i];
			}

			// Cuando se encuentre una variacion el los campos agrega datos faltante
			$items2 = $items;
			if ($tipoComp == "factura" || $tipoComp == "nota de credito") {
				if (empty($items['codigoAuxiliar']) || empty($items['codigoAdicional'])) {

					foreach ($items2 as $key => $value) {
						$items2 += [$key => $value];
						if ($key == "codigoPrincipal" || $key == "codigoInterno") {
							$items2 += ["codigoAuxiliar" => "NULL"];
							// echo "se agrego codigoAuxiliar</br>";
						}
					}
				}

			}

			if($tipoComp == "guia de remision") {
				if (empty($items['codigoAdicional'])) {

					foreach ($items2 as $key => $value) {
						$items2 += [$key => $value];
						if ($key == "codigoInterno") {
							$items2 += ["codigoAdicional" => "NULL"];
							// echo "se agrego codigoAdicional</br>";
						}
					}
					// print($items2);
				}
			}


		// Imprime las cabeceras de los detalles
		if($i == 0){
			$table .= "<tr>";
			foreach($items2 as $detalle =>$valor){
				$table .= "<th>" .$detalle. "</th>";

			}
			$table .= "</tr>";
		}


		// imprime todos los valores que existan de cada items
		$table .= "<tr>";
		foreach($items2 as $detalle => $valores){
				$table .= "<td>" .$valores. "</td>";
		}


		// comprueba los impuestos por detalle antes de cerrar las filas
		if($tipoComp == "factura"):
			if (isset($comprobante[$partesComp[3]][$subpartes][$i]['impuestos']['impuesto'][0])) {
				$impuestos = $comprobante[$partesComp[3]][$subpartes][$i]['impuestos']['impuesto'];
			}else{
				$impuestos = $comprobante[$partesComp[3]][$subpartes][$i]['impuestos'];
			}

			foreach ($impuestos as $key => $impuesto) {
				$impuesto = $impuesto['tarifa'];
				if($impuesto == "0" || $impuesto == "10"):
					$table .= "<td>*</td>";
				endif;
			}


		endif;

		$table .= "</tr>";

		unset($items);
		unset($items2);

		}

		echo "se encontraron " .count($detalles). " detalles<br>";
		echo "se encontraron " .$ContadorDetAdicionales. " detalles Adicionales<br>";
		// print_r($detallesAdicionales);
		// return $detallesAdicionales;



// Si solo existe un item en el comprobante se imprime esto
}else{
	echo "<br>La {$tipoComp} posee un solo item</br>";
	$items;
	// Obtiene la cabecera de los detalles adicionales
	if(isset($detalles['detallesAdicionales'])){

		$detallesAdicionales = array();
		if(array_key_exists("0", $detalles['detallesAdicionales']['detAdicional'])){
			if($tipoComp == "guia de remision"){
				$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle']['detallesAdicionales']['detAdicional'];
				$ContadorDetAdicionales = count($detAdicionalContador);
						for($i = 0; $i < $ContadorDetAdicionales; $i++){
					$detAdicional = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle']['detallesAdicionales']['detAdicional'][$i]['@attributes'];
					// print_r($detalleAdicional);
					$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
				}
			}else{

				$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detallesAdicionales']['detAdicional'];

				$ContadorDetAdicionales = count($detAdicionalContador);
				for($i = 0; $i < $ContadorDetAdicionales; $i++){
					$detAdicional = $comprobante[$partesComp[3]][$subpartes]['detallesAdicionales']['detAdicional'][$i]['@attributes'];
					// print_r($detalleAdicional);
					$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
				}

			}

		}else{
			if($tipoComp == "guia de remision"){
				$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle']['detallesAdicionales']['detAdicional']['@attributes'];
				$ContadorDetAdicionales = count($detAdicionalContador);
				for($i = 0; $i < $ContadorDetAdicionales; $i++){
					$detAdicional = $comprobante[$partesComp[3]][$subpartes]['detalles']['detalle']['detallesAdicionales']['detAdicional']['@attributes'];
					// print_r($detalleAdicional);
					$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
				}
			}else{
				$detAdicionalContador = $comprobante[$partesComp[3]][$subpartes]['detallesAdicionales']['detAdicional']['@attributes'];
				$ContadorDetAdicionales = count($detAdicionalContador);
				for($i = 0; $i < $ContadorDetAdicionales; $i++){
					$detAdicional = $comprobante[$partesComp[3]][$subpartes]['detallesAdicionales']['detAdicional']['@attributes'];
					// print_r($detalleAdicional);
					$detallesAdicionales += [$detAdicional['nombre'] => $detAdicional['valor']];
				}
			}

		}
		// Elimina los impuestos y detalles adicionales del primer item
		unset($detalles['impuestos']);
		unset($detalles['detallesAdicionales']);
		// Agrega los detalles con los detAdicionales
		$items = $detalles + $detallesAdicionales;
	}else{
		// Elimina los impuestos del primer item
		unset($detalles['impuestos']);
		$items = $detalles;
	}

	if($tipoComp == "factura"):
	$impuesto = $comprobante[$partesComp[3]][$subpartes]['impuestos']['impuesto']['tarifa'];
	endif;

	// Cuando se encuentre una variacion el los campos agrega datos faltante
	$items2 = $items;
	if ($tipoComp == "factura" || $tipoComp == "nota de credito") {
		if (empty($items['codigoAuxiliar']) || empty($items['codigoAdicional'])) {

			foreach ($items as $key => $value) {
				$items2 += [$key => $value];
				if ($key == "codigoPrincipal" || $key == "codigoInterno") {
					$items2 += ["codigoAuxiliar" => "NULL"];
					echo "se agrego codigoAuxiliar</br>";
				}
			}
		}

	}

	if($tipoComp == "guia de remision") {
		if (empty($items['codigoAdicional'])) {

			foreach ($items as $key => $value) {
				$items2 += [$key => $value];
				if ($key == "codigoInterno") {
					$items2 += ["codigoAdicional" => "NULL"];
					echo "se agrego codigoAdicional</br>";
				}
			}
		}
	}

    // las cabeceras del unico item
	$table .= "<tr>";
	foreach($items2 as $detalle => $valores){
		$table .= "<th>" .$detalle. "</th>";
	}
	$table .= "</tr><tr>";
	// los valores del unico item
	foreach($items2 as $detalle => $valores){
		$table .= "<td>" .$valores. "</td>";
	}
	if($tipoComp == "factura"){
		if($impuesto != "12"):
			$table .= "<td>*</td>";
		endif;
	}

	$table .= "</tr>";
	// echo "se encontraron " .count($detalles). " detalles<br>";
	echo "se encontraron " .$ContadorDetAdicionales. " detalles Adicionales<br>";
}

// Mostramos la tabla de detalle en pantalla
$table .= "</theader></table>";
echo $table;

// Imprime los totales
if ($tipoComp == "factura") {
	if (isset($comprobante[$partesComp[2]]['totalConImpuestos']['totalImpuesto'][0])) {

		$array_totalImpuestos = $comprobante[$partesComp[2]]['totalConImpuestos']['totalImpuesto'];
	}
	else{
		$array_totalImpuestos = $comprobante[$partesComp[2]]['totalConImpuestos'];
	}

	foreach ($array_totalImpuestos as $key => $value) {
		$impuestoTotal = $value;
		$tarifa = "0";
		if ($impuestoTotal['codigoPorcentaje'] == 2) {
			$tarifa = "12";
		}
		// echo $key . ": " . $value . " ";
		echo "Base Imponible <span>" .$tarifa . "%</span>: " .$impuestoTotal['baseImponible'] . "</br>";
		// echo $impuestos['tarifa'];
		echo "IVA: " .$impuestoTotal['valor'] . "</br>";
	}
	echo "TOTAL: " .$comprobante[$partesComp[2]]['importeTotal'];
}

}

}
?>
