<?php

// Listado de Precios Comerciales
$URL2 = 'https://bit.ly/2JNcTha';
if(file_put_contents("prices.xml", file_get_contents($URL2))){
    echo "====> Lista de precios downloaded :3\n"; // 2JNcTha
}
else {echo "******conection error :C********\n";}   


//////////////////////// 

$con1 = conection1(); 
echo "======>successfull conection Mysql<========\n";  
LeerXMLInsert1($con1); 
mysqli_close($con1);  
echo "datos insertados correctamente\n";
echo "======>conection close Mysql<========\n";
///home/serch/Downloads/places.xml


function LeerXMLInsert1($con1)
{  

    $prices = simplexml_load_file("prices.xml");
    foreach($prices as $dates)
    { 
    // Premiun,Diesel
    $places = $dates["place_id"];  
    $gas2 = $dates -> gas_price[1];
    $gas1 = $dates -> gas_price;  
    $gas3 = $dates -> gas_price[2];
    
    
    $insert1= "INSERT INTO listaprecios (ID,Regular,Premium,Diesel) VALUES ('$places','$gas1','$gas2','$gas3')"; 
    $query = mysqli_query($con1,$insert1) or die (mysqli_error($con1)); 

    }  

}


function conection1(){
$user="root"; 
$pass=""; 
$server="localhost"; 
$db ="GASOLINERA"; 
$conectionMysql=mysqli_connect($server,$user,$pass,$db) or die ("***** Error conection Mysql ***** Fatality :c"); 
//mysql_select_db($db,$conectionMysql);

return $conectionMysql; 
}

?>
