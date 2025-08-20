<?php
require './includes/db.php';

// ดึงเพลย์ลิสต์ทั้งหมด
$sql = "SELECT * FROM playlists ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) die("SQL Error: " . $conn->error);

$playlists = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>🎵 เพลย์ลิสต์ทั้งหมด</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .playlist-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .playlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .playlist-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .playlist-title {
            color: #d4621a;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .playlist-description {
            color: #666;
            flex-grow: 1;
        }
        .btn-detail {
            background: linear-gradient(135deg, #ff9500, #ff7b00);
            color: #fff;
            border-radius: 25px;
            padding: 6px 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }
        .btn-detail:hover {
            background: linear-gradient(135deg, #ff7b00, #ff5500);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 149, 0, 0.3);
            color: #fff;
        }
    </style>
</head>
<body>
<?php $currentPage = 'playlists'; include './includes/navbar.php'; ?>

<div class="container my-5">
    <h1 class="mb-4">🎵 เพลย์ลิสต์ทั้งหมด</h1>
    <?php if (empty($playlists)): ?>
        <p class="text-muted">ยังไม่มีเพลย์ลิสต์</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($playlists as $pl): ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="playlist-card">
                        <?php if(!empty($pl['image'])): ?>
                            <img src="<?= htmlspecialchars($pl['image']) ?>" alt="<?= htmlspecialchars($pl['title']) ?>" class="playlist-image">
                        <?php else: ?>
                            <div class="playlist-image d-flex align-items-center justify-content-center" style="background: #f0f0f0;">
                                <i class="fas fa-music" style="font-size:3rem; color:#ccc;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h5 class="playlist-title"><?= htmlspecialchars($pl['title']) ?></h5>
                            <p class="playlist-description"><?= htmlspecialchars(mb_substr($pl['description'] ?? '', 0, 80)) ?><?= (mb_strlen($pl['description'] ?? '') > 80) ? '...' : '' ?></p>
                            <a href="playlist.php?id=<?= $pl['id'] ?>" class="btn-detail mt-auto">
                                <i class="fas fa-eye"></i> ดูรายละเอียด
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>