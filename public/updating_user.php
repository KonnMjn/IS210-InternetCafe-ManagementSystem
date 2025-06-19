<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['USERNAME']))
{
    $manv = $_POST['USERNAME'];

    $query = 'SELECT * FROM TAIKHOAN WHERE USERNAME = :USERNAME';
    $stid = oci_parse($con, $query);
    oci_bind_by_name($stid, ':USERNAME', $manv);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC);

    $hoten = !empty($_POST['PASS']) ? $_POST['PASS'] : $row['PASS'];
    $dienthoai = !empty($_POST['HOTEN']) ? $_POST['HOTEN'] : $row['HOTEN'];
    $ngaysinh = !empty($_POST['NGAYSINH']) ? $_POST['NGAYSINH'] : $row['NGAYSINH'];
    $cccd = !empty($_POST['CCCD']) ? $_POST['CCCD'] : $row['CCCD'];
    $diachi = !empty($_POST['DIACHI']) ? $_POST['DIACHI'] : $row['DIACHI'];
    $gioitinh = !empty($_POST['GIOITINH']) ? $_POST['GIOITINH'] : $row['GIOITINH'];
    $sotienconlai = !empty($_POST['SOTIENCONLAI']) ? $_POST['SOTIENCONLAI'] : $row['SOTIENCONLAI'];
    $loaitk = !empty($_POST['LOAITK']) ? $_POST['LOAITK'] : $row['LOAITK'];

    $querry = 'UPDATE TAIKHOAN SET PASS = :PASS, HOTEN = :HOTEN, NGAYSINH = :NGAYSINH, CCCD = :CCCD, DIACHI = :DIACHI, GIOITINH = :GIOITINH, SOTIENCONLAI = :SOTIENCONLAI, LOAITK = :LOAITK WHERE USERNAME = :USERNAME';
    $stid = oci_parse($con, $querry);

    oci_bind_by_name($stid, ':USERNAME', $manv);
    oci_bind_by_name($stid, ':PASS', $hoten);
    oci_bind_by_name($stid, ':HOTEN', $dienthoai);
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
    <title>Update User</title>
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
        input[type="text"], input[type="number"] {
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
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            color: white;
            text-align: center;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
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
    <form action="updating_user.php" method="post">
    <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="USERNAME" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="text" id="password" name="PASSWORD">
        </div>
        <div>
            <label for="hoten">Full Name:</label>
            <input type="text" id="hoten" name="HOTEN">
        </div>
        <div>
            <label for="ngaysinh">Birthday:</label>
            <input type="date" id="ngaysinh" name="NGAYSINH">
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
        <input type="submit" value="Update User">
        <button onclick="goBack(event)" id="backBtn">Back</button>
    </form>
</body>
</html>
<table border="1">
    <tr>
        <th>Employee ID</th>
        <th>Full Name</th>
        <th>Phone Number</th>
        <th>Date of Birth</th>
        <th>Entry Date</th>
        <th>Personal ID</th>
        <th>Address</th>
        <th>Sex</th>
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
            <td><?php echo htmlspecialchars($row['LOAITK'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
function goBack(event) {
  event.preventDefault();
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#user-management'; 
}
</script>