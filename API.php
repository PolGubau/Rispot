<?php
require('./DB/database.php');
session_start();

$login = true;
if (!isset($_SESSION['username'])) {
    $login = false;
}
require('./dependences/inputs.php');




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

    if (isset($_POST['NUMBER'])) {

        $row = inputs();
        // qry
        $query = "INSERT INTO pedidos (NUMBER,PRICE,COUNTRY,CP,DATEHOUR,DATE,HOUR,HOURAPROX,MONTH, WEEKDAY,ASIN,USER,ADDED) VALUES ('
        $row[number]',
        $row[price] ,
        '$row[country]',
        '$row[CP]' ,
        '$row[datehour]' ,
        '$row[date]',
        '$row[hour]',
        '$row[houraprox]',
        '$row[month]',
        '$row[weekday]',
        '$row[asin]',
        '$row[user]',
        '$row[added]')";

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
    $id = $_POST['ID'];
    $r = inputs();

    $query = "UPDATE
    `pedidos`
    SET
    `NUMBER` = '$r[number]',
    `PRICE` = $r[price],
    `COUNTRY` = '$r[country]',
    `CP` = '$r[CP]',
    `DATEHOUR` = '$r[datehour]',
    `DATE` = '$r[date]',
    `HOUR` = '$r[hour]',
    `HOURAPROX` = '$r[houraprox]',
    `MONTH` = '$r[month]',
    `WEEKDAY` = '$r[weekday]',
    `ASIN` = '$r[asin]',
    `USER` = '$r[user]',
    `ADDED` = '$r[added]'
    WHERE
    `ID` = '$id'";

    $result = mysqli_query($connection, $query);

    if (!$result) die('Query Error ' . mysqli_errno($connection));
    echo 'Updated by ' . $r['user'] . '. On ' . $id;
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

// ?Top Function: Returns the entire Database
if (isset($_REQUEST['viewDB'])) {
    if (!isset($_SESSION['username'])) {
        
        echo 'No_login';
        $login = false;
    }

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
    if ($login != false) echo $json_string;
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
if (isset($_REQUEST['truncateBackup'])) {
    $query = "TRUNCATE `backup`;
    ";
    $result = mysqli_query($connection, $query);

    if (!$result) die('Query Error ' . mysqli_errno($connection));
}
