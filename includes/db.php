<?php
$host = "localhost";
$user = "root";  // ชื่อผู้ใช้ MySQL
$pass = "";      // รหัสผ่าน MySQL
$dbname = "StarHall"; // ชื่อฐานข้อมูล

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8"); // ให้รองรับภาษาไทย
?>