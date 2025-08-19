<?php
// admin_delete_person.php

$mysqli = new mysqli("localhost", "root", "", "StarHall");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $mysqli->prepare("DELETE FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // ลบสำเร็จ ส่งกลับหน้าเดิม
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=deleted");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ไม่มี id ที่ส่งมา";
}

$mysqli->close();
?>