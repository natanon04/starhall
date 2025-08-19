<?php
include "./includes/db.php";

// ดึงบุคคลทั้งหมด พร้อมหมวดหมู่
$sql = "SELECT p.*, c.name AS category_name 
        FROM persons p 
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id DESC";
$result = $conn->query($sql);

$persons = [];
if($result){
    $persons = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บุคคลทั้งหมด - StarHall</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .person-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
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
            margin-bottom: 5px;
        }
        .person-category {
            color: #8b4513;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .person-description {
            flex-grow: 1;
            color: #555;
            font-size: 0.95rem;
        }
        .detail-btn {
            margin-top: 10px;
            background: linear-gradient(135deg, #ff9500, #ff7b00);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 8px 15px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        .detail-btn:hover {
            background: linear-gradient(135deg, #ff7b00, #ff5500);
        }
    </style>
</head>
<body>
    <?php $currentPage = 'persons'; include './includes/navbar.php'; ?>

    <div class="container mt-4">
        <h1>บุคคลทั้งหมด</h1>
        <hr>
        <div class="row g-4">
            <?php if(empty($persons)): ?>
                <p style="color:gray;">ยังไม่มีข้อมูลบุคคล</p>
            <?php else: ?>
                <?php foreach($persons as $p): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="person-card">
                            <?php if(!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="person-image">
                            <?php else: ?>
                                <div class="person-image d-flex align-items-center justify-content-center" 
                                     style="background: #f0f0f0;">
                                    <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
                                </div>
                            <?php endif; ?>
                            <h3 class="person-name"><?= htmlspecialchars($p['name']) ?></h3>
                            <div class="person-category"><?= htmlspecialchars($p['category_name']) ?></div>
                            <p class="person-description"><?= htmlspecialchars(mb_substr($p['description'],0,80)) ?><?php if(mb_strlen($p['description'])>80) echo '...'; ?></p>
                            <a href="person.php?id=<?= $p['id'] ?>" class="detail-btn"><i class="fas fa-eye"></i> ดูรายละเอียด</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>