<?php

session_start();
// Regenerate the session ID.
session_regenerate_id();

if (isset($_GET['username'])) {
    $username = $_GET['username']; // Consider sanitizing this value

    // Store username in session if needed
    $_SESSION['username'] = $username;
} else {
    // Handle case where username is not provided
    die('Username not provided.');
}

// Connect to the database.
$conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Get the list of available computers.
$stid = oci_parse($conn, "SELECT * FROM MAYTINH WHERE TRANGTHAI = 'Y' ORDER BY TO_NUMBER(MAMAY) ASC");
oci_execute($stid);

$row = oci_fetch_array($stid, OCI_ASSOC);
if ($row) {
    $mamay = $row['MAMAY'];

    $updateStid = oci_parse($conn, "UPDATE MAYTINH SET MT_USERNAME = :username WHERE MAMAY = :mamay");
    oci_bind_by_name($updateStid, ':username', $username);
    oci_bind_by_name($updateStid, ':mamay', $mamay);
    oci_execute($updateStid);

    $_SESSION['MAMAY'] = $mamay;

    // Start the computer usage session in the SUDUNG table
    $usageStid = oci_parse($conn, "BEGIN SP_THEM_SUDUNG(:username, :mamay); END;");
    oci_bind_by_name($usageStid, ":username", $username);
    oci_bind_by_name($usageStid, ":mamay", $mamay);
    oci_execute($usageStid);

    // Optionally, if you're tracking usage in the session
    if (!isset($_SESSION['pc_usage'])) {
        $_SESSION['pc_usage'] = array();
    }
    $_SESSION['pc_usage'][$mamay] = $username;
} else {
    echo "No available computers.";
}

$query1 = "SELECT SOTIENCONLAI FROM TAIKHOAN WHERE USERNAME = :username";
$stid1 = oci_parse($conn, $query1);
oci_bind_by_name($stid1, ':username', $username);
oci_execute($stid1);
$row1 = oci_fetch_array($stid1, OCI_ASSOC);

// Query to fetch GIANY for the assigned MAMAY
$query2 = "SELECT GIANY FROM MAYTINH WHERE MT_USERNAME = :username";
$stid2 = oci_parse($conn, $query2);
oci_bind_by_name($stid2, ':username', $username);
oci_execute($stid2);
$row2 = oci_fetch_array($stid2, OCI_ASSOC);

if ($row1 && $row2) {
    $soTienConLai = $row1['SOTIENCONLAI'];
    $giany = $row2['GIANY'];

    // Calculate the estimated time left in hours
    $estimatedTimeLeftInHours = $soTienConLai / $giany;

    // For JavaScript, convert hours into total seconds
    $estimatedTimeLeftInSeconds = $estimatedTimeLeftInHours * 3600 + 17;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User's Remote Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            background-color: blueviolet;
        }
        #main {
            width: 400px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            font-size: 1.5em;
        }
        #content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-top: 20px;
        }
        input {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #008000;
            border: none;
            font-weight: bold;
        }
        button:hover{
            background-color: #00FF00;
        }
        #footer {
            background-color: violet;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            border-radius: 5px;
        }
        .custom-footer {
        background-color: #008000;
        }
    </style>
</head>
<body class="bg-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 mt-5">
                <div class="card">
                <div class="card-header bg-success text-white"><?php echo $_SESSION['MAMAY']; ?></div>
                    <div class="card-body" style="padding: 15px;">
                        <label for="hours">Remain hours:</label>
                        <div id="remaining_time" style="padding: 10px; background-color: #f0f0f0; margin-bottom: 20px; border-radius: 5px;">
                            Calculating...
                        </div>
                        <label for="price">Price per hour:</label>
                        <input type="text" id="price" name="price" value="7000VNĐ" class="form-control" readonly><br>
                        <button id="user" class="btn btn-info btn-block mt-3">USER</button>
                        <!-- <button id="service" class="btn btn-info btn-block">SERVICE</button>
                        <button id="lock" class="btn btn-info btn-block" onclick="showLockButton()">LOCK</button> -->
                        <button id="contact" class="btn btn-info btn-block" onclick="showEmail()">CONTACT</button>
                        <a href="logout.php?username=<?php echo urlencode($_SESSION['username']); ?>">Logout</a>
                    </div>
                    <div class="card-footer bg-success text-white text-center">
                        <p>THANKS FOR USING OUR SERVICE!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script>
        window.onload = function() {
            document.getElementById('user').addEventListener('click', function() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'user_ui_fetch_info.php', true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var user = JSON.parse(this.responseText);
                        alert('User Info: ' + JSON.stringify(user));
                    }
                }
                xhr.send();
            });
        }
    </script>
    <script>
        function showEmail() {
            alert('You can contact me at: luonganhhuy10@gmail.com');
        }
    </script>
    <!-- <script>
        function showLockButton(){
            alert('This module is not available at this time!')
        }
    </script> -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>
        var remainingSeconds = <?php echo floor($estimatedTimeLeftInSeconds); ?>;

        function updateTimer() {
        if (remainingSeconds > 0) {
            var hours = Math.floor(remainingSeconds / 3600);
            var minutes = Math.floor((remainingSeconds % 3600) / 60);

            hours = String(hours).padStart(2, '0');
            minutes = String(minutes).padStart(2, '0');

            document.getElementById('remaining_time').textContent = hours + ":" + minutes ;

            if (minutes !== '00') {
                remainingSeconds--;
            } else {
                clearInterval(timerInterval);
                window.location.href = 'logout.php';
            }
        }
        }

        updateTimer();
        var timerInterval = setInterval(updateTimer, 1000);
    </script>
</html>