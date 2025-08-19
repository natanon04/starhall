<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (function_exists('opcache_reset')) {
    opcache_reset();
}

include "includes/db.php";
$cats = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- โลโก้ -->
    <a class="navbar-brand" href="index.php" style="padding: 0; font-weight: bold;">
        <img src="logo.webp" alt="logo" style="width: 40px;"> StarHall
    </a>

    
    <?php 
if ($currentPage != 'home') {
    echo '<form action="search.php" method="get" class="d-flex">
  <div class="input-group" style="border-radius: 30px; overflow: hidden;">
    <input type="text" name="q" class="form-control" placeholder="ค้นหาบุคคลสำคัญ..." required>
    <button class="btn" type="submit" style="background-color: white; border: 1px solid #ccc;">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
  </div>
</form>';
}
?>
    <!-- ปุ่ม Hamburger สำหรับมือถือ -->
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- เมนูบนคอม -->
    <div class="d-none d-lg-flex ms-auto">
      <ul class="navbar-nav d-flex flex-row" style="gap: 20px;">
        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='home') echo 'active'; ?>" href="index.php">หน้าแรก</a>
        </li>

        <!-- Dropdown หมวดหมู่ -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php if($currentPage=='category') echo 'active'; ?>" 
             href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            หมวดหมู่
          </a>
          <ul class="dropdown-menu" style="  position: absolute !important; top: 100% !important; left: 0 !important; margin-top: 0.5rem; z-index: 1050; /* ให้อยู่บน */">
            <?php while($cat = $cats->fetch_assoc()): ?>
              <li><a class="dropdown-item" href="category.php?id=<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
              </a></li>
            <?php endwhile; ?>
          </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='playlist') echo 'active'; ?>" href="all_playlist.php">เพลย์ลิสต์</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='contact') echo 'active'; ?>" href="all_person.php">บุคคลทั้งหมด</a>
        </li>
       </ul>
    </div>
  </div>
</nav>

<!-- Offcanvas Sidebar มือถือ (เลื่อนจากซ้าย) -->
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">เมนู</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='home') echo 'active'; ?>" href="index.php">หน้าแรก</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='category') echo 'active'; ?>" href="category.php">หมวดหมู่</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='playlist') echo 'active'; ?>" href="all_playlist.php">เพลย์ลิสต์</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($currentPage=='contact') echo 'active'; ?>" href="all_person.php">บุคคลทั้งหมด</a>
        </li>
    </ul>
  </div>
</div>