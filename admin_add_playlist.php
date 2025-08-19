<?php
$currentPage = 'playlists';
include './includes/admin_navbar.php';
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

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>เพิ่ม Playlist เรียบร้อยแล้ว!</div>";
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่ม Playlist - Admin</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4"><i class="fas fa-plus-circle"></i> เพิ่ม Playlist ใหม่</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">ชื่อ Playlist:</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">คำอธิบาย:</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รูป Playlist:</label>
                            <input type="file" name="image" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> เพิ่ม Playlist</button>
                            <a href="admin_playlists.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> กลับไปยัง Playlist</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>