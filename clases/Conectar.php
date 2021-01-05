<?php

/**
 *
 */

class Conectar
{

  public function toConect()
  {
    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $base = "gestor";

    $conexion = new mysqli($servidor,
                       $usuario,
                       $password,
                       $base);

    $conexion->set_charset('utf8mb4');

    if ($conexion->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}else{

  //echo "Connected successfully";
}
    //return $cof;
    return $conexion;
  }
}



class conectToFerricons
{

  private $server = "localhost";
  private $user = "root";
  private $password = "";
  private $bd = "bd_ferricons";

  public function conect()
  {
    $conect = new mysqli($this->server,
                         $this->user,
                         $this->password,
                         $this->bd);
    if ($conect->connect_error)
    {
      die("Connection failed: " . $conn->connect_error);
    }
    return $conect;
  }

}



 ?>
