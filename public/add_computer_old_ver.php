<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MAMAY']) && isset($_POST['NGAYNHAP']) && isset($_POST['TRANGTHAI']) && isset($_POST['NGAYBAOHANH']) && isset($_POST['GIANY']))
{
    $querry = 'INSERT INTO MAYTINH VALUES (:MAMAY, :NGAYNHAP, :TRANGTHAI, :NGAYBAOHANH, :GIANY)';
    $stid = oci_parse($con, $querry);

    $mamay = $_POST['MAMAY'];
    $ngaynhap = $_POST['NGAYNHAP'];
    $trangthai = $_POST['TRANGTHAI'];
    $ngaybaohanh = $_POST['NGAYBAOHANH'];
    $giany = $_POST['GIANY'];

    oci_bind_by_name($stid, ':MAMAY', $mamay);
    oci_bind_by_name($stid, ':NGAYNHAP', $ngaynhap);
    oci_bind_by_name($stid, ':TRANGTHAI', $trangthai);
    oci_bind_by_name($stid, ':NGAYBAOHANH', $ngaybaohanh);
    oci_bind_by_name($stid, ':GIANY', $giany);

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
    <title>Add Computer</title>
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
    </style>
</head>
<body>
    <form action="adding_computer.php" method="post">
        <div>
            <label for="mamay">Mã máy:</label>
            <input type="text" id="mamay" name="MAMAY">
        </div>
        <div>
            <label for="ngaynhap">Ngày nhập máy:</label>
            <input type="text" id="ngaynhap" name="NGAYNHAP">
        </div>
        <div>
            <label for="trangthai">Trạng thái:</label>
            <input type="text" id="trangthai" name="TRANGTHAI">
        </div>
        <div>
            <label for="ngaybaohanh">Ngày bảo hành:</label>
            <input type="text" id="ngaybaohanh" name="NGAYBAOHANH">
        </div>
        <div>
            <label for="giany">Giá niêm yết:</label>
            <input type="number" id="giany" name="GIANY">
        </div>
        <input type="submit" value="Add computer">
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
            <td><?php echo $row['MAMAY'] !== null ? htmlspecialchars($row['MAMAY'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['NGAYNHAP'] !== null ? htmlspecialchars($row['NGAYNHAP'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['TRANGTHAI'] !== null ? htmlspecialchars($row['TRANGTHAI'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['NGAYBAOHANH'] !== null ? htmlspecialchars($row['NGAYBAOHANH'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
            <td><?php echo $row['GIANY'] !== null ? htmlspecialchars($row['GIANY'], ENT_QUOTES, 'UTF-8') : ''; ?></td>
        </tr>
    <?php endforeach; ?>
</table>