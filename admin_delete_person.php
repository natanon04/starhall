<?php
// admin_delete_person.php

$mysqli = new mysqli("localhost", "root", "", "StarHall");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // ลบจาก playlist_items ก่อน
    $stmt = $mysqli->prepare("DELETE FROM playlist_items WHERE person_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // แล้วค่อยลบจาก persons
    $stmt = $mysqli->prepare("DELETE FROM persons WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_persons.php?msg=deleted");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

}

$mysqli->close();
?>