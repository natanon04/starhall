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
<style>
    .admin-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        margin: 30px auto;
        max-width: 900px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .admin-title {
        font-size: 2rem;
        font-weight: bold;
        color: #d4621a;
        margin-bottom: 15px;
    }

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
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .person-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 15px;
    }

    .person-name {
        font-size: 1.3rem;
        font-weight: bold;
        color: #d4621a;
        margin-bottom: 10px;
    }

    .person-description {
        color: #666;
        flex-grow: 1;
        font-size: 0.95rem;
        margin-bottom: 15px;
    }

    .btn-action {
        border-radius: 25px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        background: linear-gradient(135deg, #007bff, #0056d2);
        color: white;
        border: none;
    }
    .btn-edit:hover {
        background: linear-gradient(135deg, #0056d2, #0041a8);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 91, 255, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ff4d4d, #cc0000);
        color: white;
        border: none;
    }
    .btn-delete:hover {
        background: linear-gradient(135deg, #cc0000, #990000);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 77, 77, 0.3);
    }
</style>
</head>
<body>
<?php $currentPage = 'persons'; include './includes/admin_navbar.php'; ?>

<div class="container mt-4">
    <div class="admin-header">
        <h2 class="admin-title">
            <i class="fas fa-users me-2"></i> จัดการบุคคล
        </h2>
        <a href="admin_add_person.php" class="btn btn-success rounded-pill px-4">
            <i class="fas fa-plus"></i> เพิ่มบุคคล
        </a>
    </div>

    <div class="row g-4">
        <?php foreach($persons as $p): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="person-card">
                    <?php if(!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" 
                             alt="<?= htmlspecialchars($p['name']) ?>" 
                             class="person-image">
                    <?php else: ?>
                        <div class="person-image d-flex align-items-center justify-content-center" 
                             style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                            <i class="fas fa-user" style="font-size: 3rem; color: #bbb;"></i>
                        </div>
                    <?php endif; ?>

                    <h5 class="person-name"><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="person-description">
                        <?= htmlspecialchars(mb_substr($p['description'], 0, 80)) ?>
                        <?php if(mb_strlen($p['description']) > 80) echo '...'; ?>
                    </p>

                    <div class="d-flex justify-content-between mt-auto">
                        <a href="admin_edit_person.php?id=<?= $p['id'] ?>" class="btn btn-edit btn-action">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>
                        <a href="admin_delete_person.php?id=<?= $p['id'] ?>" 
                           class="btn btn-delete btn-action"
                           onclick="return confirm('คุณแน่ใจว่าต้องการลบ?')">
                            <i class="fas fa-trash-alt"></i> ลบ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>