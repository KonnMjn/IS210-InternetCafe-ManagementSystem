<?php
session_start();
include __DIR__ . '/getdbconnection.php';

function showAccounts($searchUsername = '') {
    $conn = getDbConnection();
    $query = "SELECT USERNAME, PASS, HOTEN, NGAYSINH, CCCD, DIACHI, GIOITINH, SOTIENCONLAI, LOAITK FROM TAIKHOAN";

    if ($searchUsername !== '') {
        $query .= " WHERE USERNAME = :searchUsername";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':searchUsername', $searchUsername);
    } else {    
        $stid = oci_parse($conn, $query);
    }

    oci_execute($stid);

    while (($row = oci_fetch_assoc($stid)) != false) {
        echo "<tr>
            <td>{$row['USERNAME']}</td>
            <td>{$row['PASS']}</td>
            <td>{$row['HOTEN']}</td>
            <td>{$row['NGAYSINH']}</td>
            <td>{$row['CCCD']}</td>
            <td>{$row['DIACHI']}</td>
            <td>{$row['GIOITINH']}</td>
            <td>{$row['SOTIENCONLAI']}</td>
            <td>{$row['LOAITK']}</td>
            <td>
                <a href='QL_TAIKHOAN.php?action=edit&id={$row['USERNAME']}'>Edit</a> | 
                <form action='QL_TAIKHOAN.php' method='post' style='display: inline;'>
                    <input type='hidden' name='delete_username' value='{$row['USERNAME']}'>
                    <input type='hidden' name='action' value='delete'>
                    <button type='submit' onclick='return confirm(\"Are you sure you want to delete this account?\");'>Delete</button>
                </form>            
                </td>
        </tr>";
    }

    oci_free_statement($stid);
    oci_close($conn);
}
function addAccount($data) {
    $conn = getDbConnection();
    $query = "INSERT INTO TAIKHOAN (USERNAME, PASS, HOTEN, NGAYSINH, CCCD, DIACHI, GIOITINH, SOTIENCONLAI, LOAITK) 
              VALUES (:USERNAME, :PASS, :HOTEN, TO_DATE(:NGAYSINH, 'YYYY-MM-DD'), :CCCD, :DIACHI, :GIOITINH, :SOTIENCONLAI, :LOAITK)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':USERNAME', $data['USERNAME']);
    oci_bind_by_name($stid, ':PASS', $data['PASS']);
    oci_bind_by_name($stid, ':HOTEN', $data['HOTEN']);
    oci_bind_by_name($stid, ':NGAYSINH', $data['NGAYSINH']);
    oci_bind_by_name($stid, ':CCCD', $data['CCCD']);
    oci_bind_by_name($stid, ':DIACHI', $data['DIACHI']);
    oci_bind_by_name($stid, ':GIOITINH', $data['GIOITINH']);
    oci_bind_by_name($stid, ':SOTIENCONLAI', $data['SOTIENCONLAI']);
    oci_bind_by_name($stid, ':LOAITK', $data['LOAITK']);

    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['message'] = 'Account added successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to add account.';
        $_SESSION['message_type'] = 'error';
    }

    oci_free_statement($stid);
    oci_close($conn);
}

function editAccount($data) {
    $conn = getDbConnection();
    $query = "UPDATE TAIKHOAN SET 
              PASS = :PASS, 
              HOTEN = :HOTEN, 
              NGAYSINH = TO_DATE(:NGAYSINH, 'YYYY-MM-DD'), 
              CCCD = :CCCD, 
              DIACHI = :DIACHI, 
              GIOITINH = :GIOITINH, 
              SOTIENCONLAI = :SOTIENCONLAI, 
              LOAITK = :LOAITK 
              WHERE USERNAME = :USERNAME";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':USERNAME', $data['USERNAME']);
    oci_bind_by_name($stid, ':PASS', $data['PASS']);
    oci_bind_by_name($stid, ':HOTEN', $data['HOTEN']);
    oci_bind_by_name($stid, ':NGAYSINH', $data['NGAYSINH']);
    oci_bind_by_name($stid, ':CCCD', $data['CCCD']);
    oci_bind_by_name($stid, ':DIACHI', $data['DIACHI']);
    oci_bind_by_name($stid, ':GIOITINH', $data['GIOITINH']);
    oci_bind_by_name($stid, ':SOTIENCONLAI', $data['SOTIENCONLAI']);
    oci_bind_by_name($stid, ':LOAITK', $data['LOAITK']);

    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['message'] = 'Account updated successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to update account.';
        $_SESSION['message_type'] = 'error';
    }

    oci_free_statement($stid);
    oci_close($conn);
}

function deleteAccount($username) {
    $conn = getDbConnection();
    $query = "DELETE FROM TAIKHOAN WHERE USERNAME = :username";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':username', $username);

    $result = oci_execute($stid);
    if ($result) {
        $_SESSION['message'] = 'Account deleted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete account.';
        $_SESSION['message_type'] = 'error';
    }

    oci_free_statement($stid);
    oci_close($conn);
}

function addMoney($username, $amount) {
    $conn = getDbConnection();

    // Step 1: Check if the username exists in SUDUNG
    $queryCheck = "SELECT COUNT(*) AS user_exists FROM SUDUNG WHERE USERNAME = :username";
    $stidCheck = oci_parse($conn, $queryCheck);
    oci_bind_by_name($stidCheck, ':username', $username);
    oci_execute($stidCheck);
    $row = oci_fetch_array($stidCheck, OCI_ASSOC);

    if ($row['USER_EXISTS'] > 0) {
        // Step 2: Username exists in SUDUNG, use stored procedure to add money
        // Assuming your stored procedure is named 'ADD_MONEY_PROCEDURE'
        // and takes two parameters: username and amount.
        $sql = "BEGIN SP_CAPNHAT_SOTIENCONLAI(:username, :amount); END;";
        $stidProc = oci_parse($conn, $sql);
        oci_bind_by_name($stidProc, ':username', $username);
        oci_bind_by_name($stidProc, ':amount', $amount);
        $result = oci_execute($stidProc);
        oci_free_statement($stidProc);
    } else {
        // Step 3: Username does not exist in SUDUNG, use the original method to add money
        $query = "UPDATE TAIKHOAN SET SOTIENCONLAI = SOTIENCONLAI + :amount WHERE USERNAME = :username";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':amount', $amount);
        oci_bind_by_name($stid, ':username', $username);
        $result = oci_execute($stid);
        oci_free_statement($stid);
    }

    // Handle result
    if ($result) {
        $_SESSION['message'] = 'Money added successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to add money.';
        $_SESSION['message_type'] = 'error';
    }

    // Clean up
    oci_free_statement($stidCheck);
    oci_close($conn);
}