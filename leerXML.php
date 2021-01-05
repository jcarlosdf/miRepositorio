<?php

function leerXML($filename)
{
    $xml = simplexml_load_file($filename);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);

    //$array = $array["comprobante"]["factura"];
    return $array;
}

function obtenerDatos($array)
{
    $ambiente = $array['infoTributaria']['ambiente'];
    $cliente = [
        "nombre" => $array['infoFactura']['razonSocialComprador'],
        "documento" => $array['infoFactura']['identificacionComprador'],
        "direccion" => $array['infoFactura']['direccionComprador'],
        "sinImpuestos" => $array['infoFactura']['totalSinImpuestos'],
        "iva" => ($array['infoFactura']['totalConImpuestos']['totalImpuesto'][0]['valor']),
        "total" => $array['infoFactura']['pagos']['pago']['total'],
        "formaPago" => $array['infoFactura']['pagos']['pago']['formaPago'],
        "descuento" => $array['infoFactura']['totalDescuento'],
        "obligado" => $array['infoFactura']['obligadoContabilidad'],
        "ambiente" => $ambiente
    ];

    return $cliente;
}
function leerXMLCdata($filename)
{
    $xml = simplexml_load_file($filename, "SimpleXMLElement", LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    // return $array;
    $array = simplexml_load_string($array["comprobante"]);
    $json = json_encode($array);
    $array = json_decode($json, TRUE);
    return $array;
}

function consultarDetalle($array)
{
    if (isset($array['detalles']['detalle'][0])) {
        $array_detalles = $array['detalles']['detalle'];
    } else {
        $array_detalles = $array['detalles'];
    }
    ?>
     <table border="1">
      <tr>
      <td>codigo Principal</td>
      <td>descripcion</td>
      <td>precio Unitario</td>
      <td>cantidad</td>
      <td>precioTotalSinImpuesto</td>
      </tr>
    <?php
    foreach ($array_detalles as $key => $value) {
        $prod = $value; ?>
        <tr class="css-1azt77c">
            <td width="20%" class="css-1mqvq1l">
                <span class="css-6ak4ye"><?php echo ($prod['codigoPrincipal']); ?></span>
            </td>
            <td width="40%" class="css-1mqvq1l">
                <span class="css-6ak4ye"><?php echo ($prod['descripcion']); ?></span>
            </td>
            <td width="15%" class="css-mdftu6">
                <span data-selector="currency-amount"><?php echo ($prod['precioUnitario']); ?> $</span></td>
            <td width="15%" class="css-yqqqyh">
                <span data-selector="quantity">
                    <?php echo ($prod['cantidad']); ?></span>
            </td>
            <td width="10%" class="css-1fbfb3x">
                <span data-selector="currency-amount">
                    <?php echo ($prod['precioTotalSinImpuesto']); ?> $</span>
            </td>
        </tr>
    <?php
    } ?>

    </table> <?php
}

$arr = leerXMLCdata("facturas_proveedores/MEGAPRODUCTOS.xml");
consultarDetalle($arr);
// $datos = obtenerDatos($arr);
echo "<pre>";
print_r($arr);
