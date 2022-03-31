<?php
require('./DB/database.php');
session_start();

$login=true;
if (!isset($_SESSION['username'])) {
    $login=false;
}


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
                'WEEKDAY' => $row['WEEKDAY'],
                'ASIN' => $row['ASIN'],
                'USER' => $row['USER'],
                'ADDED' => $row['ADDED'],

            );
        }
        $json_string = json_encode($json);
        echo $json_string;
    }
}

// Delete row from the DB
if (isset($_REQUEST['deleteFromDB'])) {

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $table = $_POST['table'];
        $query = "DELETE FROM $table WHERE ID= $id";
        $result = mysqli_query($connection, $query);

        if (!$result) die('QE' . mysqli_errno($connection));
        echo 'Deleted';
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
        $Asin = $_POST['asin'];
        $User = $_SESSION['username'];
        // $User = $_user;
        $Added = Date('D, d M Y H:i');

        $data = $fecha . ' ' . $Hora;


        $Horaaprox = substr($Hora, 0, 2);
        $minutos = substr($Hora, 3, 5);
        intval($Horaaprox);
        if (intval($minutos) > 30) $Horaaprox++;

        if ($Horaaprox > 23) $Horaaprox = 0;

        if (strlen($Horaaprox == 1)) $Horaaprox = '0' + $Horaaprox;

        $query = "INSERT INTO pedidos (NUMBER,PRICE,COUNTRY,CP,DATEHOUR,DATE,HOUR,HOURAPROX,MONTH, WEEKDAY,ASIN,USER,ADDED) VALUES ('$numero',$precio ,'$pais','$CP' ,'$data' ,'$fecha','$Hora','$Horaaprox','$Mes','$Dia','$Asin','$User','$Added')";

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
    $asin = $_POST['asin'];

    $data = $fecha . ' ' . $Hora;


    $Horaaprox = substr($Hora, 0, 2);
    $minutos = substr($Hora, 3, 5);

    intval($Horaaprox);
    if (intval($minutos) > 30) $Horaaprox++;

    if ($Horaaprox > 23) $Horaaprox = 0;


    $query = "UPDATE `pedidos` SET `NUMBER`='$numero',`PRICE`=$precio,`COUNTRY`='$pais',`CP`='$CP',`DATEHOUR`='$data',`DATE`='$fecha',`HOUR`='$Hora',`HOURAPROX`=$Horaaprox,`MONTH`='$Mes',`WEEKDAY`='$Dia',`Asin`='$asin' WHERE 'ID'= $id";

    $result = mysqli_query($connection, $query);

    if (!$result) die('Query Error ' . mysqli_errno($connection));
}

// Searchs into de DB
if (isset($_REQUEST['searchInDB'])) {
    $table =  isset($_POST['table']) ? $_POST['table'] : 'pedidos';
    $search = $_POST['search'];
    if (!empty($search)) {

        $query = "SELECT * FROM $table WHERE 
            ID LIKE '$search%' OR 
            NUMBER LIKE '$search%' OR 
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
//     if (!isset($_SESSION['username'])) {
//         // header('Location:login.php');
//         // echo 'No_login';
//         $login=false;
 
//    }
    
    // Choosing DBS
    $table =  isset($_POST['table']) ? $_POST['table'] : 'pedidos';
    $table2 = isset($_POST['table2']) ? $_POST['table2'] : 'backup';

    // Presets
    $limit =  isset($_POST['limit']) ? $_POST['limit'] : 10;
    $order =  isset($_POST['order']) ? $_POST['order'] : 'ID';
    $direction =  isset($_POST['direction']) ? $_POST['direction'] : 'DESC';


    if ($table2 != '') {
        $query =  "SELECT * from $table UNION ALL SELECT * FROM $table2 ORDER BY $order $direction LIMIT $limit";
    } else {
        $query = "SELECT * FROM $table ORDER BY $order $direction LIMIT $limit";
    }



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
            'WEEKDAY' => $row['WEEKDAY'],
            'ASIN' => $row['ASIN'],
            'USER' => $row['USER'],
            'ADDED' => $row['ADDED'],

        );
    };

    $json_string = json_encode($json);
    if($login!=false)echo $json_string;
}

// Backup a Row
if (isset($_REQUEST['BackupRow'])) {
    $id = $_POST['id'];
    $query = "INSERT INTO pedidos SELECT * FROM backup WHERE ID = $id";
    $result = mysqli_query($connection, $query);
    $query = "DELETE FROM backup WHERE ID = $id";
    $result = mysqli_query($connection, $query);
}
// Backup a Row
if (isset($_REQUEST['closeSession'])) {
    session_destroy();
    header('Location: login.php');
}
