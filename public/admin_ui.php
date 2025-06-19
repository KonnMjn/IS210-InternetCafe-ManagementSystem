<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

ob_start();
include('function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $status = isset($_POST['status']) ? 1 : 0;
    $category_id = $_POST['category_id'];
    $donvitinh = $_POST['donvitinh'];

    addOrUpdateProduct($id, $name, $price, $status, $category_id, $donvitinh);
    displayProducts();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    deleteProduct($id);
    header('Location: admin_ui.php');
    exit;
}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Internet Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .sidebar {
            width: 190px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            color: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); 
        }
        .sidebar h2 {
            color: #fff;
            text-transform: uppercase;
            text-align: center; 
            margin-bottom: 30px; 
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            padding: 10px; 
            border-radius: 4px; 
        }
        .sidebar a:hover {
            background-color: #0080FF; 
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .active-button {
            background-color: #0080FF;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            width: 20%;
            font-size: 16px;
            margin-top: 10px; 
            display: inline-block;
        }
        .active-button:hover{
            background-color: #66FFFF;
        }
        .sidebar a.logout {
            background-color: #ff4b5c; 
            color: #fff; 
        }

        .sidebar a.logout:hover {
            background-color: #e60023; 
        }
        button#backBtn {
            background-color: #0080FF;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            width: 50%;
            font-size: 16px;
            margin-top: 10px; 
        }

        button#backBtn:hover {
            background-color: #66FFFF;
        }
        .container-fluid {
    margin-top: 20px;
}

        .card {
            border: none;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #4b5d67;
            color: white;
            font-size: 20px;
            padding: 10px 15px;
        }

        .card-body {
            padding: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .custom-select {
            border-radius: 0;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 5px;
        }

        .table thead tr {
            background-color: #4b5d67;
            color: white;
        }

        .table tbody tr {
            background-color: #f7f7f7;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        #backBtn {
            background-color: #4b5d67;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
        }

        #backBtn:hover {
            background-color: #3a4b57;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Internet Management</h2>
        <a href="#welcome" class="tab-link" onclick="openTab('welcome')">Welcome</a>
        <a href="#user-management" class="tab-link" onclick="openTab('user-management')">User Management</a>
        <a href="#employee-management" class="tab-link" onclick="openTab('employee-management')">Employee Management</a>
        <a href="#computer-management" class="tab-link" onclick="openTab('computer-management')">Computer Management</a>
        <a href="#usage-management" class = "tab-link" onclick="openTab('usage-management')">Usage Management</a>
        <a href="#price-management" class = "tab-link" onclick="openTab('price-management')">Price Management</a>
        <a href="#transaction-management" class = "tab-link" onclick="openTab('transaction-management')">Transaction Management</a>
        <a href="logoutadmin.php" class="tab-link logout">Logout</a>
    </div>
    <div class="content">
        <div id="welcome" class="tab-content">
            <h1>Welcome back, have a good day!</h1>
        </div>
        <div id="user-management" class="tab-content">
            <h1>User Management</h1>
            <table>
                <?php
                $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
                if (!$conn) {
                    $e = oci_error();
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }

                $stid = oci_parse($conn, 'SELECT * FROM TAIKHOAN');
                if (!$stid) {
                    $e = oci_error($conn);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                $r = oci_execute($stid);
                if (!$r) {
                    $e = oci_error($stid);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                echo "<table>\n";
                echo "<tr><th>Username</th><th>Password</th><th>FullName</th><th>Birthday</th><th>PersonalID</th><th>Address</th><th>Sex</th><th>RemainMoney(VND)</th><th>Role</th></tr>";
                while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    foreach ($row as $item) {
                        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                    }
                    echo "</tr>\n";
                }
                echo "</table>\n";
                ?>
            </table>
            <div>
                <button class="active-button" onclick="location.href='adding_user.php'">Add</button>
                <button class="active-button" onclick="location.href='updating_user.php'">Update</button>
                <button class="active-button" onclick="location.href='deleting_user.php'">Delete</button>
                <button class="active-button" onclick="location.href='inserting_user.php'">Insert</button>
            </div>
        </div>
        <div id="employee-management" class="tab-content">
            <h1>Employee Management</h1>
            <table>
                <?php
                $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
                if (!$conn) {
                    $e = oci_error();
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }

                $stid = oci_parse($conn, 'SELECT * FROM NHANVIEN ORDER BY MANV');
                if (!$stid) {
                    $e = oci_error($conn);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                $r = oci_execute($stid);
                if (!$r) {
                    $e = oci_error($stid);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                echo "<table>\n";
                echo "<tr><th>EmployeeID</th><th>FullName</th><th>Phone Number</th><th>Birthday</th><th>EntryDate</th><th>PersonalID</th><th>Address</th><th>Sex</th></tr>";
                while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    foreach ($row as $item) {
                        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                    }
                    echo "</tr>\n";
                }
                echo "</table>\n";
                ?>
            </table>
            <div>
                <button class="active-button" onclick="location.href='adding_employee.php'">Add</button>
                <button class="active-button" onclick="location.href='updating_employee.php'">Update</button>
                <button class="active-button" onclick="location.href='deleting_employee.php'">Delete</button>
            </div>
        </div>
        <div id="usage-management" class="tab-content">
            <h1>Usage Management</h1>
            <table>
                <thead>
                <tr>
                        <th>Username</th>
                        <th>Computer ID</th>
                        <th>Consumption</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody id="usage-table">
                    <?php
                    $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
                    if (!$conn) {
                        $e = oci_error();
                        echo "Problem occurred during connection: " . $e['message'];
                    }

                    $stid = oci_parse($conn, 'SELECT * FROM SUDUNG ORDER BY TO_NUMBER(MAMAY)');
                    if (!$stid) {
                        $e = oci_error($conn);
                        echo "Problem occurred during statement preparation: " . $e['message'];
                    }

                    $r = oci_execute($stid);
                    if (!$r) {
                        $e = oci_error($stid);
                        echo "Problem occurred during statement execution: " . $e['message'];
                    }

                    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                        echo "<tr>\n";
                        foreach ($row as $item) {
                            echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                        }
                        echo "</tr>\n";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="computer-management" class="tab-content">
            <h1>Computer Management</h1>
            <table>
                <?php
                $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
                if (!$conn) {
                    $e = oci_error();
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }

                $stid = oci_parse($conn, 'SELECT MAMAY, NGAYNHAP, TRANGTHAI, NGAYBAOHANH, GIANY FROM MAYTINH');
                if (!$stid) {
                    $e = oci_error($conn);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                $r = oci_execute($stid);
                if (!$r) {
                    $e = oci_error($stid);
                    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                }
                echo "<table>\n";
                echo "<tr><th>Computer ID</th><th>Entry Date</th><th>Status</th><th>Expiration Date</th><th>Price</th></tr>";
                while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    foreach ($row as $item) {
                        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                    }
                    echo "</tr>\n";
                }
                echo "</table>\n";
                ?>
            </table>
            <div>
                <button class="active-button" onclick="location.href='adding_computer.php'">Add</button>
                <button class="active-button" onclick="location.href='updating_computer.php'">Update</button>
                <button class="active-button" onclick="location.href='deleting_computer.php'">Delete</button>
            </div>
        </div>
        <div id="usage-management" class="tab-content">
            <h1>Usage Management</h1>
            <table>
                <thead>
                <tr>
                        <th>Username</th>
                        <th>Computer ID</th>
                        <th>Consumption</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody id="usage-table">
                    <?php
                    $conn = oci_connect('sinhvienuit', 'sinhvienuit', 'localhost/orcl');
                    if (!$conn) {
                        $e = oci_error();
                        echo "Problem occurred during connection: " . $e['message'];
                    }

                    $stid = oci_parse($conn, 'SELECT * FROM SUDUNG ORDER BY TO_NUMBER(MAMAY)');
                    if (!$stid) {
                        $e = oci_error($conn);
                        echo "Problem occurred during statement preparation: " . $e['message'];
                    }

                    $r = oci_execute($stid);
                    if (!$r) {
                        $e = oci_error($stid);
                        echo "Problem occurred during statement execution: " . $e['message'];
                    }

                    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                        echo "<tr>\n";
                        foreach ($row as $item) {
                            echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                        }
                        echo "</tr>\n";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="price-management" class="tab-content">
            <h1>Price Management</h1>
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-4">
                            <form action="" method="post" id="manage-product">
                                <div class="card">
                                    <div class="card-header">Product Form</div>
                                    <div class="card-body">
                                        <input type="hidden" name="id">
                                        <div class="form-group">
                                            <label class="control-label">Category</label>
                                            <select name="category_id" id="category_id" class="custom-select select2">
                                                <option value=""></option>
                                                <?php
                                                $conn = getDbConnection();
                                                $query = "SELECT distinct DANHMUC FROM SANPHAM ORDER BY DANHMUC ASC";
                                                $stid = oci_parse($conn, $query);
                                                oci_execute($stid);
                                                while ($row = oci_fetch_assoc($stid)) {
                                                    echo '<option value="'. htmlspecialchars($row['DANHMUC']) .'">'. htmlspecialchars($row['DANHMUC']) .'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Name</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Unit</label>
                                            <input type="text" class="form-control" name="donvitinh">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Price</label>
                                            <input type="number" class="form-control text-right" name="price">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" checked value="1">
                                                <label class="custom-control-label" for="status">Available</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button id="backBtn">Save</button>
                                                <button id="backBtn" type="button" onclick="document.getElementById('manage-product').reset()">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">Product List</div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Product Info</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="product-list">
                                        <?php displayProducts(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="transaction-management" class="tab-content">
            <h1>Transaction Management</h1>
        </div>

        <!-- Add more tab content divs as needed -->
    </div>

    <script>
        function openTab(tabId) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none"; 
            }
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabId).style.display = "block";
            event.currentTarget.className += " active";
            localStorage.setItem('currentTab', tabId);
        }
    </script>
    <script>
    var savedTab = localStorage.getItem('currentTab');
    if (savedTab) {
        openTab(savedTab);
    } else {
        openTab('welcome');
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script>
        setInterval(function(){
            $.get('fetch_usage.php', function(data) {
                var usageData = JSON.parse(data);
                var table = '';
                for(var i=0; i<usageData.length; i++) {
                    table += '<tr>';
                    table += '<td>' + usageData[i].USERNAME + '</td>';
                    table += '<td>' + usageData[i].MAMAY + '</td>';
                    table += '<td>' + usageData[i].CONSUMPTION + '</td>';
                    table += '<td>' + usageData[i].GIOBATDAU + '</td>';
                    table += '<td>' + usageData[i].GIOKETTHUC + '</td>';
                    table += '</tr>';
                }
                $('#usage-table').html(table);
            });
        }, 1000);
        </script>
        <script>
    const setupProductButtons = function() {
        document.querySelectorAll('.edit_product').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const status = this.dataset.status;
                const category_id = this.dataset.category_id;

                document.querySelector('input[name="id"]').value = id;
                document.querySelector('input[name="name"]').value = name;
                document.querySelector('input[name="price"]').value = price;
                document.querySelector('input[name="status"]').checked = status == 1;
                document.querySelector('select[name="category_id"]').value = category_id;
            });
        });

        document.querySelectorAll('.delete_product').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Are you sure you want to delete this product?')) {
                    window.location.href = `admin_ui.php?delete_id=${id}`;
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        setupProductButtons();

        document.getElementById('manage-product').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = this;
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('product-list').innerHTML = data;
                    form.reset();
                    setupProductButtons();
                });
        });
    });
</script>
</body>
</html>