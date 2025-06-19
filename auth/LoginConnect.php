<?php
function getDbConnection()
{
    $username = "sinhvienuit";
    $password = "sinhvienuit";
    $connection_string = "localhost/orcl";

    $conn = oci_connect($username, $password, $connection_string);
    if (!$conn) {
        $e = oci_error();
        echo "Connection failed: " . $e['message'];
        exit;
    }
    else{
        
    }
    return $conn;
}