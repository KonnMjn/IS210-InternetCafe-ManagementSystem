<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['MANV']))
{
    $manv = $_POST['MANV'];

    $query = 'SELECT * FROM NHANVIEN WHERE MANV = :MANV';
    $stid = oci_parse($con, $query);
    oci_bind_by_name($stid, ':MANV', $manv);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC);

    $hoten = !empty($_POST['HOTEN']) ? $_POST['HOTEN'] : $row['HOTEN'];
    $dienthoai = !empty($_POST['DIENTHOAI']) ? $_POST['DIENTHOAI'] : $row['DIENTHOAI'];
    $ngaysinh = !empty($_POST['NGAYSINH']) ? $_POST['NGAYSINH'] : $row['NGAYSINH'];
    $ngayvl = !empty($_POST['NGAYVL']) ? $_POST['NGAYVL'] : $row['NGAYVL'];
    $cccd = !empty($_POST['CCCD']) ? $_POST['CCCD'] : $row['CCCD'];
    $diachi = !empty($_POST['DIACHI']) ? $_POST['DIACHI'] : $row['DIACHI'];
    $gioitinh = !empty($_POST['GIOITINH']) ? $_POST['GIOITINH'] : $row['GIOITINH'];

    $querry = 'UPDATE NHANVIEN SET HOTEN = :HOTEN, DIENTHOAI = :DIENTHOAI, NGAYSINH = :NGAYSINH, NGAYVL = :NGAYVL, CCCD = :CCCD, DIACHI = :DIACHI, GIOITINH = :GIOITINH WHERE MANV = :MANV';
    $stid = oci_parse($con, $querry);

    oci_bind_by_name($stid, ':MANV', $manv);
    oci_bind_by_name($stid, ':HOTEN', $hoten);
    oci_bind_by_name($stid, ':DIENTHOAI', $dienthoai);
    oci_bind_by_name($stid, ':NGAYSINH', $ngaysinh);
    oci_bind_by_name($stid, ':NGAYVL', $ngayvl);
    oci_bind_by_name($stid, ':CCCD', $cccd);
    oci_bind_by_name($stid, ':DIACHI', $diachi);
    oci_bind_by_name($stid, ':GIOITINH', $gioitinh);

    oci_execute($stid);
    oci_commit($con);
}

$querrySelect = "SELECT * FROM NHANVIEN ORDER BY MANV";
$sid = oci_parse($con, $querrySelect);
oci_execute($sid);

$rows = oci_fetch_all($sid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Employee</title>
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
    <form action="updating_employee.php" method="post">
        <div>
            <label for="manv">Employee ID:</label>
            <input type="text" id="manv" name="MANV" required>
        </div>
        <div>
            <label for="hoten">Full Name:</label>
            <input type="text" id="hoten" name="HOTEN">
        </div>
        <div>
            <label for="dienthoai">Phone Number:</label>
            <input type="text" id="dienthoai" name="DIENTHOAI">
        </div>
        <div>
            <label for="ngaysinh">Date of Birth:</label>
            <input type="text" id="ngaysinh" name="NGAYSINH">
        </div>
        <div>
            <label for="ngayvl">Entry Date</label>
            <input type="text" id="ngayvl" name="NGAYVL">
        </div>
        <div>
            <label for="cccd">Personal ID:</label>
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
        <input type="submit" value="Update employee">
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
            <td><?php echo htmlspecialchars($row['MANV'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['HOTEN'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['DIENTHOAI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['NGAYSINH'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['NGAYVL'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['CCCD'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['DIACHI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['GIOITINH'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function goBack(event) {
  event.preventDefault();
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#employee-management'; 
}
</script>