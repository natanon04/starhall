<?php
include './includes/db.php';

// เพิ่มหมวดหมู่
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name']);
    if($name===''){
        $error = "กรุณากรอกชื่อหมวดหมู่";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s",$name);
        if($stmt->execute()){
            header("Location: admin_categories.php?msg=added");
            exit;
        } else $error = "เกิดข้อผิดพลาด: ".$stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เพิ่มหมวดหมู่</title>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $currentPage='categories'; include './includes/admin_navbar.php'; ?>

<div class="container mt-5">
<h2>เพิ่มหมวดหมู่</h2>

<?php if(!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
<div class="mb-3">
    <label class="form-label">ชื่อหมวดหมู่</label>
    <input type="text" name="name" class="form-control" required>
</div>

<button type="submit" class="btn btn-success">บันทึก</button>
<a href="admin_categories.php" class="btn btn-secondary">ยกเลิก</a>
</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>