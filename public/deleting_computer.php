<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MAMAY']))
{
    $querry = 'DELETE FROM MAYTINH WHERE MAMAY = :MAMAY';
    $stid = oci_parse($con, $querry);

    $mamay = $_POST['MAMAY'];
    oci_bind_by_name($stid, ':MAMAY', $mamay);
    oci_execute($stid);
    oci_commit($con);
}
$querrySelect = "SELECT * FROM MAYTINH ORDER BY TO_NUMBER(MAMAY)";
$sid = oci_parse($con, $querrySelect);
oci_execute($sid);

$rows = oci_fetch_all($sid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Computer</title>
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
    <form action="deleting_computer.php" method="post">
        <label for="Ma may">Delete the computer with ID:</label>
        <input type="text" id="Ma may" name="MAMAY" required>
        <input type="submit" value="Delete Computer">
        <button onclick="goBack()" id="backBtn">Back</button>
    </form>
</body>
</html>
<table border="1">
    <tr>
        <th>Mã máy</th>
        <th>Ngày nhập máy</th>
        <th>Trạng thái</th>
        <th>Ngày bảo hành</th>
        <th>Giá niêm yết</th>
    </tr>
    <?php foreach ($res as $row) : ?>
        <tr>
            <td><?php echo htmlspecialchars($row['MAMAY'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['NGAYNHAP'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['TRANGTHAI'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['NGAYBAOHANH'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['GIANY'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function goBack() {
  window.location.href = 'admin_ui.php#computer-management'; 
}
</script>