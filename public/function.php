<?php
include('LoginConnect.php');

function deleteOrder($id) {
    $conn = getDbConnection();
    $query = "DELETE FROM HOADON WHERE SOHD = :id";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':id', $id);
    oci_execute($stid);
    oci_free_statement($stid);
    oci_close($conn);
}

function updateOrder($id, $productsArray) {
    $conn = getDbConnection();
    $totalPrice = 0;

    $deleteQuery = "DELETE FROM CTHD WHERE SOHD = :id";
    $deleteStid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($deleteStid, ':id', $id);
    oci_execute($deleteStid);
    oci_free_statement($deleteStid);

    foreach ($productsArray as $product) {
        $product = trim($product);
        $productName = explode('-', $product)[0];
        $quantity = explode('-', $product)[1];

        $query = "SELECT MASP, GIATIEN FROM SANPHAM WHERE TENSP = :productName";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':productName', $productName);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $product_id = $row['MASP'];
        $product_price = $row['GIATIEN'];
        oci_free_statement($stid);

        $totalPrice += $product_price * $quantity;

        $insertQuery = "INSERT INTO CTHD (SOHD, MASP, SL) VALUES (:id, :product_id, :quantity)";
        $insertStid = oci_parse($conn, $insertQuery);
        oci_bind_by_name($insertStid, ':id', $id);
        oci_bind_by_name($insertStid, ':product_id', $product_id);
        oci_bind_by_name($insertStid, ':quantity', $quantity);
        oci_execute($insertStid);
        oci_free_statement($insertStid);
    }

    $updateQuery = "UPDATE HOADON SET TRIGIA = :totalPrice WHERE SOHD = :id";
    $updateStid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($updateStid, ':totalPrice', $totalPrice);
    oci_bind_by_name($updateStid, ':id', $id);
    oci_execute($updateStid);
    oci_free_statement($updateStid);
    oci_close($conn);
}

function createOrder($productsArray, $username, $manv) {
    $conn = getDbConnection();
    $newSOHD = rand(100, 999999);
    $ngayhd = date('Y-m-d');

    $insertOrderQuery = "INSERT INTO HOADON (SOHD, NGAYHD, USERNAME, MANV, TRIGIA) VALUES (:sohd, TO_DATE(:ngayhd, 'YYYY-MM-DD'), :username, :manv, 0)";
    $insertOrderStid = oci_parse($conn, $insertOrderQuery);
    oci_bind_by_name($insertOrderStid, ':sohd', $newSOHD);
    oci_bind_by_name($insertOrderStid, ':ngayhd', $ngayhd);
    oci_bind_by_name($insertOrderStid, ':username', $username);
    oci_bind_by_name($insertOrderStid, ':manv', $manv);
    oci_execute($insertOrderStid);
    oci_free_statement($insertOrderStid);

    foreach ($productsArray as $product) {
        $product = trim($product);
        $productName = explode('-', $product)[0];
        $quantity = explode('-', $product)[1];

        $query = "SELECT MASP FROM SANPHAM WHERE TENSP = :productName";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':productName', $productName);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $product_id = $row['MASP'];
        oci_free_statement($stid);

        // Call the stored procedure SP_THEM_CTHD
        $procedureQuery = "BEGIN SP_THEM_CTHD(:sohd, :product_id, :quantity); END;";
        $procedureStid = oci_parse($conn, $procedureQuery);
        oci_bind_by_name($procedureStid, ':sohd', $newSOHD);
        oci_bind_by_name($procedureStid, ':product_id', $product_id);
        oci_bind_by_name($procedureStid, ':quantity', $quantity);
        oci_execute($procedureStid);
        oci_free_statement($procedureStid);
    }

    oci_close($conn);
}

