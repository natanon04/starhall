<?php
include './includes/db.php';
$currentPage = 'playlists';

// ดึง Playlist ทั้งหมด
$result = $conn->query("SELECT * FROM playlists ORDER BY id DESC");
$playlists = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการ Playlists</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.person-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.person-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
.person-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 15px;
}
.person-name {
    color: #d4621a;
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 10px;
}
.person-description {
    color: #666;
    line-height: 1.5;
    margin-bottom: 15px;
    flex-grow: 1;
}
.detail-btn {
    background: linear-gradient(135deg, #ff9500, #ff7b00);
    color: white;
    border: none;
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
}
.detail-btn:hover {
    background: linear-gradient(135deg, #ff7b00, #ff5500);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 149, 0, 0.3);
}
.no-results {
    text-align: center;
    padding: 60px 20px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    margin: 30px auto;
    max-width: 600px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.no-results-icon {
    font-size: 4rem;
    color: #ff9500;
    margin-bottom: 20px;
}
.no-results-text {
    color: #666;
    font-size: 1.2rem;
    margin-bottom: 10px;
}
</style>
</head>
<body>
<?php $currentPage = 'playlists'; include './includes/admin_navbar.php'; ?>
<div class="container my-5">
    <h1 class="mb-4">🎵 Playlists</h1>
    <a href="admin_add_playlist.php" class="btn btn-success mb-4"><i class="fas fa-plus"></i> เพิ่ม Playlist</a>

    <div class="row g-4">
        <?php
        $result = $conn->query("SELECT * FROM playlists ORDER BY id DESC");
        if($result->num_rows > 0):
            while($row = $result->fetch_assoc()):
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="person-card">
                <?php if(!empty($row['image'])): ?>
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="person-image">
                <?php else: ?>
                    <div class="person-image d-flex align-items-center justify-content-center" 
                         style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                        <i class="fas fa-music" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                <?php endif; ?>

                <h3 class="person-name"><?= htmlspecialchars($row['title']) ?></h3>
                <p class="person-description">
                    <?= htmlspecialchars(mb_substr($row['description'],0,80)) ?>
                    <?php if(mb_strlen($row['description'])>80) echo '...'; ?>
                </p>
                
                <a href="admin_edit_playlist.php?id=<?= $row['id'] ?>" class="detail-btn">
                    <i class="fas fa-edit"></i> แก้ไข
                </a>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div class="no-results">
                <div class="no-results-icon"><i class="fas fa-folder-open"></i></div>
                <div class="no-results-text">ยังไม่มี Playlist</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>