<?php
session_start();
include 'LoginConnect.php';
$loginSuccess = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

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

        if ($row) {
            $stored_password = $row['PASS'];

            if ($stored_password === $password) {
                $_SESSION['username'] = $username;
                oci_free_statement($stid);
                oci_close($con);
                $loginSuccess = true;
                if ($username == '123456789' && $password == '1')
                {
                    $_SESSION['role'] = 'QL';
                    header("Location: admin_ui.php");
                }
                else
                {
                    $_SESSION['role'] = 'KH';
                    header("Location: user_ui.php?username=" . urlencode($username));
                }
            } else {
                $error = "Invalid username or password.";
                oci_free_statement($stid);
                oci_close($con);
            }
        } else {
            $error = "Invalid username or password.";
            oci_free_statement($stid);
            oci_close($con);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internet Cafe Login</title>
    <style>
        body {
            font-family: "Times New Roman", sans-serif;
            background-color: #33FFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Internet Cafe Login</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
</div>

<!-- Modal for displaying messages -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <p id="modalMessage"></p>
    </div>
</div>

<script>
    // Display the modal if there's a message
    var loginSuccess = <?php echo json_encode($loginSuccess); ?>;
    var errorMessage = <?php echo json_encode($error); ?>;

    if (loginSuccess || errorMessage) {
        var modal = document.getElementById('myModal');
        var modalMessage = document.getElementById('modalMessage');
        modalMessage.innerText = loginSuccess ? "Login successful!" : errorMessage;
        modal.style.display = "block";

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
</script>
</body>
</html>