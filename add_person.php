<?php
include './includes/db.php';

// ดึง category สำหรับ dropdown
$categories = [];
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
if($result){
    while($row = $result->fetch_assoc()){
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่มบุคคลสำคัญ</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
form { max-width: 600px; margin: auto; }
label { display: block; margin-top: 10px; }
input[type=text], textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
button { margin-top: 15px; padding: 10px 20px; }
</style>
</head>
<body>
<?php $currentPage = '#'; include './includes/admin_navbar.php'; ?>
<h2>เพิ่มบุคคลสำคัญ</h2>
<form action="save_person.php" method="post" enctype="multipart/form-data">
    <label>ชื่อบุคคลสำคัญ <span style="color:red">*</span>:</label>
    <input type="text" name="name" required>

    <label>คำอธิบายสั้น:</label>
    <textarea name="description" rows="3"></textarea>

    <label>เนื้อหาเต็ม:</label>
    <textarea name="content" rows="5"></textarea>

    <label>คำค้น (Keywords):</label>
    <input type="text" name="keywords" placeholder="คั่นด้วย , เช่น นักวิทยาศาสตร์, นักประวัติศาสตร์">

    <label>อัปโหลดรูปภาพ:</label>
    <input type="file" name="image" accept="image/*">

    <label>หมวดหมู่:</label>
    <select name="category_id">
        <option value="">-- เลือกหมวดหมู่ --</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">บันทึกบุคคลสำคัญ</button>
</form>

</body>
</html>