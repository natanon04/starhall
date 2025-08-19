<?php
$currentPage = 'playlists';
include './includes/admin_navbar.php';
include './includes/db.php';

$playlist_id = intval($_GET['id'] ?? 0);
if (!$playlist_id) {
    echo "<div class='alert alert-danger'>ไม่พบ Playlist ที่ต้องการแก้ไข</div>";
    exit;
}

// ดึงข้อมูล Playlist
$stmt = $conn->prepare("SELECT * FROM playlists WHERE id=?");
$stmt->bind_param("i", $playlist_id);
$stmt->execute();
$playlist = $stmt->get_result()->fetch_assoc();

if (!$playlist) {
    echo "<div class='alert alert-danger'>ไม่พบ Playlist</div>";
    exit;
}

// อัปเดต Playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $imagePath = $playlist['image'];

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/playlists/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("UPDATE playlists SET title=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $imagePath, $playlist_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>แก้ไข Playlist เรียบร้อยแล้ว!</div>";
        $playlist['title'] = $title;
        $playlist['description'] = $description;
        $playlist['image'] = $imagePath;
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไข Playlist - Admin</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4"><i class="fas fa-edit"></i> แก้ไข Playlist</h1>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">ชื่อ Playlist:</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($playlist['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">คำอธิบาย:</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($playlist['description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">รูป Playlist:</label><br>
                            <?php if(!empty($playlist['image'])): ?>
                                <img src="<?= htmlspecialchars($playlist['image']) ?>" class="img-fluid rounded mb-2" style="max-height:150px;" alt="Playlist Image"><br>
                            <?php endif; ?>
                            <input type="file" name="image" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกการแก้ไข</button>
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