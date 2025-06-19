<?php
error_reporting(E_ALL);
include('LoginConnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    addComputer($_POST);
}
function addComputer($data)
{
    if(empty($data['NGAYNHAP']) || empty($data['TRANGTHAI']) || empty($data['NGAYBAOHANH']) || empty($data['GIANY'])) {
        return;
    }

    $conn = getDbConnection();
    $querry = "BEGIN SP_THEM_MAYTINH(TO_DATE(:ngaynhap, 'YYYY-MM-DD'), :trangthai, TO_DATE(:ngaybaohanh, 'YYYY-MM-DD'), :giany); END;";
    $stid = oci_parse($conn, $querry);

    oci_bind_by_name($stid, ':ngaynhap', $data['NGAYNHAP']);
    oci_bind_by_name($stid, ':trangthai', $data['TRANGTHAI']);
    oci_bind_by_name($stid, ':ngaybaohanh', $data['NGAYBAOHANH']);
    oci_bind_by_name($stid, ':giany', $data['GIANY']);

    $result = oci_execute($stid);

    // if ($result)
    // {
    //     $_SESSION['message'] = 'Computer added successfully';
    //     $_SESSION['message_type'] = 'success';
    // }
    // else
    // {
    //     $e = oci_error($stid);
    //     $_SESSION['message'] = 'Failed to add employee: ' . $e['message'];
    //     $_SESSION['message_type'] = 'error';
    // }
    oci_free_statement($stid);
    oci_close($conn);
}
$conn = getDbConnection();
$query = "SELECT MAMAY, NGAYNHAP, TRANGTHAI, NGAYBAOHANH, GIANY FROM MAYTINH ORDER BY TO_NUMBER(MAMAY)";
$stid = oci_parse($conn, $query);
oci_execute($stid);
$rows = oci_fetch_all($stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
oci_free_statement($stid);
oci_close($conn);
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
    <form action="adding_computer.php" method="post">
        <div>
            <label for="ngaynhap">Entry Date:</label>
            <input type="date" id="ngaynhap" name="NGAYNHAP" required>
        </div>
        <div>
            <label for="trangthai">Status:</label>
            <input type="text" id="trangthai" name="TRANGTHAI" required>
        </div>
        <div>
            <label for="ngaybaohanh">Expiration Date:</label>
            <input type="date" id="ngaybaohanh" name="NGAYBAOHANH" required>
        </div>
        <div>
            <label for "giany">Price</label>
            <input type="text" id="giany" name="GIANY" required>
        </div>
        <input type="submit" value="Add computer">
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
  window.location.href = 'http://localhost:8080/internetmanagement/admin_ui.php#computer-management'; 
}
</script>