<?php
include ('LoginConnect.php');
    $message = '';
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $money = $_POST['money'];

        $conn = getDbConnection();

        $query = "SELECT * FROM SUDUNG WHERE USERNAME = :username AND GIOKETTHUC IS NULL";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':username', $username);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);

        if ($row) {
            $procedure = 'BEGIN TAM_DUNG_VA_CAP_NHAT_TIEUTHU(:username, :money); END;';
            $stid = oci_parse($conn, $procedure);
            oci_bind_by_name($stid, ':username', $username);
            oci_bind_by_name($stid, ':money', $money);
            oci_execute($stid);
            $message = 'Money added successfully!';
        } else {
            $query = "UPDATE TAIKHOAN SET SOTIENCONLAI = SOTIENCONLAI + :money WHERE USERNAME = :username";
            $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ':username', $username);
            oci_bind_by_name($stid, ':money', $money);
            oci_execute($stid);
            $message = 'Money added successfully!';
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Money</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Insert Money</div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-success">
                                <?= $message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="inserting_user.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="money">Money to Add:</label>
                                <input type="number" id="money" name="money" class="form-control" required>
                            </div>
                            <button type="submit" name="add" class="btn btn-primary">Add</button>
                        </form>
                        <a href="admin_ui.php" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>