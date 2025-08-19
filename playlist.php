<?php
require './includes/db.php';

$playlist_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ดึงข้อมูลเพลย์ลิสต์
$sql = "SELECT * FROM playlists WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $playlist_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("ไม่พบเพลย์ลิสต์นี้");

$playlist = $result->fetch_assoc();

// ดึงบุคคลในเพลย์ลิสต์
$sql_items = "
    SELECT p.*
    FROM playlist_items pi
    JOIN persons p ON pi.person_id = p.id
    WHERE pi.playlist_id = ?
";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $playlist_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
$persons = $result_items->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($playlist['title']) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .person-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
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
        @media (max-width: 768px) {
            .person-card { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
<?php $currentPage = 'playlists'; include './includes/navbar.php'; ?>

<div class="container mt-4">
    <h1><?= htmlspecialchars($playlist['title']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars($playlist['description']) ?></p>
    <hr>

    <?php if(empty($persons)): ?>
        <p style="color:gray;">ยังไม่มีบุคคลในเพลย์ลิสต์นี้</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($persons as $person): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="person-card">
                        <?php if(!empty($person['image'])): ?>
                            <img src="<?= htmlspecialchars($person['image']) ?>" 
                                 alt="<?= htmlspecialchars($person['name']) ?>" 
                                 class="person-image">
                        <?php else: ?>
                            <div class="person-image d-flex align-items-center justify-content-center" 
                                 style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                                <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                        <?php endif; ?>
                        <h3 class="person-name"><?= htmlspecialchars($person['name']) ?></h3>
                        <p class="person-description">
                            <?= htmlspecialchars(mb_substr($person['description'],0,80)) ?>
                            <?php if(mb_strlen($person['description'])>80) echo '...'; ?>
                        </p>
                        <a href="person.php?id=<?= $person['id'] ?>" class="detail-btn">
                            <i class="fas fa-eye"></i> ดูรายละเอียด
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>