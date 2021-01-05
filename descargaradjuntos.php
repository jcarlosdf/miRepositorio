<?php

set_time_limit(3000);

/* connect to mail with your credentials */
// $hostname = "{imap.mail.yahoo.com:993/imap/ssl/novalidate-cert}INBOX";
// $username = "disensadelgadobasurto@yahoo.com";
// $password = "ouihderowtcannhf";

// otras credenciales
$hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
$username = "josedelgado1987@gmail.com";
$password = "JCDelgadoF1987";

// FROM ferrohecaduelectronico@gmail.com
/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

$emails = imap_search($inbox, 'SINCE "01-MAY-2019" BEFORE "30-JUN-2019"');

/* if any emails found, iterate through each email */
if($emails) {

       $count = 1;

       /* put the newest emails on top */
       rsort($emails);

       /* for every email... */
       foreach($emails as $email_number)
       {

           /* get information specific to this email */
           $overview = imap_fetch_overview($inbox,$email_number,0);

           $message = imap_fetchbody($inbox,$email_number,2);

           /* get mail structure */
           $structure = imap_fetchstructure($inbox, $email_number);

           $attachments = array();

           /* if any attachments found... */
           if(isset($structure->parts) && count($structure->parts))
           {
               for($i = 0; $i < count($structure->parts); $i++)
               {
                   $attachments[$i] = array(
                       'is_attachment' => false,
                       'filename' => '',
                       'name' => '',
                       'attachment' => ''
                   );

                   if($structure->parts[$i]->ifdparameters)
                   {
                       foreach($structure->parts[$i]->dparameters as $object)
                       {
                           if(strtolower($object->attribute) == 'filename')
                           {
                               $attachments[$i]['is_attachment'] = true;
                               $attachments[$i]['filename'] = $object->value;
                           }
                       }
                   }

                   if($structure->parts[$i]->ifparameters)
                   {
                       foreach($structure->parts[$i]->parameters as $object)
                       {
                           if(strtolower($object->attribute) == 'name')
                           {
                               $attachments[$i]['is_attachment'] = true;
                               $attachments[$i]['name'] = $object->value;
                           }
                       }
                   }

                   if($attachments[$i]['is_attachment'])
                   {
                       $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

                       /* 3 = BASE64 encoding */
                       if($structure->parts[$i]->encoding == 3)
                       {
                           $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                       }
                       /* 4 = QUOTED-PRINTABLE encoding */
                       elseif($structure->parts[$i]->encoding == 4)
                       {
                           $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                       }
                   }
               }
           }

           /* iterate through each attachment and save it */
           foreach($attachments as $attachment)
           {
               if($attachment['is_attachment'] == 1)
               {
                   $filename = $attachment['name'];
                   if(empty($filename)) $filename = $attachment['filename'];

                   if(empty($filename)) $filename = time() . ".dat";
                   $folder = "attachments1";
                   if(!is_dir($folder))
                   {
                        mkdir($folder);
                   }
                   if (preg_match("/.\.(xml)/", utf8_decode($filename))) {

                     $fp = fopen("./". $folder ."/". $email_number . "-" . $filename, "w+");
                     fwrite($fp, $attachment['attachment']);
                     fclose($fp);
                     echo "se descargo xml</br>";
                   }
               }
           }
       }
}

/* close the connection */
imap_close($inbox);

echo "all attachment Downloaded";

?>
