<?php


class metodosCRUD
{

  public function mostrarDatos($sql)
  {
    $c = new conectToFerricons();
    $conexionBD = $c->conect();

    $result = mysqli_query($conexionBD, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }


  public function insertarDatos($datos)
  {
    $c = new conectToFerricons();
    $conexionBD = $c->conect();

    $sql = "INSERT INTO productos(codProveedor,
                                  codProducto,
                                  producto,
                                  descripcion,
                                  proveedor,
                                  priceIN,
                                  foto)
            VALUES(?,?,?,?,?,?,?)";
    $pre = $conexionBD->prepare($sql);
    $pre->bind_param("ssssids", $datos['codProveedor'],
                               $datos['codProducto'],
                               $datos['producto'],
                               $datos['descripcion'],
                               $datos['proveedor'],
                               $datos['priceIN'],
                               $datos['foto']);
    $ok = $pre->execute();
    $pre->close();
    return $ok;
  }

  public function buscarProductoRepetido($codigos)
  {
    $c = new conectToFerricons();
    $conexionBD = $c->conect();
    $sql = "SELECT pp.codProducto, pp.codProveedor, pv.numeroID
            FROM productos pp INNER JOIN proveedor pv
            ON pp.proveedor=pv.id_proveedor
            WHERE pp.codProducto = ".$codigos['codProducto']."
            OR pp.codProveedor = ".$codigos['codProveedor']."";
    $consulta = mysqli_query($conexionBD, $sql);
    $dato=mysqli_fetch_array($consulta);
    if ($dato['codProducto'] == $codigos['codProducto']) {
      // Si existe el codigo de producto entonces_
      // volver a generar uno nuevo
      return 1;
    }elseif ($dato['codProveedor'] == $codigos['codProveedor']) {
      return 2;
    }else{
      return 0;
    }

  }


}























 ?>
