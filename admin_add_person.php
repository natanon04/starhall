<?php
session_start();
include './includes/db.php';

// --- ดึง category สำหรับ dropdown ---
$categories = [];
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// --- รับ Session message ---
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

// ล้าง message หลังแสดง
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เพิ่มบุคคลสำคัญ</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; font-family: Arial, sans-serif; }
h2 { color: #d4621a; text-align: center; margin: 30px 0; }

.alert {
    max-width: 800px;
    margin: 20px auto;
    padding: 15px;
    border-radius: 10px;
    font-weight: 500;
}

.preview-section {
    max-width: 1200px;
    margin: 0 auto 50px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.person-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    flex: 0 0 250px; /* ความกว้างเหมือนการ์ดค้นหา */
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
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
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
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
    background: linear-gradient(135deg,#ff9500,#ff7b00);
    color: #fff;
    border: none;
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
    transition: all 0.3s ease;
}
.detail-btn:hover {
    background: linear-gradient(135deg,#ff7b00,#ff5500);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,149,0,0.3);
}

/* Form สวยงาม */
form {
    max-width: 800px;
    margin: 0 auto 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.form-label { font-weight: 500; margin-top: 15px; }
button { background: linear-gradient(135deg,#ff9500,#ff7b00); border:none; color:#fff; padding:10px 20px; border-radius:25px; transition: all 0.3s; }
button:hover { background: linear-gradient(135deg,#ff7b00,#ff5500); }
</style>
</head>
<body>
<?php $currentPage = 'persons'; include './includes/admin_navbar.php'; ?>

<div class="container mt-5">

    <!-- แสดง Session message -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Live Preview การ์ด -->
    <h2>ตัวอย่างการ์ด</h2>
    <div class="preview-section">
        <div class="person-card" id="previewCard">
            <div class="person-image" id="previewImage">
                <i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>
            </div>
            <div class="person-name" id="previewName">ชื่อบุคคล</div>
            <div class="person-description" id="previewDescription">คำอธิบายสั้น</div>
            <a class="detail-btn" style="pointer-events:none; justify-content:center; display:flex;">
                <i class="fas fa-eye"></i> ดูรายละเอียด
            </a>
        </div>
    </div>

    <!-- Form เพิ่มบุคคล -->
    <form action="save_person.php" method="post" enctype="multipart/form-data">
        <label class="form-label">ชื่อบุคคลสำคัญ <span class="text-danger">*</span>:</label>
        <input type="text" name="name" id="name" class="form-control" required oninput="updatePreview()">

        <label class="form-label">คำอธิบายสั้น:</label>
        <textarea name="description" id="description" class="form-control" rows="3" oninput="updatePreview()"></textarea>

        <label class="form-label">เนื้อหาเต็ม:</label>
        <textarea name="content" class="form-control" rows="5"></textarea>

        <label class="form-label">คำค้น (Keywords):</label>
        <input type="text" name="keywords" class="form-control">

        <label class="form-label">อัปโหลดรูปภาพ:</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="updatePreview()">

        <label class="form-label">หมวดหมู่:</label>
        <select name="category_id" class="form-select">
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <div class="text-center mt-4">
            <button type="submit"><i class="fas fa-save"></i> บันทึกบุคคลสำคัญ</button>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    let name = document.getElementById('name').value;
    document.getElementById('previewName').innerText = name || 'ชื่อบุคคล';

    let desc = document.getElementById('description').value;
    document.getElementById('previewDescription').innerText = desc || 'คำอธิบายสั้น';

    const file = document.getElementById('image').files[0];
    const previewImage = document.getElementById('previewImage');
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            previewImage.innerHTML = `<img src="${e.target.result}" class="person-image" style="width:100%;height:200px;object-fit:cover;border-radius:15px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        previewImage.innerHTML = '<i class="fas fa-user" style="font-size: 3rem; color: #ccc;"></i>';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>