<?php
session_start();
include './includes/db.php';

// --- ตรวจสอบสิทธิ์ admin ---


// ตรวจสอบ POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "ไม่สามารถเข้าหน้านี้ได้โดยตรง";
    header("Location: admin_add_person.php");
    exit;
}

// รับค่าจากฟอร์ม
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$content     = trim($_POST['content'] ?? '');
$keywords    = trim($_POST['keywords'] ?? '');
$category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : null;

// ตรวจสอบฟิลด์บังคับ
if (empty($name)) {
    $_SESSION['error'] = "กรุณากรอกชื่อบุคคลสำคัญ";
    header("Location: admin_add_person.php");
    exit;
}

// --- อัปโหลดไฟล์ ---
$imagePath = null;
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($tmpName, $targetFile)) {
        $imagePath = 'uploads/' . $filename; // path สำหรับ DB
    } else {
        $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
        header("Location: admin_add_person.php");
        exit;
    }
}

// --- Prepared Statement แบบปลอดภัย ---
$stmt = $conn->prepare("INSERT INTO persons (name, description, content, keywords, image, category_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssssi", $name, $description, $content, $keywords, $imagePath, $category_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "บันทึกบุคคลสำคัญเรียบร้อยแล้ว";
    header("Location: admin_persons.php");
    exit;
} else {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $stmt->error;
    header("Location: admin_add_person.php");
    exit;
}