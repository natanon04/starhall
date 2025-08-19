<?php
require './includes/db.php';

// ดึงเพลย์ลิสต์ทั้งหมด
$sql = "SELECT * FROM playlists ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) die("SQL Error: " . $conn->error);

$playlists = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Playlists</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $currentPage = 'playlists'; include './includes/navbar.php'; ?>

<div class="container mt-4">
    <h1>🎵 เพลย์ลิสต์ทั้งหมด</h1>
    <hr>

    <?php if (empty($playlists)): ?>
        <p style="color:gray;">ยังไม่มีเพลย์ลิสต์</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($playlists as $pl): ?>
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="playlist.php?id=<?php echo $pl['id']; ?>">
                                    <?php echo htmlspecialchars($pl['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($pl['description']); ?>
                            </p>
                            <a href="playlist.php?id=<?php echo $pl['id']; ?>" class="btn btn-sm btn-primary">
                                ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>