<?php

//Para ubicar en cualquier link html y hacer el enlace de descarga
//<a href="download.php?file=fichero.png">Descargar fichero</a>

if(!empty($_GET['file'])){
    $fileName = basename($_GET['file']);
    $filePath = 'files/'.$fileName;
    if(!empty($fileName) && file_exists($filePath)){
      
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // Read the file
        readfile($filePath);
        exit;
    }else{
        echo 'The file does not exist.';
    }
}
