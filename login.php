<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="./images/0.icons/ico_U.ico" sizes="16x16 32x32" type="image/png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="RISPOT" />
    <meta property="og:type" content="Rispot DB" />
    <meta property="og:url" content="https://rispot.cat" />
    <meta property="og:description" content="Inside DB" />
    <title>Rispot Login</title>


</head>

<body>
    <?php
    session_start();

    include('./DB/database.php');

    if (isset($_REQUEST['enviar'])) {

        if (isset($_REQUEST['username'])) {
            $username = htmlentities(addslashes($_REQUEST['username']));
        } else {
            die('Es necesario un usuario para continuar.');
        }
        $password = htmlentities(addslashes($_REQUEST['password']));

        $query = "SELECT * FROM users";
        $result = mysqli_query($connection, $query);



        while ($row = mysqli_fetch_array($result)) {
            if ($row['username'] == $username) {

                echo 'User Ok, Password Incorrect';

                if ($row['password'] == $password) {

                    $_SESSION['username'] = $username;
                    header('Location:index.html');
                }
            }
        }
    }else{
        session_destroy();
    }
    ?>
    <div class="center_login">

        <form class="login_form" action="login.php" method="post">
            <div class="inputs usuari_div">
                <label class="label" for="username"> User Name</label>
                <input type="text" name="username" maxlength="20" autofocus>
            </div>
            <div class="inputs contrasenya_div">
                <label class="label" for="password"> Password</label>
                <?php
                if (!isset($_REQUEST['pass'])) {
                    echo ' <input type="password" name="password" maxlength="30" >';
                } else {
                    echo ' <input style="border:1px solid red" type="password" name="password" maxlength="30" >';
                }
                ?>
            </div>

            <div class=" submit_div">

                <input type="submit" class="sudbutton inicia_sessio" name="enviar" value="Iniciar sessió">
            </div>


        </form>
    </div>


</body>

</html>