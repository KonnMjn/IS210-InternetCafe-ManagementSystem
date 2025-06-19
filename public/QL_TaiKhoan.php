<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Management</title>
    <style>
        .return-btn {
            position: absolute;
            top: 20px;
            right: 450px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
        }
        .popup.show {
            display: block;
        }
        .popup h2 {
            margin-top: 0;
        }
        .popup form {
            display: flex;
            flex-direction: column;
        }
        .popup form label, .popup form input, .popup form button {
            margin-bottom: 10px;
        }
        .message {
            cursor: pointer;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            border-radius: 5px;
        }
        .message.success {
            color: green;
            border-color: green;
        }
        .message.error {
            color: red;
            border-color: red;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .actions-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .buttons-container, .search-container {
            display: flex;
            align-items: center;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input {
            margin-right: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_ui.php" class="btn btn-secondary return-btn">Return to Admin Menu</a>
        <h1>Account List</h1>
        <?php include 'TK_function.php'; ?>
        <?php
        function displayMessage() {
            if (isset($_SESSION['message'])) {
                $message = $_SESSION['message'];
                $type = $_SESSION['message_type'];
                echo "<div class='message {$type}' onclick='this.style.display=\"none\";'>{$message}</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
        }
        displayMessage();

        $searchUsername = '';

        if (isset($_GET['search_username'])) {
            $searchUsername = $_GET['search_username'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                if (isset($_POST['delete_username'])) {
                    deleteAccount($_POST['delete_username']);
                }
                header("Location: QL_TaiKhoan.php");
                exit();
            }
            if (isset($_POST['add_money'])) {
                $username = $_POST['USERNAME'];
                $amount = $_POST['AMOUNT'];
                addMoney($username, $amount);
                header("Location: QL_TaiKhoan.php");
                exit;
            }

            $data = [
                'USERNAME' => $_POST['USERNAME'],
                'PASS' => $_POST['PASS'],
                'HOTEN' => $_POST['HOTEN'],
                'NGAYSINH' => $_POST['NGAYSINH'],
                'CCCD' => $_POST['CCCD'],
                'DIACHI' => $_POST['DIACHI'],
                'GIOITINH' => $_POST['GIOITINH'],
                'SOTIENCONLAI' => $_POST['SOTIENCONLAI'],
                'LOAITK' => $_POST['LOAITK']
            ];
            if (isset($_POST['edit'])) {
                editAccount($data);
            } else {
                addAccount($data);
            }
            header("Location: QL_TaiKhoan.php");
            exit;
        }

        if (isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit' || $_GET['action'] === 'add_money')) {
            $account = [
                'USERNAME' => '',
                'PASS' => '',
                'HOTEN' => '',
                'NGAYSINH' => '',
                'CCCD' => '',
                'DIACHI' => '',
                'GIOITINH' => '',
                'SOTIENCONLAI' => '',
                'LOAITK' => ''
            ];

            if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
                $conn = getDbConnection();
                $query = "SELECT * FROM TAIKHOAN WHERE USERNAME = :username";
                $stid = oci_parse($conn, $query);
                oci_bind_by_name($stid, ':username', $_GET['id']);
                oci_execute($stid);
                $account = oci_fetch_assoc($stid);
                oci_free_statement($stid);
                oci_close($conn);
            }
        ?>
             <div class="popup show" id="popup-form">
                <form action="QL_TaiKhoan.php" method="post">
                    <h2><?php echo $_GET['action'] === 'edit' ? 'Edit Account' : ($_GET['action'] === 'add' ? 'Add Account' : 'Add Money'); ?></h2>
                    <?php if ($_GET['action'] === 'add_money') { ?>
                        <label for="USERNAME">Username:</label>
                        <input type="text" id="USERNAME" name="USERNAME" required>
                        <label for="AMOUNT">Amount:</label>
                        <input type="number" id="AMOUNT" name="AMOUNT" required>
                        <button type="submit" name="add_money">Add Money</button>
                    <?php } else { ?>
                        <label for="USERNAME">Username:</label>
                        <input type="text" id="USERNAME" name="USERNAME" value="<?php echo $account['USERNAME']; ?>">
                        <label for="PASS">PASS:</label>
                        <input type="text" id="PASS" name="PASS" value="<?php echo $account['PASS']; ?>" required>
                        <label for="HOTEN">Name:</label>
                        <input type="text" id="HOTEN" name="HOTEN" value="<?php echo $account['HOTEN']; ?>" required>
                        <label for="NGAYSINH">Date of Birth:</label>
                        <input type="date" id="NGAYSINH" name="NGAYSINH" value="<?php echo $account['NGAYSINH']; ?>" required>
                        <label for="CCCD">CCCD:</label>
                        <input type="text" id="CCCD" name="CCCD" value="<?php echo $account['CCCD']; ?>" required>
                        <label for="DIACHI">Address:</label>
                        <input type="text" id="DIACHI" name="DIACHI" value="<?php echo $account['DIACHI']; ?>" required>
                        <label for="GIOITINH">Gender:</label>
                        <input type="text" id="GIOITINH" name="GIOITINH" value="<?php echo $account['GIOITINH']; ?>" required>
                        <label for="SOTIENCONLAI">Remaining Balance:</label>
                        <input type="text" id="SOTIENCONLAI" name="SOTIENCONLAI" value="<?php echo $account['SOTIENCONLAI']; ?>" required>
                        <label for="LOAITK">Account Type:</label>
                        <input type="text" id="LOAITK" name="LOAITK" value="<?php echo $account['LOAITK']; ?>" required>
                        <button type="submit" name="<?php echo $_GET['action'] === 'edit' ? 'edit' : 'add'; ?>">
                            <?php echo $_GET['action'] === 'edit' ? 'Save Changes' : 'Add Account'; ?>
                        </button>
                    <?php } ?>
                    <button type="button" onclick="closePopup()">Cancel</button>
                </form>
            </div>
            <script>
                function closePopup() {
                    document.getElementById('popup-form').style.display = 'none';
                    window.location.href = 'QL_TaiKhoan.php';
                }
            </script>
        <?php } else { ?>
            <div class="actions-row">
                <div class="buttons-container">
                    <a href="QL_TaiKhoan.php?action=add" class="button">Add Account</a>
                    <a href="QL_TaiKhoan.php?action=add_money" class="button">Add Money</a>
                </div>

                <div class="search-container">
                    <form class="search-bar" action="QL_TaiKhoan.php" method="get">
                        <input type="text" name="search_username" placeholder="Search by username">
                        <a href="javascript:;" onclick="this.closest('form').submit()" class="button">Search</a>
                    </form>
                </div>
            </div>
            <table>
                <tr>
                    <th>USERNAME</th>
                    <th>PASS</th>
                    <th>HOTEN</th>
                    <th>NGAYSINH</th>
                    <th>CCCD</th>
                    <th>DIACHI</th>
                    <th>GIOITINH</th>
                    <th>SOTIENCONLAI</th>
                    <th>LOAITK</th>
                    <th>Actions</th>
                </tr>
                <?php showAccounts($searchUsername); ?>
            </table>
        <?php } ?>
    </div>
</body>
</html>