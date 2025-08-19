<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
if (function_exists('opcache_reset')) {
opcache_reset();
}

// เชื่อมต่อฐานข้อมูล
include './includes/db.php';

// ดึงเพลย์ลิสต์ 10 บุคคลสำคัญ (ตัวอย่าง playlist_id = 1)
$sql = "
    SELECT p.*
    FROM persons p
    JOIN playlist_items pi ON p.id = pi.person_id
    WHERE pi.playlist_id = 1
    LIMIT 10
";
$result = $conn->query($sql);

// เก็บข้อมูลในอาร์เรย์
$persons = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $persons[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StarHall</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            padding: 0;
            min-height: auto;
        }

        .slider-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .slider-wrapper {
            display: flex;
            transition: transform 0.3s ease-in-out;
            height: 300px;
        }

        .card {
            min-width: 280px;
            height: 100%;
            margin-right: 20px;
            border-radius: 15px;
            position: relative;
            background-size: cover;
            background-position: center;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .card-subtitle {
            font-size: 14px;
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            backdrop-filter: blur(10px);
            width: fit-content;
        }

        .card-description {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #333;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            opacity: 1;
            visibility: visible;
        }

        .nav-button:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .nav-button.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
            transform: scale(1.2);
        }

        /* สไตล์การ์ดสำหรับบุคคลสำคัญ */
        .person-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 69, 19, 0.6));
        }

        .person-card:nth-child(2n) {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.8), rgba(168, 85, 247, 0.6));
        }

        .person-card:nth-child(3n) {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(34, 197, 94, 0.6));
        }

        .person-card:nth-child(4n) {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.8), rgba(251, 191, 36, 0.6));
        }

        .person-card:nth-child(5n) {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.8), rgba(220, 38, 127, 0.6));
        }

        /* เพิ่มพื้นหลังภาพ */
        .person-card.has-image {
            background-size: cover;
            background-position: center;
        }

        .card-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 3;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .card {
                min-width: 250px;
            }
            
            .nav-button {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .prev {
                left: 10px;
            }
            
            .next {
                right: 10px;
            }
        }

        .head {
            padding: 30px;
        }
        .texthead {
            text-align: center;
            font-size: 60px;
        }
</style>
</head>
<body>
<?php $currentPage = 'home'; include './includes/navbar.php'; ?>

<div class="head">
    <h1 class="texthead">ประวัติ<span style="color: red;">บุคคลสำคัญ</span>ของโลก</h1>
</div>

<div>
    <p style="text-align: center;">
        <span>StarHall ช่วยรวบรวมบุคคลสำคัญในประวัติศาสตร์โลกให้คุณแล้ว อยากรู้จักใครมาเสิร์ชที่เราสิ</span>
    </p>
</div>

<div class="d-flex justify-content-center my-3">
  <form action="search.php" method="get" class="w-100" style="max-width: 640px;">
    <div class="input-group shadow" style="border-radius: 30px; overflow: hidden;">
      <input type="text" name="q" class="form-control border-0 py-2 px-3"
             placeholder="ค้นหาบุคคลสำคัญ..." required>
      <button class="btn border-0 px-3" type="submit" style="background-color: white;">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </form>
</div>

<div class="container-fluid py-4">
    <div class="slider-container">
        <div class="slider-wrapper" id="slider">
            <?php if (count($persons) > 0): ?>
                <?php foreach($persons as $index => $person): ?>
                    <div class="card person-card <?= !empty($person['image']) ? 'has-image' : '' ?>" 
                         <?= !empty($person['image']) ? 'style="background-image: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%), url(\'' . htmlspecialchars($person['image']) . '\')"' : '' ?>>
                        <a href="person.php?id=<?= $person['id'] ?>" class="card-link"></a>
                        <div class="card-content">
                            <div>
                                <div class="card-title"><?= htmlspecialchars($person['name']) ?></div>
                                <div class="card-subtitle">บุคคลสำคัญ</div>
                            </div>
                            <div class="card-description">
                                <?= mb_substr(strip_tags($person['description']), 0, 80, 'UTF-8') ?>...
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- แสดงการ์ดตัวอย่างถ้าไม่มีข้อมูลจากฐานข้อมูล -->
                <div class="card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 69, 19, 0.6));">
                    <div class="card-content">
                        <div>
                            <div class="card-title">📊 ไม่มีข้อมูล</div>
                            <div class="card-subtitle">แจ้งเตือน</div>
                        </div>
                        <div class="card-description">ยังไม่มีข้อมูลบุคคลสำคัญในระบบ</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <button class="nav-button prev" id="prevBtn">‹</button>
        <button class="nav-button next" id="nextBtn">›</button>
        
        <div class="indicators" id="indicators"></div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
        const slider = document.getElementById('slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const indicatorsContainer = document.getElementById('indicators');
        const cards = document.querySelectorAll('.card');
        
        let currentIndex = 0;
        const cardWidth = 300; // 280px + 20px margin
        const visibleCards = Math.floor(window.innerWidth / cardWidth) || 1;
        const maxIndex = Math.max(0, cards.length - visibleCards);

        // สร้าง indicators
        function createIndicators() {
            indicatorsContainer.innerHTML = '';
            const totalIndicators = maxIndex + 1;
            
            for (let i = 0; i < totalIndicators; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add('indicator');
                if (i === 0) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            }
        }

        function updateSlider() {
            const translateX = -currentIndex * cardWidth;
            slider.style.transform = `translateX(${translateX}px)`;
            
            // อัปเดต indicators
            const indicators = document.querySelectorAll('.indicator');
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });
            
            // แสดง/ซ่อน ปุ่มลูกศร
            prevBtn.classList.toggle('hidden', currentIndex === 0);
            nextBtn.classList.toggle('hidden', currentIndex === maxIndex);
        }

        function goToSlide(index) {
            currentIndex = Math.max(0, Math.min(index, maxIndex));
            updateSlider();
        }

        function nextSlide() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        }

        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        }

        // Event listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'ArrowRight') nextSlide();
        });

        // Touch/Swipe support
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });

        slider.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });

        slider.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            
            const diff = startX - currentX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) nextSlide();
                else prevSlide();
            }
        });

        // Mouse drag support
        let isMouseDown = false;

        slider.addEventListener('mousedown', (e) => {
            startX = e.clientX;
            isMouseDown = true;
            slider.style.cursor = 'grabbing';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;
            currentX = e.clientX;
        });

        slider.addEventListener('mouseup', () => {
            if (!isMouseDown) return;
            isMouseDown = false;
            slider.style.cursor = 'default';
            
            const diff = startX - currentX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) nextSlide();
                else prevSlide();
            }
        });

        // Window resize handler
        window.addEventListener('resize', () => {
            const newVisibleCards = Math.floor(window.innerWidth / cardWidth) || 1;
            if (newVisibleCards !== visibleCards) {
                location.reload(); // Reload to recalculate
            }
        });

        // Initialize
        if (cards.length > 0) {
            createIndicators();
            updateSlider();
        }
</script>
</body>
</html>