function getOrders() {
    $conn = getDbConnection();
    $query = "SELECT HOADON.SOHD, HOADON.NGAYHD, HOADON.TRIGIA, HOADON.MANV,
              LISTAGG(SANPHAM.TENSP || ' (' || CTHD.SL || ')', ', ') WITHIN GROUP (ORDER BY SANPHAM.TENSP) AS PRODUCTS,
              SUM(CTHD.SL * SANPHAM.GIATIEN) AS PRICE,
              HOADON.USERNAME
              FROM HOADON
              LEFT JOIN CTHD ON HOADON.SOHD = CTHD.SOHD
              LEFT JOIN SANPHAM ON CTHD.MASP = SANPHAM.MASP
              GROUP BY HOADON.SOHD, HOADON.NGAYHD, HOADON.TRIGIA, HOADON.MANV, HOADON.USERNAME
              ORDER BY HOADON.NGAYHD ASC";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $orders = [];
    while ($row = oci_fetch_assoc($stid)) {
        $orders[] = $row;
    }
    oci_free_statement($stid);
    oci_close($conn);
    return $orders;
}

function getProducts() {
    $conn = getDbConnection();
    $query = "SELECT TENSP FROM SANPHAM";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
    $products = [];
    while ($row = oci_fetch_assoc($stid)) {
        $products[] = $row['TENSP'];
    }
    oci_free_statement($stid);
    oci_close($conn);
    return $products;
}
function displayProducts() {
    $conn = getDbConnection();
    $product = oci_parse($conn, "SELECT * FROM SANPHAM ORDER BY DANHMUC DESC, TENSP ASC ");
    oci_execute($product);
    $i = 1;
    while ($row = oci_fetch_assoc($product)):
        echo '<tr>';
        echo '<td class="text-center">' . $i++ . '</td>';
        echo '<td class=""><p><b>' . htmlspecialchars($row['DANHMUC']) . '</b></p></td>';
        echo '<td class="">';
        echo '<p>Name: <b>' . htmlspecialchars($row['TENSP']) . '</b></p>';
        echo '<p><small>Price: <b>' . number_format($row['GIATIEN'], 0) . '</b></small></p>';
        echo '<p><small>Status: <b>' . ($row['STATUS'] == 1 ? "Available" : "Unavailable") . '</b></small></p>';
        echo '</td>';
        echo '<td class="text-center">';
        echo '<button class="btn btn-sm btn-primary edit_product" type="button" ';
        echo 'data-id="' . htmlspecialchars($row['MASP']) . '" ';
        echo 'data-name="' . htmlspecialchars($row['TENSP']) . '" ';
        echo 'data-price="' . htmlspecialchars($row['GIATIEN']) . '" ';
        echo 'data-status="' . htmlspecialchars($row['STATUS']) . '" ';
        echo 'data-category_id="' . htmlspecialchars($row['DANHMUC']) . '">Edit</button>';
        echo '<button class="btn btn-sm btn-danger delete_product" type="button" data-id="' . htmlspecialchars($row['MASP']) . '">Delete</button>';
        echo '</td>';
        echo '</tr>';
    endwhile;
}

function addOrUpdateProduct($id, $name, $price, $status, $category_id, $donvitinh) {
    $conn = getDbConnection();

    if (empty($id)) {
        // Call the Oracle stored procedure for inserting a new product
        $query = "BEGIN SP_THEM_SANPHAM(:name, :donvitinh, :price, :category_id); END;";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':donvitinh', $donvitinh);
        oci_bind_by_name($stid, ':price', $price);
        oci_bind_by_name($stid, ':category_id', $category_id);
    } else {
        // Update existing product
        $query = "UPDATE SANPHAM SET TENSP = :name, GIATIEN = :price, DANHMUC = :category_id, STATUS = :status, DONVITINH = :donvitinh WHERE MASP = :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':price', $price);
        oci_bind_by_name($stid, ':category_id', $category_id);
        oci_bind_by_name($stid, ':status', $status);
        oci_bind_by_name($stid, ':donvitinh', $donvitinh);
    }

    oci_execute($stid);
    oci_free_statement($stid);
}


function deleteProduct($id) {
    $conn = getDbConnection();
    $query = "DELETE FROM SANPHAM WHERE MASP = :id";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':id', $id);
    oci_execute($stid);
    oci_free_statement($stid);
}
?>
