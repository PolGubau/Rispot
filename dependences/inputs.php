<?php
function inputs()
{
    // FUNCTIONS
    function getWeekDay($date)
    {
        return date('l', strtotime($date));
    }


    // Client
    $pais = $_POST['COUNTRY'];
    $CP = $_POST['CP'];

    // date
    $fecha = $_POST['DATE'];
    $año_a = substr($fecha, 0, 4);
    $mes_a = substr($fecha, 5, 2);
    $dia_m = substr($fecha, 8, 2);
    $weekday = getWeekDay($fecha);
    // $Mes = $_POST['MONTH'];
    // $Dia = $_POST['WEEKDAY'];

    // Product
    $numero = $_POST['NUMBER'];
    $Asin = $_POST['ASIN'];
    $precio = $_POST['PRICE'];


    // meta
    $User = $_SESSION['username'];
    $Added = Date('y,m,d');


    // time
    $Hora = $_POST['HOUR'];
    $data = $fecha . ' ' . $Hora;

    $Horaaprox = substr($Hora, 0, 2);
    $minutos = substr($Hora, 3, 5);
    intval($Horaaprox);
    if (intval($minutos) > 30) $Horaaprox++;

    if ($Horaaprox > 23) $Horaaprox = 0;

    if (strlen($Horaaprox == 1)) $Horaaprox = '0' + $Horaaprox;
    return [
        'country' => $pais,
        'CP' => $CP,
        'date' => $fecha,
        'year' => $año_a,
        'month' => $mes_a,
        'day' => $dia_m,
        'weekday' => $weekday,
        'number' => $numero,
        'asin' => $Asin,
        'price' => $precio,
        'user' => $User,
        'added' => $Added,
        'hour' => $Hora,
        'minutes' => $minutos,
        'datehour' => $data,
        'houraprox' => $Horaaprox,
    ];
}
