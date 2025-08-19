<?php
include './includes/db.php';



// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $playlist_id = $_POST['playlist_id'];
    $person_ids  = $_POST['person_id'] ?? []; // จะได้ array ของบุคคลที่เลือก

    if (empty($person_ids)) {
        echo "<p style='color:red;'>กรุณาเลือกบุคคลอย่างน้อย 1 คน</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO playlist_items (playlist_id, person_id) VALUES (?, ?)");
        foreach ($person_ids as $person_id) {
            $stmt->bind_param("ii", $playlist_id, $person_id);
            $stmt->execute();
        }
        echo "<p style='color:green;'>เพิ่มบุคคลลงเพลลิสต์สำเร็จ!</p>";
    }
}
?>

<h2>เพิ่มบุคคลลงเพลลิสต์</h2>
<form method="POST">
    <label>เลือกเพลลิสต์:</label>
    <select name="playlist_id" required>
        <option value="">-- เลือกเพลลิสต์ --</option>
        <?php
        $playlists = $conn->query("SELECT * FROM playlists");
        while ($row = $playlists->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['title']}</option>";
        }
        ?>
    </select>
    <br><br>

    <label>เลือกบุคคล:</label><br>
    <?php
    $persons = $conn->query("SELECT * FROM persons");
    while ($row = $persons->fetch_assoc()) {
        echo "<label>
                <input type='checkbox' name='person_id[]' value='{$row['id']}'>
                {$row['name']}
              </label><br>";
    }
    ?>
    <br>

    <button type="submit">เพิ่มลงเพลลิสต์</button>
</form>