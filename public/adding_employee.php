<?php
error_reporting(E_ALL);
include('LoginConnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    addEmployee($_POST);
}
function addEmployee($data)
{
    if(empty($data['HOTEN']) || empty($data['DIENTHOAI']) || empty($data['NGAYSINH']) || empty($data['NGAYVL']) || empty($data['CCCD']) || empty($data['DIACHI']) || empty($data['GIOITINH'])) {
        return;
    }

    $conn = getDbConnection();
    $querry = "BEGIN SP_THEM_NHANVIEN(:hoten, :dienthoai, TO_DATE(:ngaysinh, 'YYYY-MM-DD'), TO_DATE(:ngayvl, 'YYYY-MM-DD'), :cccd, :diachi, :gioitinh); END;";
    $stid = oci_parse($conn, $querry);

    oci_bind_by_name($stid, ':hoten', $data['HOTEN']);
    oci_bind_by_name($stid, ':dienthoai', $data['DIENTHOAI']);
    oci_bind_by_name($stid, ':ngaysinh', $data['NGAYSINH']);
    oci_bind_by_name($stid, ':ngayvl', $data['NGAYVL']);
    oci_bind_by_name($stid, ':cccd', $data['CCCD']);
    oci_bind_by_name($stid, ':diachi', $data['DIACHI']);
    oci_bind_by_name($stid, ':gioitinh', $data['GIOITINH']);

    $result = oci_execute($stid);
    
    oci_free_statement($stid);
    oci_close($conn);
}
$conn = getDbConnection();
$query = "SELECT * FROM NHANVIEN ORDER BY MANV";
$stid = oci_parse($conn, $query);
oci_execute($stid);
$rows = oci_fetch_all($stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
oci_free_statement($stid);
oci_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
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
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="message <?php echo $_SESSION['message_type']; ?>">
            <?php echo $_SESSION['message']; ?>
        </div>
    <?php endif; ?>
    <form action="adding_employee.php" method="post">
        <div>
            <label for="hoten">Full Name:</label>
            <input type="text" id="hoten" name="HOTEN" required>
        </div>
        <div>
            <label for="dienthoai">Phone Number:</label>
            <input type="text" id="dienthoai" name="DIENTHOAI" required>
        </div>
        <div>
            <label for="ngaysinh">Date of Birth:</label>
            <input type="date" id="ngaysinh" name="NGAYSINH" required>
        </div>
        <div>
            <label for "ngayvl">Entry Date</label>
            <input type="date" id="ngayvl" name="NGAYVL" required>
        </div>
        <div>
            <label for="cccd">Personal ID:</label>
            <input type="text" id="cccd" name="CCCD" required>
        </div>
        <div>
            <label for="diachi">Address:</label>
            <input type="text" id="diachi" name="DIACHI" required>
        </div>
        <div>
            <label for="gioitinh">Sex:</label>
            <input type="text" id="gioitinh" name="GIOITINH" required>
        </div>
        <input type="submit" value="Add employee">
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