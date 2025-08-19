<?php
include './includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // อัปโหลดรูป
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/playlists/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO playlists (title, description, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $imagePath);
    if($stmt->execute()){
        $msg = "<div class='alert alert-success'>เพิ่ม Playlist สำเร็จ!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่ม Playlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php $currentPage = '#'; include './includes/admin_navbar.php'; ?>
<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center">เพิ่ม Playlist ใหม่</h2>

        <?php if(!empty($msg)) echo $msg; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">ชื่อ Playlist</label>
                <input type="text" name="title" class="form-control" placeholder="กรอกชื่อ Playlist" required>
            </div>

            <div class="mb-3">
                <label class="form-label">คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="3" placeholder="กรอกคำอธิบาย"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">รูป Playlist</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">เพิ่ม Playlist</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>