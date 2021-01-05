<?php

// Include and initialize Extractor class
require_once 'clases/Extractor.php';
$extractor = new Extractor;

$zips=glob("..\..\practicas\imap\attachment");
print_r($zips);
$directorio=opendir("..\..\practicas\imap\attachment");
$leerdir=readdir($directorio);
echo $leerdir;
var_dump($directorio);
// foreach($zips as $recoridos){
// // Path of archive file
// $archivePath = '/path/to/archive.zip';
//
// // Destination path
// $destPath = '/destination/dir/';
//
// // Extract archive file
// $extract = $extractor->extract($archivePath, $destPath);
//
// if($extract){
//     echo $GLOBALS['status']['success'];
// }else{
//     echo $GLOBALS['status']['error'];
// }
// }
?>
