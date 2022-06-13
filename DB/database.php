<?php

$_host = 'mysql-database-sc.alwaysdata.net';
$_user = '259173';
$_password = 'Admin_development';
$_table = 'database-sc_main';

$connection = mysqli_connect(
    "$_host",
    "$_user",
    "$_password",
    "$_table"
);
