<?php
session_start();
require_once '../constants/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['errorMsg'] = true;
        header("Location: ../login.php");
        exit();
    }
    
    try {
        // Conectar a la base de datos
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Buscar usuario por email
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verificar contraseña
            $stored_password = $user['login'];
            
            // Comprobar si la contraseña está hasheada con MD5 o es texto plano
            if (md5($password) === $stored_password || $password === $stored_password) {
                
                // Login exitoso - Configurar sesión
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $user['member_no'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['role'] = $user['role'];
                
                // Actualizar último login
                $update_stmt = $conn->prepare("UPDATE tbl_users SET last_login = NOW() WHERE member_no = :member_no");
                $update_stmt->execute([':member_no' => $user['member_no']]);
                
                // Redirigir según el rol
                switch ($user['role']) {
                    case 'admin':
                        header("Location: ../admin/dashboard.php");
                        break;
                    
                    case 'employer':
                        header("Location: ../employer/");
                        break;
                    
                    case 'employee':
                        header("Location: ../employee/");
                        break;
                    
                    default:
                        // Si no tiene rol definido, redirigir a la página principal
                        header("Location: ../index.php");
                        break;
                }
                exit();
                
            } else {
                // Contraseña incorrecta
                $_SESSION['errorMsg'] = true;
                header("Location: ../login.php?error=invalid_credentials");
                exit();
            }
            
        } else {
            // Usuario no encontrado
            $_SESSION['errorMsg'] = true;
            header("Location: ../login.php?error=user_not_found");
            exit();
        }
        
    } catch(PDOException $e) {
        // Error de base de datos
        $_SESSION['errorMsg'] = true;
        header("Location: ../login.php?error=db_error");
        exit();
    }
    
} else {
    // Acceso directo no permitido
    header("Location: ../login.php");
    exit();
}
?>