<?php
include './includes/db.php';

if (!isset($_GET['id'])) {
    die("ไม่พบข้อมูลบุคคล");
}

$id = intval($_GET['id']);

// ถ้ากดปุ่มบันทึก → ทำการ UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $content     = $_POST['content'];
    $keywords    = $_POST['keywords'];

    // เช็คว่ามีการอัปโหลดรูปใหม่ไหม
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName   = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    if ($imagePath) {
        $stmt = $conn->prepare("UPDATE persons SET name=?, description=?, content=?, keywords=?, image=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $description, $content, $keywords, $imagePath, $id);
    } else {
        $stmt = $conn->prepare("UPDATE persons SET name=?, description=?, content=?, keywords=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $description, $content, $keywords, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin_persons.php?msg=updated");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// โหลดข้อมูลบุคคลมาแสดงในฟอร์ม
$stmt = $conn->prepare("SELECT * FROM persons WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$person = $result->fetch_assoc();

if (!$person) {
    die("ไม่พบบุคคลนี้");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขบุคคล</title>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .preview-card img {
        max-height: 200px;
        object-fit: cover;
    }
    .preview-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>
</head>
<body>
<?php $currentPage = 'persons'; include './includes/admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">✏️ แก้ไขบุคคล</h2>

    <div class="row g-4">
        <!-- ฟอร์ม -->
        <div class="col-md-7">
            <div class="card shadow-sm p-4">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อบุคคล</label>
                        <input type="text" name="name" class="form-control" required 
                               value="<?= htmlspecialchars($person['name']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">คำอธิบายสั้น</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($person['description']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เนื้อหาประวัติ</label>
                        <textarea name="content" class="form-control" rows="6"><?= htmlspecialchars($person['content']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">คำค้นหา (Keywords)</label>
                        <input type="text" name="keywords" class="form-control" 
                               value="<?= htmlspecialchars($person['keywords']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">รูปภาพ (ถ้าต้องการเปลี่ยน)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">💾 บันทึกการแก้ไข</button>
                        <a href="admin_persons.php" class="btn btn-secondary px-4">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview -->
        <div class="col-md-5">
            <div class="card preview-card">
                <?php if (!empty($person['image'])): ?>
                    <img src="<?= htmlspecialchars($person['image']) ?>" class="card-img-top" alt="image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($person['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($person['description']) ?></p>
                    <small class="text-secondary">🔑 <?= htmlspecialchars($person['keywords']) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>