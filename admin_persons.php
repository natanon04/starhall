<?php
include './includes/db.php';
$currentPage = 'persons';

$result = $conn->query("SELECT * FROM persons ORDER BY id DESC");
$persons = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการบุคคล</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $currentPage = 'persons'; include './includes/admin_navbar.php'; ?>

<div class="container mt-4">
    <h2>บุคคล</h2>
    <a href="admin_add_person.php" class="btn btn-success mb-3">+ เพิ่มบุคคล</a>

    <div class="row g-4">
        <?php foreach($persons as $p): ?>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <?php if(!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(mb_substr($p['description'],0,60)) ?>...</p>
                        <a href="admin_edit_person.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                        <a href="admin_delete_person.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจว่าต้องการลบ?')">ลบ</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>