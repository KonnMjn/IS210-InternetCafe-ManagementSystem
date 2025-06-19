<?php
session_start();

// Check for logout action
if (isset($_GET['username'])) {
    $username = urldecode($_GET['username']);

    // Connect to the database
    $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        exit;
    }

    // Fetch MAMAY using USERNAME from the MAYTINH table
    $query = "SELECT MAMAY FROM MAYTINH WHERE MT_USERNAME = :username";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':username', $username);
    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    if ($row) {
        $mamay = $row['MAMAY'];

        // Now that you have both USERNAME and MAMAY, perform your end_usage logic
        // This might involve another database operation or call to a stored procedure

        // Example placeholder for end_usage operation
        $endUsageId = oci_parse($conn, "BEGIN sp_end_usage(:username, :mamay); END;");
        oci_bind_by_name($endUsageId, ":username", $username);
        oci_bind_by_name($endUsageId, ":mamay", $mamay);
        oci_execute($endUsageId);

        // Clear session variables and destroy the session
        $_SESSION = array();
        session_destroy();

        // Redirect to the login page
        header("Location: Login.php");
        exit;
    } else {
        // Handle case where MAMAY is not found - likely a logic or data consistency error
        echo "An error occurred. Unable to find the MAMAY for the given USERNAME.";
        // Consider logging this issue and/or redirecting to an error page
    }
}

session_destroy();

header("Location: Login.php");
exit;
?>