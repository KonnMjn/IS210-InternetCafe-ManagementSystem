<?php
$username = "sinhvienuit";
$password = "sinhvienuit";
$connect_string = "localhost/orcl";

$con = oci_connect($username, $password, $connect_string);
if (!$con)
{
    $e = oci_error();
    echo "Connection failed: ".$e['message'];
    exit;
}
?>