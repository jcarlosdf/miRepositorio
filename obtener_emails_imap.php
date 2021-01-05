<?php


class OptieneMails {

//cuenta gmail
// var $user ="josedelgado1987@gmail.com";
// var $password = "JCDelgadoF1987";
// var $mailbox = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";

//cuenta yahoo
var $user ="disensadelgadobasurto@yahoo.com";
var $password = "ouihderowtcannhf"; //CLAVE DE APLICACION TERCERO GENERADA, SE PUEDE ELIMINAR DESDE LA CUENTA YAHOO
var $mailbox = "{imap.mail.yahoo.com:993/imap/ssl/novalidate-cert}INBOX";

var $fecha1 = "01-JAN-2015";
var $fecha2 = "31-DEC-2015";

function obtieneAsuntosDelMails(){
  $inbox=imap_open($this->mailbox, $this->user, $this->password) or die("FALLA EN EL SISTEMA: NO SE PUDO CONECTAR ☺ " . imap_last_error());
  $emails=imap_search($inbox, 'SINCE "01-JAN-2015" BEFORE "31-DEC-2015" FROM ferrohecaduelectronico@gmail.com');
  print 'se encontraron: ' .count($emails) . ' correos en total' . "<br><br>";
  if($emails){
    foreach ($emails as $email_number) {
      $overview = imap_fetch_overview($inbox,$email_number);

      foreach ($overview as $over) {
        if (isset($over->subject)) {
          $asunto = $this->fixTextSubject($over->subject);
          $desde = $this->fixTextSubject($over->from);
          $fecha = $this->fixTextSubject($over->date);

          echo $asunto." ....... " . $desde . " - " .$fecha . "<br>";

        }
      }
    }
  }
}

function fixTextSubject($str){
  $subject="";
  $subject_array=imap_mime_header_decode($str);

  foreach ($subject_array as $s) {
    $subject .=utf8_encode(rtrim($s->text, "t"));
    return $subject;
  }
}


}

$oOptieneMails = new OptieneMails();
$oOptieneMails->obtieneAsuntosDelMails();



 ?>
