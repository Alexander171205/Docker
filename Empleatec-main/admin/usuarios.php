<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

if(isset($_GET['del'])){
    $stmt = $conn->prepare("DELETE FROM tbl_users WHERE member_no = :id");
    $stmt->bindParam(':id', $_GET['del']);
    $stmt->execute();
    header("location:usuarios.php");
}
$usuarios = $conn->query("SELECT * FROM tbl_users WHERE role != 'admin' ORDER BY member_no DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Empleatec Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 40px; }
        .table-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .badge-role { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .bg-employer { background: #e3f2fd; color: #1976d2; }
        .bg-employee { background: #f3e5f5; color: #7b1fa2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0">Gestión de Usuarios</h2>
                <p class="text-muted">Administra empresas y candidatos registrados.</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-chevron-left me-2"></i> Dashboard
            </a>
        </div>

        <div class="table-card">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Usuario</th>
                        <th>Contacto</th>
                        <th>Rol</th>
                        <th>Ubicación</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?php echo $u['first_name']; ?>&background=random" class="rounded-circle me-3" width="40">
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo $u['first_name']." ".$u['last_name']; ?></h6>
                                    <small class="text-muted">ID: <?php echo $u['member_no']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $u['email']; ?></td>
                        <td>
                            <span class="badge-role <?php echo $u['role'] == 'employer' ? 'bg-employer' : 'bg-employee'; ?>">
                                <?php echo strtoupper($u['role']); ?>
                            </span>
                        </td>
                        <td><i class="fas fa-map-marker-alt text-danger me-1"></i> <?php echo $u['city']; ?></td>
                        <td class="text-end pe-4">
                            <a href="editar_usuario.php?id=<?php echo $u['member_no']; ?>" class="btn btn-light btn-sm rounded-circle me-2 text-primary" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="usuarios.php?del=<?php echo $u['member_no']; ?>" class="btn btn-light btn-sm rounded-circle text-danger" onclick="return confirm('¿Seguro?')" title="Eliminar"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>