<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'lab10_php_oop';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) die('Koneksi DB gagal');
