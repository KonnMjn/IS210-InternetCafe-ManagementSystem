<?php
session_start();
include 'LoginConnect.php';

$username = $_SESSION['username'];

$con = getDbConnection();
if (!$con) {
    $m = oci_error();
    echo $m['message'] . "\n";
    exit;
}

$query = "SELECT * FROM TAIKHOAN WHERE USERNAME = :username";
$stid = oci_parse($con, $query);

oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);

$row = oci_fetch_array($stid, OCI_ASSOC);

if (!$row) {
    echo 'No user found';
} else {
    header('Content-Type: application/json');
    echo json_encode($row);
}

oci_free_statement($stid);
oci_close($con);
?>