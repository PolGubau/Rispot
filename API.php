<?php
include('database.php');
session_start();
// Returns a JSON array with the values of a row from the DB
if (isset($_REQUEST['valorize'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "SELECT * FROM pedidos WHERE ID= $id";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Query Error ' . mysqli_errno($connection));
        }

        $json = array();

        while ($row = mysqli_fetch_array($result)) {
            $json[] = array(
                'numero' => $row['NUMBER'],
                'precio' => $row['PRICE'],
                'pais' => $row['COUNTRY'],
                'CP' => $row['CP'],
                'data' => $row['DATEHOUR'],
                'fecha' => $row['DATE'],
                'Hora' => $row['HOUR'],
                'Horaaprox' => $row['HOURAPROX'],
                'mes' => $row['MONTH'],
                'dia' => $row['WEEKDAY']

            );
        }
        $json_string = json_encode($json);
        echo $json_string;
    }
}

// Delete row from the DB
if (isset($_REQUEST['deleteFromDB'])) {

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM pedidos WHERE ID= $id";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Query Error ' . mysqli_errno($connection));
        }
    }
}

// Adds row to the DB
if (isset($_REQUEST['addToDB'])) {

    if (isset($_POST['numero'])) {

        $numero = $_POST['numero'];
        $precio = $_POST['precio'];
        $pais = $_POST['pais'];
        $CP = $_POST['CP'];
        $fecha = $_POST['fecha'];
        $Hora = $_POST['Hora'];
        $Mes = $_POST['Mes'];
        $Dia = $_POST['Dia'];

        $data = $fecha . ' ' . $Hora;


        $Horaaprox = substr($Hora, 0, 2);
        $minutos = substr($Hora, 3, 5);
        intval($Horaaprox);
        if (intval($minutos) > 30) $Horaaprox++;

        if ($Horaaprox > 23) $Horaaprox = 0;


        $query = "INSERT INTO pedidos (NUMBER,PRICE,COUNTRY,CP,DATEHOUR,DATE,HOUR,HOURAPROX,MONTH, WEEKDAY) VALUES ('$numero',$precio ,'$pais','$CP' ,'$data' ,'$fecha','$Hora','$Horaaprox','$Mes','$Dia')";

        $result = mysqli_query($connection, $query);

        if (!$result) {
            die('Query Error ' . mysqli_errno($connection));
        }
        echo 'Tarea agregada con éxito';
    } else {
        echo '<script>alert("Tienes que poner al menos un número al pedido.")</script>';
    }
}

// Uploads a DB row
if (isset($_REQUEST['upload'])) {

    $id = $_POST['id'];

    $numero = $_POST['numero'];
    $precio = $_POST['precio'];
    $pais = $_POST['pais'];
    $CP = $_POST['CP'];
    $fecha = $_POST['fecha'];
    $Hora = $_POST['Hora'];
    $Mes = $_POST['Mes'];
    $Dia = $_POST['Dia'];
    
    $data = $fecha . ' ' . $Hora;


    $Horaaprox = substr($Hora, 0, 2);
    $minutos = substr($Hora, 3, 5);
    
    intval($Horaaprox);
    if (intval($minutos) > 30) $Horaaprox++;

    if ($Horaaprox > 23) $Horaaprox = 0;
    

    $query = "UPDATE `pedidos` SET `NUMBER`='$numero',`PRICE`=$precio,`COUNTRY`='$pais',`CP`='$CP',`DATEHOUR`='$data',`DATE`='$fecha',`HOUR`='$Hora',`HOURAPROX`=$Horaaprox,`MONTH`='$Mes',`WEEKDAY`='$Dia' WHERE 'ID'= $id";

    $result = mysqli_query($connection, $query);

    if (!$result) die('Query Error ' . mysqli_errno($connection));
}

// Searchs into de DB
if (isset($_REQUEST['searchInDB'])) {

    $seasrch = $_POST['search'];
    if (!empty($search)) {

        $query = "SELECT * FROM pedidos WHERE 
    NUMBER LIKE '$search%' OR 
    ID LIKE '$search%' OR 
    PRICE LIKE '$search%' OR 
    COUNTRY LIKE '$search%' OR 
    CP LIKE '$search%' OR 
    DATE LIKE '$search%' OR 
    HOUR LIKE '$search%' OR 
    MONTH LIKE '$search%' OR 
    WEEKDAY LIKE '$search%'";



        $result = mysqli_query($connection, $query);
        if (!$result) {
            die('Query Error ' . mysqli_errno($connection));
        }
        $json = array();

        while ($row = mysqli_fetch_array($result)) {
            $json[] = array(
                'ID' => $row['ID'],
                'NUMBER' => $row['NUMBER'],
                'PRICE' => $row['PRICE'],
                'COUNTRY' => $row['COUNTRY'],
                'CP' => $row['CP'],
                'DATEHOUR' => $row['DATEHOUR'],
                'DATE' => $row['DATE'],
                'HOUR' => $row['HOUR'],
                'HOURAPROX' => $row['HOURAPROX'],
                'MONTH' => $row['MONTH'],
                'WEEKDAY' => $row['WEEKDAY']
            );
        }
        $json_string = json_encode($json);
        echo $json_string;
    } else {
        echo '';
    }
}

// Top Function: Returns the entire Database
if (isset($_REQUEST['viewDB'])) {

    if (!isset($_POST['limit'])) $limit = 10;
    if (isset($_POST['limit'])) $limit = $_POST['limit'];


    $query = "SELECT * FROM pedidos ORDER BY ID DESC LIMIT $limit";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query Error ' . mysqli_errno($connection));
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'ID' => $row['ID'],
            'NUMBER' => $row['NUMBER'],
            'PRICE' => $row['PRICE'],
            'COUNTRY' => $row['COUNTRY'],
            'CP' => $row['CP'],
            'DATEHOUR' => $row['DATEHOUR'],
            'DATE' => $row['DATE'],
            'HOUR' => $row['HOUR'],
            'HOURAPROX' => $row['HOURAPROX'],
            'MONTH' => $row['MONTH'],
            'WEEKDAY' => $row['WEEKDAY']
        );
    };

    $json_string = json_encode($json);
    echo $json_string;
}
// View backup Database
if (isset($_REQUEST['viewBackupDB'])) {

    if (!isset($_POST['limit'])) $limit = 10;
    if (isset($_POST['limit'])) $limit = $_POST['limit'];


    $query = "SELECT * FROM backup ORDER BY ID DESC LIMIT $limit";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query Error ' . mysqli_errno($connection));
    }

    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'ID' => $row['ID'],
            'NUMBER' => $row['NUMBER'],
            'PRICE' => $row['PRICE'],
            'COUNTRY' => $row['COUNTRY'],
            'CP' => $row['CP'],
            'DATEHOUR' => $row['DATEHOUR'],
            'DATE' => $row['DATE'],
            'HOUR' => $row['HOUR'],
            'HOURAPROX' => $row['HOURAPROX'],
            'MONTH' => $row['MONTH'],
            'WEEKDAY' => $row['WEEKDAY']
        );
    };

    $json_string = json_encode($json);
    echo $json_string;
}
