<?php

require_once "Conectar.php";

class Usuario extends Conectar
{

  public function agregarUsuario($data) {

    if (self::buscarUsuarioRepetido($data['user'])) {
      return 2;
    }else{

      $con = Conectar::toConect();
      //$con->conect();

      $consult="insert into usuario (
        nombrePerson,
        fechaNacimiento,
        emailUser,
        nombreUser,
        passwordUser
        )
        values(
          ?,?,?,?,?
          )";


          $qr=$con->prepare($consult);
          
          $qr->bind_param("sssss",$data['nombre'],
          $data['birth'],
          $data['correo'],
          $data['user'],
          $data['contrasena']
        );
        $exito = $qr->execute();
        $qr->close();
        return $exito;
    }
  }

  public function buscarUsuarioRepetido($usuario){
    $con = Conectar::toConect();
    $consulta="select nombreUser from usuario where nombreUser='$usuario'";
    $ejecutar=mysqli_query($con,$consulta);
    $datos=mysqli_fetch_array($ejecutar);
    //mysqli_close($con);
    if ($datos['nombreUser'] != "" || $datos['nombreUser'] == $usuario) {
      return 1;
    }else{
      return 0;
    }
  }


  public function login($log){
    $con = Conectar::toConect();

    $queri="select count(*) as totalUser from usuario
            where nombreUser = '".$log['user']."'
            and passwordUser = '".$log['contrasena']."'";
    $eject=mysqli_query($con,$queri);
    $col=mysqli_fetch_array($eject)['totalUser'];
    if ($col > 0) {
      $_SESSION['usuario'] = $log['user'];
      $queri="select iduser from usuario
      where nombreUser = '".$log['user']."'
      and passwordUser = '".$log['contrasena']."'";
      $eject=mysqli_query($con,$queri);
      $col=mysqli_fetch_row($eject)[0];
      $_SESSION['idusaurio'] = $col;
      return 1;
    }else{
      return 0;

    }

  }


}





 ?>
