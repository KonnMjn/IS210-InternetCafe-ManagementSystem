<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['MAMAY']))
{
    $mamay = $_POST['MAMAY'];

    // Fetch the existing data for the given MAMAY
    $query = 'SELECT MAMAY, NGAYNHAP, TRANGTHAI, NGAYBAOHANH, GIANY FROM MAYTINH WHERE MAMAY = :MAMAY';
    $stid = oci_parse($con, $query);
    oci_bind_by_name($stid, ':MAMAY', $mamay);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC);

    // Use the existing data as the default value if the new value is not provided
    $ngaynhap = !empty($_POST['NGAYNHAP']) ? $_POST['NGAYNHAP'] : $row['NGAYNHAP'];
    $trangthai = !empty($_POST['TRANGTHAI']) ? $_POST['TRANGTHAI'] : $row['TRANGTHAI'];
    $ngaybaohanh = !empty($_POST['NGAYBAOHANH']) ? $_POST['NGAYBAOHANH'] : $row['NGAYBAOHANH'];
    $giany = !empty($_POST['GIANY']) ? $_POST['GIANY'] : $row['GIANY'];

    $querry = 'UPDATE MAYTINH SET NGAYNHAP = :NGAYNHAP, TRANGTHAI = :TRANGTHAI, NGAYBAOHANH = :NGAYBAOHANH, GIANY = :GIANY WHERE MAMAY = :MAMAY';
    $stid = oci_parse($con, $querry);

    oci_bind_by_name($stid, ':MAMAY', $mamay);
    oci_bind_by_name($stid, ':NGAYNHAP', $ngaynhap);
    oci_bind_by_name($stid, ':TRANGTHAI', $trangthai);
    oci_bind_by_name($stid, ':NGAYBAOHANH', $ngaybaohanh);
    oci_bind_by_name($stid, ':GIANY', $giany);

    oci_execute($stid);
    oci_commit($con);
}

$querrySelect = "SELECT MAMAY, NGAYNHAP, TRANGTHAI, NGAYBAOHANH, GIANY FROM MAYTINH ORDER BY TO_NUMBER(MAMAY)";
$sid = oci_parse($con, $querrySelect);
oci_execute($sid);

$rows = oci_fetch_all($sid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Computer</title>
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
    <form action="updating_computer.php" method="post">
        <div>
            <label for="mamay">Computer ID:</label>
            <input type="text" id="mamay" name="MAMAY">
        </div>
        <div>
            <label for="ngaynhap">Entry Date:</label>
            <input type="text" id="ngaynhap" name="NGAYNHAP">
        </div>
        <div>
            <label for="trangthai">Status:</label>
            <input type="text" id="trangthai" name="TRANGTHAI">
        </div>
        <div>
            <label for="ngaybaohanh">Expiration Date:</label>
            <input type="text" id="ngaybaohanh" name="NGAYBAOHANH">
        </div>
        <div>
            <label for="giany">Price:</label>
            <input type="number" id="giany" name="GIANY">
        </div>
        <input type="submit" value="Update computer">
        <button onclick="goBack(event)" id="backBtn">Back</button>
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
function goBack(event) {
    event.preventDefault();
    window.location.href = 'admin_ui.php#computer-management'; 
}
</script>