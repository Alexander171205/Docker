<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $total_usuarios = $conn->query("SELECT COUNT(*) FROM tbl_users")->fetchColumn();
    $total_empleos = $conn->query("SELECT COUNT(*) FROM tbl_jobs")->fetchColumn();
    $total_empresas = $conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employer'")->fetchColumn();
} catch(PDOException $e) { $error = $e->getMessage(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pro - Empleatec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; display: flex; min-height: 100vh; overflow-x: hidden; }
        .sidebar { width: 280px; background: #1a1c23; color: #fff; position: fixed; height: 100vh; padding: 20px; z-index: 1000; }
        .main-content { margin-left: 280px; width: calc(100% - 280px); padding: 40px; }
        .nav-link { color: #9e9e9e; padding: 12px 15px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #3f51b5; color: white !important; }
        .stat-card { border: none; border-radius: 16px; padding: 25px; transition: 0.3s; color: white; }
        .bg-gradient-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-green { background: linear-gradient(135deg, #2af598 0%, #009efd 100%); }
        .bg-gradient-orange { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
        .chart-container { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-5">
        <h3 class="fw-bold text-primary">EMPLEA<span class="text-white">TEC</span></h3>
        <small class="text-muted">ADMINISTRADOR</small>
    </div>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link active"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        <a href="usuarios.php" class="nav-link"><i class="fas fa-user-friends me-2"></i> Usuarios</a>
        <a href="reportes.php" class="nav-link"><i class="fas fa-file-alt me-2"></i> Reportes</a>
        <div class="mt-5">
            <a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
        </div>
    </nav>
</div>

<div class="main-content">
    <header class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Vista General</h2>
        <div class="d-flex align-items-center">
            <span class="me-3 text-muted">Hola, <strong><?php echo $_SESSION['myfname']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="rounded-circle" width="45">
        </div>
    </header>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card bg-gradient-blue shadow">
                <p class="mb-1 opacity-75">Usuarios Registrados</p>
                <h2 class="fw-bold"><?php echo $total_usuarios; ?></h2>
                <i class="fas fa-users float-end opacity-25" style="font-size: 3rem; margin-top: -40px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-green shadow">
                <p class="mb-1 opacity-75">Empleos Activos</p>
                <h2 class="fw-bold"><?php echo $total_empleos; ?></h2>
                <i class="fas fa-briefcase float-end opacity-25" style="font-size: 3rem; margin-top: -40px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-gradient-orange shadow">
                <p class="mb-1 opacity-75">Empresas Aliadas</p>
                <h2 class="fw-bold"><?php echo $total_empresas; ?></h2>
                <i class="fas fa-building float-end opacity-25" style="font-size: 3rem; margin-top: -40px;"></i>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <h5 class="fw-bold mb-4">Crecimiento de la Plataforma</h5>
        <canvas id="mainChart" height="100"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Interacciones',
                data: [12, 19, 3, 5, 2, <?php echo $total_usuarios; ?>],
                borderColor: '#3f51b5',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(63, 81, 181, 0.1)'
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
</body>
</html>