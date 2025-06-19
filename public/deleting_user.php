<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['USERNAME']))
{
    $querry = 'DELETE FROM TAIKHOAN WHERE USERNAME = :USERNAME';
    $stid = oci_parse($con, $querry);

    $username = $_POST['USERNAME'];
    oci_bind_by_name($stid, ':USERNAME', $username);
    oci_execute($stid);
    oci_commit($con);
}
$querrySelect = "SELECT * FROM TAIKHOAN";
$sid = oci_parse($con, $querrySelect);
oci_execute($sid);

$rows = oci_fetch_all($sid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <style>
        body {
            font-family: 'Times new Roman', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;    
            font-weight: bold;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 400px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        input[type="text"] {
            width: 95%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px; 
        }
        input[type="submit"] {
            margin-top: 20px;
            background-color: #0080FF;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover{
            background-color: #66FFFF;
        }
        button#backBtn {
            background-color: #008000;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
         width: 100%;
            font-size: 16px;
            margin-top: 10px; 
        }

        button#backBtn:hover {
            background-color: #00FF00;
        }
    </style>
</head>
<body>
    <form action="deleting_user.php" method="post">
        <label for="manv">Delete the user with username:</label>
        <input type="text" id="username" name="USERNAME" required>
        <input type="submit" value="Delete User">
        <button onclick="goBack(event)" id="backBtn">Back</button>
    </form>
</body>
</html>
<table border="1">
    <tr>
        <th>USERNAME</th>
        <th>Pass</th>
        <th>FullName</th>
        <th>Birthday</th>
        <th>Personal ID</th>
        <th>Address</th>
        <th>Sex</th>
        <th>RemainMoney(VND)</th>
        <th>Role</th>
    </tr>
    <?php foreach ($res as $row) : ?>
        <tr>
            <td><?php echo htmlspecialchars($row['USERNAME'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['PASS'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['HOTEN'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['NGAYSINH'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['CCCD'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['DIACHI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['GIOITINH'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['SOTIENCONLAI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo isset($row['ROLE']) ? htmlspecialchars($row['ROLE'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function goBack(event) {
  event.preventDefault();
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#user-management'; 
}
</script>