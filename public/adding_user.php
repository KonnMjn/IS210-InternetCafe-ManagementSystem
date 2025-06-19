<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['USERNAME']) && isset($_POST['PASS']) && isset($_POST['HOTEN']) && isset($_POST['NGAYSINH']) && isset($_POST['CCCD']) && isset($_POST['DIACHI']) && isset($_POST['GIOITINH']) && isset($_POST['SOTIENCONLAI']) && isset($_POST['LOAITK']))
{
    $querry = 'INSERT INTO TAIKHOAN VALUES (:USERNAME, :PASS, :HOTEN, :NGAYSINH, :CCCD, :DIACHI, :GIOITINH, :SOTIENCONLAI, :LOAITK)';
    $stid = oci_parse($con, $querry);

    $username = $_POST['USERNAME'];
    $pass = $_POST['PASS'];
    $hoten = $_POST['HOTEN'];
    $ngaysinh = $_POST['NGAYSINH'];
    $cccd = $_POST['CCCD'];
    $diachi = $_POST['DIACHI'];
    $gioitinh = $_POST['GIOITINH'];
    $sotienconlai = $_POST['SOTIENCONLAI'];
    $loaitk = $_POST['LOAITK'];

    oci_bind_by_name($stid, ':USERNAME', $username);
    oci_bind_by_name($stid, ':PASS', $pass);
    oci_bind_by_name($stid, ':HOTEN', $hoten);
    oci_bind_by_name($stid, ':NGAYSINH', $ngaysinh);
    oci_bind_by_name($stid, ':CCCD', $cccd);
    oci_bind_by_name($stid, ':DIACHI', $diachi);
    oci_bind_by_name($stid, ':GIOITINH', $gioitinh);
    oci_bind_by_name($stid, ':SOTIENCONLAI', $sotienconlai);
    oci_bind_by_name($stid, ':LOAITK', $loaitk);

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
    <title>Add User</title>
    <style>
        body {
            font-family: 'Times New Roman', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            width: 500px;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #0080FF;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
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
    <form action="adding_user.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="USERNAME">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="text" id="password" name="PASS">
        </div>
        <div>
            <label for="hoten">Full Name:</label>
            <input type="text" id="hoten" name="HOTEN">
        </div>
        <div>
            <label for="ngaysinh">Birthday:</label>
            <input type="text" id="ngaysinh" name="NGAYSINH">
        </div>
        <div>
            <label for="cccd">PersonalID:</label>
            <input type="text" id="cccd" name="CCCD">
        </div>
        <div>
            <label for="diachi">Address:</label>
            <input type="text" id="diachi" name="DIACHI">
        </div>
        <div>
            <label for="gioitinh">Sex:</label>
            <input type="text" id="gioitinh" name="GIOITINH">
        </div>
        <div>
            <label for="sotienconlai">RemainMoney(VND):</label>
            <input type="text" id="sotienconlai" name="SOTIENCONLAI">
        </div>
        <div>
            <label for="loaitk">Role:</label>
            <input type="text" id="loaitk" name="LOAITK">
        </div>
        <input type="submit" value="Add User">
        <button onclick="goBack(event)" id="backBtn">Back</button>
    </form>
</body>
</html>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Password</th>
        <th>FullName</th>
        <th>Birthday</th>
        <th>PersonalID</th>
        <th>Address</th>
        <th>Sex</th>
        <th>RemainMoney(VND)</th>
        <th>Role</th>
    </tr>
    <?php foreach ($res as $row) : ?>
        <tr>
            <td><?php echo $row['USERNAME'] !== null ? htmlspecialchars($row['USERNAME'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['PASS'] !== null ? htmlspecialchars($row['PASS'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['HOTEN'] !== null ? htmlspecialchars($row['HOTEN'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['NGAYSINH'] !== null ? htmlspecialchars($row['NGAYSINH'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['CCCD'] !== null ? htmlspecialchars($row['CCCD'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['DIACHI'] !== null ? htmlspecialchars($row['DIACHI'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['GIOITINH'] !== null ? htmlspecialchars($row['GIOITINH'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['SOTIENCONLAI'] !== null ? htmlspecialchars($row['SOTIENCONLAI'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['LOAITK'] !== null ? htmlspecialchars($row['LOAITK'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
function goBack(event) {
  event.preventDefault();
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#user-management'; 
}
</script>