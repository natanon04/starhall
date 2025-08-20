<?php
include './includes/db.php';

if (!isset($_GET['id'])) {
    die("ไม่พบหมวดหมู่");
}

$id = intval($_GET['id']);

// --- อัปเดตชื่อหมวดหมู่ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name === '') {
        $error = "กรุณากรอกชื่อหมวดหมู่";
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            header("Location: admin_categories.php?msg=updated");
            exit;
        } else {
            $error = "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    }
}

// --- โหลดข้อมูลหมวดหมู่ ---
$stmt = $conn->prepare("SELECT * FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
if (!$category) {
    die("ไม่พบหมวดหมู่นี้");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>แก้ไขหมวดหมู่</title>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $currentPage = 'categories'; include './includes/admin_navbar.php'; ?>

<div class="container mt-5">
    <h2>แก้ไขหมวดหมู่</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">ชื่อหมวดหมู่</label>
            <input type="text" name="name" class="form-control" required 
                   value="<?= htmlspecialchars($category['name']) ?>">
        </div>

        <button type="submit" class="btn btn-primary px-4">บันทึกการแก้ไข</button>
        <a href="admin_categories.php" class="btn btn-secondary px-4">ยกเลิก</a>
        <a href="admin_delete_category.php?id=<?= $category['id'] ?>" 
           class="btn btn-danger px-4" onclick="return confirm('คุณแน่ใจว่าต้องการลบหมวดหมู่นี้?')">
           ลบหมวดหมู่
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>