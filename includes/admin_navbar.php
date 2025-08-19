<?php
// ถ้าไม่ได้กำหนดค่า $currentPage มาก่อน ให้เป็นค่าว่าง
$currentPage = $currentPage ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="admin_dashboard.php">
      <i class="fas fa-cogs me-2"></i>Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= $currentPage=='dashboard'?'active':'' ?>" href="admin_dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage=='playlists'?'active':'' ?>" href="admin_playlists.php">Playlists</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage=='persons'?'active':'' ?>" href="admin_persons.php">บุคคล</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $currentPage=='categories'?'active':'' ?>" href="admin_categories.php">หมวดหมู่</a>
        </li>
        
      </ul>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-1"></i>Admin
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">โปรไฟล์</a></li>
            <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>