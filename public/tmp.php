<?php
function addEmployee($data) {
    $conn = getDbConnection();
    $query = "BEGIN SP_THEM_NHANVIEN(:hoten, :dienthoai, TO_DATE(:ngaysinh, 'YYYY-MM-DD'), TO_DATE(:ngayvl, 'YYYY-MM-DD'), :cccd, :diachi, :gioitinh); END;";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':hoten', $data['HOTEN']);
    oci_bind_by_name($stid, ':dienthoai', $data['DIENTHOAI']);
    oci_bind_by_name($stid, ':ngaysinh', $data['NGAYSINH']);
    oci_bind_by_name($stid, ':ngayvl', $data['NGAYVL']);
    oci_bind_by_name($stid, ':cccd', $data['CCCD']);
    oci_bind_by_name($stid, ':diachi', $data['DIACHI']);
    oci_bind_by_name($stid, ':gioitinh', $data['GIOITINH']);

    $result = oci_execute($stid);

    if ($result) {
        $_SESSION['message'] = 'Employee added successfully';
        $_SESSION['message_type'] = 'success';
    } else {
        $e = oci_error($stid);
        $_SESSION['message'] = 'Failed to add employee: ' . $e['message'];
        $_SESSION['message_type'] = 'error';
    }

    oci_free_statement($stid);
    oci_close($conn);
}
?>