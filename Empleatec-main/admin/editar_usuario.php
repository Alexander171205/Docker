<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("location:../login.php"); exit(); }
require '../constants/db_config.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$id = $_GET['id'];

// Lógica para actualizar
if (isset($_POST['update'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $city = $_POST['city'];

    $stmt = $conn->prepare("UPDATE tbl_users SET first_name = :fname, last_name = :lname, email = :email, role = :role, city = :city WHERE member_no = :id");
    $stmt->execute([
        ':fname' => $fname, ':lname' => $lname, ':email' => $email, ':role' => $role, ':city' => $city, ':id' => $id
    ]);
    header("location:usuarios.php?msg=updated");
}

// Consultar datos actuales
$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :id");
$stmt->execute([':id' => $id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Empleatec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .edit-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
        .profile-header { background: linear-gradient(45deg, #3f51b5, #5c6bc0); padding: 40px; text-align: center; color: white; }
        .profile-img { width: 120px; height: 120px; border: 5px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .form-label { font-weight: 600; color: #555; font-size: 0.9rem; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #e0e0e0; }
        .form-control:focus { border-color: #3f51b5; box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.1); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="usuarios.php" class="text-decoration-none text-muted fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Volver al listado
                </a>
            </div>

            <div class="card edit-card">
                <div class="profile-header">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $u['first_name']; ?>&size=128&background=fff&color=3f51b5" class="rounded-circle profile-img mb-3">
                    <h3 class="mb-0"><?php echo $u['first_name'] . " " . $u['last_name']; ?></h3>
                    <p class="opacity-75 mb-0">ID de Usuario: #<?php echo $u['member_no']; ?></p>
                </div>
                
                <div class="card-body p-5">
                    <form method="POST">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre(s)</label>
                                <input type="text" name="fname" class="form-control" value="<?php echo $u['first_name']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido(s)</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo $u['last_name']; ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $u['email']; ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ciudad / Ubicación</label>
                                <input type="text" name="city" class="form-control" value="<?php echo $u['city']; ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Rol en la Plataforma</label>
                                <select name="role" class="form-select form-control">
                                    <option value="employee" <?php if($u['role'] == 'employee') echo 'selected'; ?>>Candidato (Employee)</option>
                                    <option value="employer" <?php if($u['role'] == 'employer') echo 'selected'; ?>>Empresa (Employer)</option>
                                    <option value="admin" <?php if($u['role'] == 'admin') echo 'selected'; ?>>Administrador del Sistema</option>
                                </select>
                            </div>

                            <div class="col-md-12 mt-5">
                                <button type="submit" name="update" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>