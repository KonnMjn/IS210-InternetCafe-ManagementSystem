<?php
include('function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_order'])) {
        $id = $_POST['id'];
        deleteOrder($id);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['update_order'])) {
        $id = $_POST['id'];
        $productsArray = $_POST['products'];
        updateOrder($id, $productsArray);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['create_order'])) {
        $productsArray = $_POST['products'];
        $username = 'testuser'; // Replace with actual username
        $manv = '0001'; // Replace with actual employee ID
        createOrder($productsArray, $username, $manv);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$orders = getOrders();
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        input[type=checkbox] {
            -ms-transform: scale(1.3);
            -moz-transform: scale(1.3);
            -webkit-transform: scale(1.3);
            -o-transform: scale(1.3);
            transform: scale(1.3);
            padding: 10px;
            cursor: pointer;
        }
        td {
            vertical-align: middle !important;
        }
        td p {
            margin: unset;
        }
        img {
            max-width: 100px;
            max-height: 150px;
        }
        .edit-form, .create-form {
            display: none;
        }
        .product-input-group {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <h2>Order List</h2>
                <button class="btn btn-primary float-right" id="add-order-btn">Add Order</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Orders</b>
                    </div>
                    <div class="card-body">
                        <table id="orderList" class="table table-condensed table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Date</th>
                                <th>Order Number</th>
                                <th>Products</th>
                                <th>Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $i => $order): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td>
                                        <p><b><?= date("M d, Y", strtotime($order['NGAYHD'])) ?></b></p>
                                    </td>
                                    <td>
                                        <p><b><?= $order['SOHD'] ?></b></p>
                                    </td>
                                    <td>
                                        <p><b><?= $order['PRODUCTS'] ?></b></p>
                                    </td>
                                    <td>
                                        <p class="text-right"><b><?= number_format($order['PRICE'], 0) ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary edit_order" type="button" data-id="<?= $order['SOHD'] ?>" data-products="<?= $order['PRODUCTS'] ?>" data-price="<?= $order['PRICE'] ?>">Edit</button>
                                        <form method="POST" action="" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?= $order['SOHD']; ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" name="delete_order">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Form -->
        <div class="row edit-form">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Edit Order</b>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="form-group">
                                <label for="edit-products-container">Products</label>
                                <div id="edit-products-container"></div>
                            </div>
                            <div class="form-group">
                            </div>
                            <button type="submit" name="update_order" class="btn btn-primary">Update Order</button>
                            <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Edit Form -->

        <!-- Create Form -->
        <div class="row create-form">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Create Order</b>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="create-products-container">Products</label>
                                <div id="create-products-container"></div>
                                <button type="button" class="btn btn-success add-product-btn">Add Product</button>
                            </div>
                            <button type="submit" name="create_order" class="btn btn-primary">Create Order</button>
                            <button type="button" class="btn btn-secondary cancel-create">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Create Form -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('#orderList').dataTable();

        $('#add-order-btn').click(function(){
            $('.create-form').show();
            $('html, body').animate({
                scrollTop: $(".create-form").offset().top
            }, 1000);
        });

        $('.cancel-create').click(function(){
            $('.create-form').hide();
        });

        $(document).on('click', '.add-product-btn', function(){
            var productIndex = $('.product-input-group').length;
            var productHtml = '<div class="product-input-group">';
            productHtml += '<label for="create-product-' + productIndex + '">Product</label>';
            productHtml += '<select class="form-control create-product" id="create-product-' + productIndex + '" name="products[]">';
            <?php foreach ($products as $product): ?>
            productHtml += '<option value="<?= $product; ?>"><?= $product; ?></option>';
            <?php endforeach; ?>
            productHtml += '</select>';
            productHtml += '<label for="create-quantity-' + productIndex + '">Quantity</label>';
            productHtml += '<input type="number" class="form-control create-quantity" id="create-quantity-' + productIndex + '" name="products[]" value="1">';
            productHtml += '</div>';
            $('#create-products-container').append(productHtml);
        });

        $('.cancel-edit').click(function(){
            $('.edit-form').hide();
        });

        $('.edit_order').click(function(){
            var id = $(this).data('id');
            var products = $(this).data('products').split(', ');
            var price = $(this).data('price');

            var productsHtml = '';
            for (var i = 0; i < products.length; i++) {
                var product = products[i].split(' (');
                var productName = product[0];
                var quantity = product[1].split(')')[0];
                productsHtml += '<div class="product-input-group">';
                productsHtml += '<label for="edit-product-' + i + '">' + productName + '</label>';
                productsHtml += '<input type="hidden" name="products[]" id="product-' + i + '" value="' + productName + '-' + quantity + '">';
                productsHtml += '<input type="number" class="form-control quantity" id="edit-product-' + i + '" name="edit-product-' + i + '" value="' + quantity + '" data-index="' + i + '">';
                productsHtml += '</div>';
            }
            $('#edit-products-container').html(productsHtml);

            $('#edit-price').val(price);

            $('.edit-form').show();
            $('html, body').animate({
                scrollTop: $(".edit-form").offset().top
            }, 1000);

            $('#edit-id').val(id);
        });

        $(document).on('change', '.quantity', function() {
            var index = $(this).data('index');
            var quantity = $(this).val();
            var productName = $('#product-' + index).val().split('-')[0];
            $('#product-' + index).val(productName + '-' + quantity);
            var total = 0;
            $('#edit-products-container .product-input-group').each(function(){
                var price = parseFloat($(this).find('.quantity').data('price'));
                var quantity = parseInt($(this).find('.quantity').val());
                total += price * quantity;
            });
            $('#edit-price').val(total);
        });
    });
</script>
</body>
</html>
