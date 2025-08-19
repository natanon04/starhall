<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
if (function_exists('opcache_reset')) {
opcache_reset();
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

        /* การ์ดแต่ละประเภท */
        .card-1 { background-image: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 69, 19, 0.6)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iNDAiIGN5PSI0MCIgcj0iMjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4yKSIvPjxjaXJjbGUgY3g9IjE2MCIgY3k9IjYwIiByPSIxNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjE1KSIvPjxjaXJjbGUgY3g9IjgwIiBjeT0iMTQwIiByPSIyNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIi8+PC9zdmc+'); }
        
        .card-2 { background-image: linear-gradient(135deg, rgba(236, 72, 153, 0.8), rgba(168, 85, 247, 0.6)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTUwIDUwTDE1MCA1MEwxMDAgMTUwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjE1KSIvPjxwYXRoIGQ9Ik0xNjAgNDBMMTgwIDgwTDE0MCA4MFoiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg=='); }
        
        .card-3 { background-image: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(34, 197, 94, 0.6)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3QgeD0iNDAiIHk9IjQwIiB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHJ4PSI1IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMikiLz48cmVjdCB4PSIxMzAiIHk9IjcwIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHJ4PSIzIiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMTUpIi8+PC9zdmc+'); }
        
        .card-4 { background-image: linear-gradient(135deg, rgba(245, 158, 11, 0.8), rgba(251, 191, 36, 0.6)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMTAwIiBjeT0iMTAwIiByPSI0MCIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMikiIHN0cm9rZS13aWR0aD0iMyIgZmlsbD0ibm9uZSIvPjwvc3ZnPg=='); }
        
        .card-5 { background-image: linear-gradient(135deg, rgba(239, 68, 68, 0.8), rgba(220, 38, 127, 0.6)), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTUwIDEwMEwxMDAgNTBMMTUwIDEwMEwxMDAgMTUwWiIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjE1KSIvPjwvc3ZnPg=='); }

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
            <div class="card card-1">
                <div class="card-content">
                    <div>
                        <div class="card-title">📊 Sheets</div>
                        <div class="card-subtitle">ใหม่</div>
                    </div>
                    <div class="card-description">จัดการข้อมูลและสร้างรายงานได้อย่างง่ายดาย</div>
                </div>
            </div>
            
            <div class="card card-2">
                <div class="card-content">
                    <div>
                        <div class="card-title">🎨 เครื่องมือแต่งรูป</div>
                        <div class="card-subtitle">ใหม่</div>
                    </div>
                    <div class="card-description">แต่งรูปสวยๆ ด้วยเครื่องมือมากมาย</div>
                </div>
            </div>
            
            <div class="card card-3">
                <div class="card-content">
                    <div>
                        <div class="card-title">📅 เว็บไซต์</div>
                        <div class="card-subtitle">อัปเดต</div>
                    </div>
                    <div class="card-description">สร้างเว็บไซต์สุดเจ๋งได้ในไม่กี่คลิก</div>
                </div>
            </div>
            
            <div class="card card-4">
                <div class="card-content">
                    <div>
                        <div class="card-title">🎵 วิดีโอ</div>
                        <div class="card-subtitle">ยอดนิยม</div>
                    </div>
                    <div class="card-description">แก้ไขและสร้างวิดีโอคุณภาพสูง</div>
                </div>
            </div>
            
            <div class="card card-5">
                <div class="card-content">
                    <div>
                        <div class="card-title">🚗 งานพิมพ์</div>
                        <div class="card-subtitle">แนะนำ</div>
                    </div>
                    <div class="card-description">พิมพ์เอกสารและใบปลิวคุณภาพเยี่ยม</div>
                </div>
            </div>

            <div class="card card-1">
                <div class="card-content">
                    <div>
                        <div class="card-title">💼 โบรชัวร์</div>
                        <div class="card-subtitle">ใหม่</div>
                    </div>
                    <div class="card-description">สร้างโบรชัวร์สวยงามสำหรับธุรกิจ</div>
                </div>
            </div>

            <div class="card card-2">
                <div class="card-content">
                    <div>
                        <div class="card-title">📱 Instagram โพสต์</div>
                        <div class="card-subtitle">ยอดนิยม</div>
                    </div>
                    <div class="card-description">สร้างโพสต์ Instagram ที่ดึงดูดใจ</div>
                </div>
            </div>
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
        createIndicators();
        updateSlider();
</script>
</body>
</html>