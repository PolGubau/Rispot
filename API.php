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
        $db = $_POST['db'];
        $query = "DELETE FROM $db WHERE ID= $id";
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
        $User = $_user;
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

    $search = $_POST['search'];
    if (!empty($search)) {

        $query = "SELECT * FROM pedidos WHERE 
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

    if (!isset($_POST['limit'])) {
        $limit = 10;
    } else ($limit = $_POST['limit']);

    if (!isset($_POST['order'])) {
        $order = 'ID';
    } else {
        $order = $_POST['order'];
    }

    if (!isset($_POST['direction'])) {
        $direction = 'DESC';
    } else {
        $direction = $_POST['direction'];
    }



    $query = "SELECT * FROM pedidos ORDER BY $order $direction LIMIT $limit";
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
    echo $json_string;
}
// View backup Database
if (isset($_REQUEST['viewBackupDB'])) {

    if (!isset($_POST['limit'])) {
        $limit = 10;
    } else ($limit = $_POST['limit']);

    if (!isset($_POST['order'])) {
        $order = 'ID';
    } else {
        $order = $_POST['order'];
    }

    if (!isset($_POST['direction'])) {
        $direction = 'DESC';
    } else {
        $direction = $_POST['direction'];
    }



    $query = "SELECT * FROM backup ORDER BY $order $direction LIMIT $limit";
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
    echo $json_string;
}
// Backup a Row
if (isset($_REQUEST['BackupRow'])) {
    $id = $_POST['id'];
    $query = "INSERT INTO pedidos SELECT * FROM backup WHERE ID = $id";
    $result = mysqli_query($connection, $query);
    $query = "DELETE FROM backup WHERE ID = $id";
    $result = mysqli_query($connection, $query);
    
}
