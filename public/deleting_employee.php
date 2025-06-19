<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MANV']))
{
    $querry = 'DELETE FROM NHANVIEN WHERE MANV = :MANV';
    $stid = oci_parse($con, $querry);

    $mamay = $_POST['MANV'];
    oci_bind_by_name($stid, ':MANV', $mamay);
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
    <title>Delete Employee</title>
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
    <form action="deleting_employee.php" method="post">
        <label for="manv">Delete the employee with ID:</label>
        <input type="text" id="manv" name="MANV" required>
        <input type="submit" value="Delete Employee">
        <button onclick="goBack()" id="backBtn">Back</button>
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
function goBack() {
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#employee-management'; 
}
</script>