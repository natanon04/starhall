<?php
$currentPage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $currentPage = 'dashboard'; include './includes/admin_navbar.php'; ?>

<div class="container mt-5">
    <h1>สวัสดี Admin</h1>
    <p>นี่คือ Dashboard สำหรับจัดการระบบ</p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-music fa-2x mb-2"></i>
                    <h5 class="card-title">Playlists</h5>
                    <a href="admin_playlists.php" class="btn btn-primary btn-sm mt-2">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-user fa-2x mb-2"></i>
                    <h5 class="card-title">บุคคล</h5>
                    <a href="admin_persons.php" class="btn btn-primary btn-sm mt-2">จัดการ</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h5 class="card-title">หมวดหมู่</h5>
                    <a href="admin_categories.php" class="btn btn-primary btn-sm mt-2">จัดการ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>