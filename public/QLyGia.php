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
        header('Location: QLyGia.php');
        exit;
    }

    ob_end_flush();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Management</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            .container-fluid {
                padding: 20px;
            }

            .card {
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            }

            .card-header {
                font-size: 20px;
                font-weight: bold;
            }

            .form-group label {
                font-weight: bold;
            }

            .btn {
                margin: 5px;
            }

            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th {
                background-color: #f2f2f2;
                padding: 10px;
                text-align: left;
            }

            .table td {
                padding: 10px;
            }

            .table tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            .edit_product {
                background-color: #007BFF;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
            }

            .delete_product {
                background-color: #f44336;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
            }
        </style>
    </head>
    <body>
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-4">
                    <!-- Biểu Mẫu Thêm/Sửa Sản Phẩm -->
                    <form action="" method="post" id="manage-product">
                        <div class="card">
                            <div class="card-header">
                                Product Form
                            </div>
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
                                        <button class="btn btn-sm btn-primary col-sm-3 offset-md-3">Save</button>
                                        <button class="btn btn-sm btn-default col-sm-3" type="button" onclick="document.getElementById('manage-product').reset()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- FORM Panel -->

                <!-- Table Panel -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Product List
                        </div>
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
                <!-- Table Panel -->
            </div>
        </div>
    </div>
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
                        window.location.href = `QLyGia.php?delete_id=${id}`;
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
    <style>
        td {
            vertical-align: middle !important;
        }
        td p {
            margin: unset;
        }
        .custom-switch {
            cursor: pointer;
        }
        .custom-switch * {
            cursor: pointer;
        }
    </style>