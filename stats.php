<?php
include('database.php');
$query_total = "SELECT * FROM pedidos";
$result_total = mysqli_query($connection, $query_total);
if (!$result_total) {
    die('Query Error ' . mysqli_errno($connection));
}


$months=[];
$weekdays=[];
$paises=[];
$hores=[];
$date=[];
$any=[];


$cantidad_ventas = 0;
$price_final = 0;


while ($row = mysqli_fetch_array($result_total,MYSQLI_BOTH
)) {
    $price_final = $price_final + $row['PRICE'];
    $cantidad_ventas++;
    array_push($months,$row['MONTH']);
    array_push($weekdays,$row['WEEKDAY']);
    array_push($paises,$row['COUNTRY']);
    array_push($hores,$row['HOURAPROX']);
    array_push($date,$row['DATE']);
    // $anys= substr($row['DATE'], -3,4);
    array_push($any,substr($row['DATE'], -3,4));


 
}
$months=array_count_values($months);
$weekdays=array_count_values($weekdays);
$paises=array_count_values($paises);
$hores=array_count_values($hores);
$anys=array_count_values($any);


$json = array();
$json[] = array(
    'DEV' => 'Pol',
    'TOTAL' => $price_final,
    'CANTIDAD_VENTAS' => $cantidad_ventas,
    'MONTHS' => $months,
    'WEEKDAYS' => $weekdays,
    'COUNTRY' => $paises,
    'HOURSAPROX'=>$hores,
    'DATA'=>$data,
    'ANYS'=>$anys
);

$json_string = json_encode($json);
echo $json_string;
