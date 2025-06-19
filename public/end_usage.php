<?php

session_start();

// Ensure the session USERNAME and MAMAY exist
if (!isset($_SESSION['username'], $_SESSION['MAMAY'])) {
    // Redirect or handle the error as appropriate
    header("Location: Login.php");
    exit;
}

$username = $_SESSION['username'];
$mamay = $_SESSION['MAMAY'];

// Connect to the database
$conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    exit;
}

// Confirm association between MAMAY and USERNAME before proceeding
$checkStid = oci_parse($conn, "SELECT MT_USERNAME FROM MAYTINH WHERE MAMAY = :mamay");
oci_bind_by_name($checkStid, ':mamay', $mamay);
oci_execute($checkStid);

$row = oci_fetch_array($checkStid, OCI_ASSOC);
if ($row && $row['MT_USERNAME'] === $username) {
    // Call the stored procedure to formally end the usage
    $stid = oci_parse($conn, "BEGIN sp_end_usage(:username, :mamay); END;");
    oci_bind_by_name($stid, ":username", $username);
    oci_bind_by_name($stid, ":mamay", $mamay);
    oci_execute($stid);

    // Clear the MT_USERNAME in MAYTINH as this computer is no longer in use by this user
    $updateStid = oci_parse($conn, "UPDATE MAYTINH SET MT_USERNAME = NULL WHERE MAMAY = :mamay");
    oci_bind_by_name($updateStid, ':mamay', $mamay);
    oci_execute($updateStid);

    // Clear session and perform any logout operations
    session_unset();
    session_destroy();

    // Redirect or give a logout success message
    header("Location: Login.php");
    exit;
} else {
    // Handle the case where there is a mismatch or no MT_USERNAME for the MAMAY
    // Perhaps redirect to an error page or display an error message
}

?>