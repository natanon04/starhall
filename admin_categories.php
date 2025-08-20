<?php
// admin_categories.php
$mysqli = new mysqli("localhost", "root", "", "StarHall");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// ดึงข้อมูลหมวดหมู่
$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>📂 หมวดหมู่</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-category {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  transition: all 0.3s;
}
.card-category:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
.card-icon {
  width:100%; height:150px; display:flex;
  align-items:center; justify-content:center;
  background: linear-gradient(135deg,#f0f0f0,#e0e0e0); border-radius:15px; margin-bottom:15px;
  font-size:3rem; color:#ccc;
}
.card-title {
  font-size:1.2rem; font-weight:bold; color:#d4621a; margin-bottom:10px;
}
.btn-edit, .btn-delete {
  margin-top:auto; margin-right:5px;
}
</style>
</head>
<body>
<?php $currentPage='categories'; include './includes/admin_navbar.php'; ?>

<div class="container my-5">
<h1 class="mb-4">📂 หมวดหมู่</h1>
<a href="admin_add_category.php" class="btn btn-success mb-4"><i class="fas fa-plus"></i> เพิ่มหมวดหมู่</a>

<div class="row g-4">
<?php if($result && $result->num_rows>0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card-category">
            <div class="card-icon"><i class="fas fa-folder"></i></div>
            <div class="card-title"><?= htmlspecialchars($row['name']) ?></div>
            <div>
                <a href="admin_edit_category.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm btn-edit"><i class="fas fa-edit"></i> แก้ไข</a>
                <a href="admin_delete_category.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-delete" onclick="return confirm('คุณแน่ใจว่าต้องการลบหมวดหมู่นี้?')"><i class="fas fa-trash"></i> ลบ</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="no-results text-center mt-5">
        <i class="fas fa-folder-open fa-3x mb-3" style="color:#ff9500;"></i>
        <div>ยังไม่มีหมวดหมู่</div>
    </div>
<?php endif; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>