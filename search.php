<?php
include './includes/db.php';

$q = trim($_GET['q'] ?? '');
if ($q == '') { echo "กรุณากรอกคำค้นหา"; exit; }

$sql = "SELECT * FROM persons 
        WHERE name LIKE ? 
        OR keywords LIKE ? 
        OR content LIKE ? 
        OR SOUNDEX(name) = SOUNDEX(?)";

$stmt = $conn->prepare($sql);
$like = "%$q%";
$stmt->bind_param("ssss", $like, $like, $like, $q);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($q)?>-StarHall</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin: 30px auto;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .search-title {
            color: #d4621a;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .search-count {
            color: #8b4513;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .results-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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
        
        .search-suggestions {
            color: #999;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .search-header {
                margin: 20px;
                padding: 20px;
            }
            
            .search-title {
                font-size: 1.5rem;
            }
            
            .person-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php $currentPage = 'home'; include './includes/navbar.php'; ?>
    
    <div class="container-fluid">
        <!-- Header ผลการค้นหา -->
        <div class="search-header">
            <h1 class="search-title">
                <i class="fas fa-search me-2"></i>
                ผลการค้นหา: "<?= htmlspecialchars($q) ?>"
            </h1>
            <p class="search-count">
                พบ <?= $count ?> รายการ
            </p>
        </div>

        <div class="results-container">
            <?php if ($count > 0): ?>
                <div class="row g-4">
                    <?php 
                    // Reset pointer เพื่อใช้ข้อมูลใหม่
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()): 
                    ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="person-card">
                                <?php if(!empty($row['image'])): ?>
                                    <img src="<?= htmlspecialchars($row['image']) ?>" 
                                         alt="<?= htmlspecialchars($row['name']) ?>" 
                                         class="person-image">
                                <?php else: ?>
                                    <div class="person-image d-flex align-items-center justify-content-center" 
                                         style="background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                                        <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="person-name"><?= htmlspecialchars($row['name']) ?></h3>
                                <p class="person-description">
                                    <?= htmlspecialchars(mb_substr($row['description'], 0, 80)) ?>
                                    <?php if(mb_strlen($row['description']) > 80) echo '...'; ?>
                                </p>
                                <a href="person.php?id=<?= $row['id'] ?>" class="detail-btn">
                                    <i class="fas fa-eye"></i>
                                    ดูรายละเอียด
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search-minus"></i>
                    </div>
                    <div class="no-results-text">
                        ไม่พบผลการค้นหาสำหรับ "<?= htmlspecialchars($q) ?>"
                    </div>
                    <div class="search-suggestions">
                        ลองค้นหาด้วยคำอื่น หรือตรวจสอบการสะกดคำ
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>