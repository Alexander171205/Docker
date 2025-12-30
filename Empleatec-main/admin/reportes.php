<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// Lógica para exportar a CSV (Compatible con Excel)
if (isset($_GET['export'])) {
    $tipo = $_GET['export'];
    $filename = "reporte_" . $tipo . "_" . date('Ymd') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    // Bom para que Excel detecte tildes correctamente
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    if ($tipo == 'usuarios') {
        fputcsv($output, array('ID', 'Nombre', 'Apellido', 'Email', 'Rol', 'Ciudad'));
        $query = $conn->query("SELECT member_no, first_name, last_name, email, role, city FROM tbl_users");
    } else {
        fputcsv($output, array('ID Empleo', 'Título', 'Ciudad', 'Categoría', 'Fecha Postulación'));
        $query = $conn->query("SELECT job_id, title, city, category, date_posted FROM tbl_jobs");
    }

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Centro de Reportes - Empleatec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .report-card { 
            background: white; 
            border: none; 
            border-radius: 20px; 
            transition: 0.3s; 
            border-bottom: 5px solid transparent;
        }
        .report-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-bottom: 5px solid #3f51b5;
        }
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="fw-bold">Centro de Reportes</h1>
            <p class="text-muted">Genera y descarga información detallada de la plataforma en formato Excel/CSV.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="dashboard.php" class="btn btn-dark rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i> Volver al Panel</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card report-card p-4 shadow-sm h-100">
                <div class="icon-box bg-primary text-white">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="fw-bold">Usuarios Totales</h4>
                <p class="text-muted small">Incluye nombres, correos electrónicos, roles (Candidato/Empresa) y ubicación geográfica.</p>
                <div class="mt-auto">
                    <a href="reportes.php?export=usuarios" class="btn btn-outline-primary w-100 rounded-pill">
                        <i class="fas fa-download me-2"></i> Descargar Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card report-card p-4 shadow-sm h-100">
                <div class="icon-box bg-success text-white">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h4 class="fw-bold">Vacantes Publicadas</h4>
                <p class="text-muted small">Listado completo de empleos, categorías más buscadas y fechas de publicación.</p>
                <div class="mt-auto">
                    <a href="reportes.php?export=empleos" class="btn btn-outline-success w-100 rounded-pill">
                        <i class="fas fa-download me-2"></i> Descargar Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card report-card p-4 shadow-sm h-100">
                <div class="icon-box bg-warning text-white">
                    <i class="fas fa-history"></i>
                </div>
                <h4 class="fw-bold">Análisis Mensual</h4>
                <p class="text-muted small">Resumen estadístico del crecimiento de la plataforma durante el último mes.</p>
                <div class="mt-auto">
                    <button class="btn btn-outline-warning w-100 rounded-pill" disabled>
                        <i class="fas fa-lock me-2"></i> Próximamente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 p-4 bg-white rounded-4 shadow-sm">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-primary fs-2 me-3"></i>
            </div>
            <div>
                <h6 class="mb-1 fw-bold">Nota sobre los archivos</h6>
                <p class="mb-0 text-muted small">Los archivos se generan en formato CSV codificado en UTF-8, lo que permite su apertura inmediata en Microsoft Excel, Google Sheets y Numbers.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>