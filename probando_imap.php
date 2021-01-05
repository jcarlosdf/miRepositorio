<?php
class Correo {


private $server="{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
private $username="josedelgado1987@gmail.com";
private $password="JCDelgadoF1987";
private $date1="05-JUN-2020";
private $date2="05-JUL-2020";

public function verCorreos(){


$inbox=imap_open($this->server,$this->username,$this->password);

if($inbox){
  echo "establecistes coneccion con el buzon de correo" . "<br>";
}else{
  echo "ultimo error: " . imap_last_error();
}

$mails=imap_search($inbox, 'SINCE "'.$this->date1.'" SUBJECT Factura');

// echo "<pre>";
// print_r($mails);
// echo "</pre>";

echo count($mails) . "<br>";
//$overview=array();
if ($mails) {
  // header("Content-disposition:attachment;filename=facturita.xml");
  // header("Content-type:application/xml");
foreach($mails as $mail_overview){

  $overview = imap_fetch_overview($inbox,$mail_overview);

  foreach ($overview as $subjects) {
    //echo imap_qprint(imap_body($inbox, $subjects->msgno)) . "<br>";
    $estruct=imap_bodystruct($inbox, $subjects->msgno,2);
    
    echo "<pre>";
    print_r($estruct);
    echo "</pre>";

    foreach ($estruct->parameters as $filename) {

      $name=$filename->value;
      echo  $name. "<br>";

      if (!is_dir("attachments")) {
        mkdir("attachments");
      }
      // $attachment=imap_savebody($inbox, fopen("attachments/".$name,"w"),$subjects->msgno,3);
    }
    // fclose();
    $mime=imap_mime_header_decode($subjects->subject);
    //print_r($mime);

    foreach ($mime as $m) {
      $subject=utf8_encode($m->text);
      echo utf8_decode($subject) ."<br>";
    }

  }

  // echo "<pre>";
  // print_r($overview);
  // echo "</pre>";
      }
    }
    imap_close($inbox);
  }

}
$llamar=new Correo();
$llamar->verCorreos();
 ?>
