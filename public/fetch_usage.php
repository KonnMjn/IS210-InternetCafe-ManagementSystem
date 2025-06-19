<?php
// fetch_usage.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT * FROM SUDUNG ORDER BY TO_NUMBER(MAMAY)");
oci_execute($stid);

$data = array();
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $data[] = array(
        'USERNAME' => $row['USERNAME'],
        'MAMAY' => $row['MAMAY'],
        'CONSUMPTION' => $row['TIEUTHU'],
        'GIOBATDAU' => $row['GIOBATDAU'],
        'GIOKETTHUC' => $row['GIOKETTHUC']
    );
}

header('Content-Type: application/json');
echo json_encode($data);
?>