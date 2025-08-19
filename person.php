<?php
include "./includes/db.php";

$person_id = intval($_GET['id'] ?? 0);

// ดึงข้อมูลบุคคล
$stmt = $conn->prepare("SELECT p.*, c.name AS category_name 
                        FROM persons p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.id = ?");
$stmt->bind_param("i", $person_id);
$stmt->execute();
$person = $stmt->get_result()->fetch_assoc();

if (!$person) {
    echo "ไม่พบบุคคลนี้";
    exit;
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($person['name']) ?> - StarHall</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php $currentPage = 'category'; include './includes/navbar.php'; ?>

  <div class="container my-5">
    <div class="p-4 bg-white shadow rounded">
      <div class="row">
        <div class="col-md-4 text-center mb-3">
          <img src="    <?= htmlspecialchars($person['image'])?>" 
               class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($person['name']) ?>">
        </div>
        <div class="col-md-8">
          <h1 class="mb-3"><?= htmlspecialchars($person['name']) ?></h1>
          <h5 class="text-muted mb-3">หมวดหมู่: <?= htmlspecialchars($person['category_name']) ?></h5>
          <p class="lead"><?= nl2br(htmlspecialchars($person['description'])) ?></p>
          <hr>
          <div>
            <?= nl2br(htmlspecialchars($person['content'])) ?>
          </div>
          <?php if(!empty($person['keywords'])): ?>
            <hr>
            <p><strong>คำค้น:</strong> <?= htmlspecialchars($person['keywords']) ?></p>
          <?php endif; ?>
          <a href="category.php?id=<?= $person['category_id'] ?>" class="btn btn-primary mt-3">กลับไปยังหมวดหมู่</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>