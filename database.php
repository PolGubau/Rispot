<?php
$_host = '127.0.0.1';
$_user = 'root';
$_password = '';
$_table = 'pau';

$connection = mysqli_connect(
    "$_host",
    "$_user",
    "$_password",
    "$_table"
);
