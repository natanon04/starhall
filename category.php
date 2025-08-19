<?php
include "./includes/db.php";

$category_id = intval($_GET['id'] ?? 0);

// ดึงชื่อหมวดหมู่
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$cat = $stmt->get_result()->fetch_assoc();

if (!$cat) {
    echo "ไม่พบหมวดหมู่";
    exit;
}

$categoryName = $cat['name'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($categoryName) ?> - StarHall</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php $currentPage = 'category'; include './includes/navbar.php'; ?>
  
  <div class="container mt-4">
    <!-- กล่องสีขาวครอบทั้งหมด -->
    <div class="p-4 bg-white shadow rounded">
      <h1>หมวดหมู่: <?php echo htmlspecialchars($categoryName) ?></h1>

      <?php
      $stmt = $conn->prepare("SELECT * FROM persons WHERE category_id = ?");
      $stmt->bind_param("i", $category_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 0) {
          echo "<p>ยังไม่มีบุคคลในหมวดหมู่นี้</p>";
      } else {
          echo "<div class='row'>";
          while ($row = $result->fetch_assoc()) {
              echo "<div class='col-md-4'>
                      <div class='card mb-3 shadow'>
                        <img src='".htmlspecialchars($row['image'])."' class='card-img-top'>
                        <div class='card-body'>
                          <h5 class='card-title'>".htmlspecialchars($row['name'])."</h5>
                          <p class='card-text'>".htmlspecialchars($row['description'])."</p>
                          <a href='person.php?id={$row['id']}' class='btn btn-sm btn-primary'>อ่านต่อ</a>
                        </div>
                      </div>
                    </div>";
          }
          echo "</div>";
      }
      ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>