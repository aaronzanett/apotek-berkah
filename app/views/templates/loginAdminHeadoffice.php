<?php
if(isset($_SESSION['pengguna']) && isset($_SESSION['outlet_name']) && isset($_SESSION['outlet_id']) && isset($_SESSION['outlet_type']) && isset($_SESSION['permissions'])){
    if($_SESSION['outlet_type'] == 'Headoffice') {
        header("Location: ".BASEURL."/app/headoffice/dashboard");
    }else if ($_SESSION['outlet_type'] == 'Admin') {
        header("Location: ".BASEURL."/app/admin/dashboard");
    }
    exit;
}

require_once("app/models/PenggunaModel.php");
require_once("app/models/RoleHakAksesModel.php");

$penggunaModel = new PenggunaModel();
$roleHakAksesModel = new RoleHakAksesModel();

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pengguna = $penggunaModel->getPenggunaByUsername($username);
    if(count($pengguna) === 1) {
        $penggunaData = $pengguna[0];
        $passwordInput = $penggunaModel->encryptData($password, $penggunaData['encryptkey'], $penggunaData['iv']);

        if($passwordInput === $penggunaData['encrypted_password']){
            $_SESSION["pengguna"] = $penggunaData['karyawan_fullname'];
            $_SESSION["outlet_name"] = $penggunaData['outlet_name'];
            $_SESSION["outlet_id"] = $penggunaData['outlet_id'];
            $_SESSION["outlet_type"] = $penggunaData['outlet_type'];
            $_SESSION["permissions"] = $roleHakAksesModel->getDataEditRole($penggunaData['role_id'], $penggunaData["outlet_type"]);

            if($penggunaData['outlet_type'] == 'Headoffice') {
                header("Location: ".BASEURL."/app/headoffice/dashboard");
            }else if ($penggunaData['outlet_type'] == 'Admin') {
                header("Location: ".BASEURL."/app/admin/dashboard");
            }
            exit;
        }
    }

    $errorToLogin = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Headoffice</title>
    <!-- icon -->
    <link rel="shortcut icon" href="<?=BASEURL?>/assets/img/logo-img/Apotek-berkah-icon.png">
    <!-- style -->
    <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/login-admin.css">
</head>
<body>
    
    <div class="login-container">
        <div class="login-hero">
        </div>
        <div class="login-input-container">
            <div class="input-container shadow">
                <img src="<?= BASEURL ?>/assets/img/logo-img/Apotek-berkah-nobg.png" id="apotekLogo">
                <h2>Headoffice / Admin Login</h2>
                <form action="" method="post">
                    <div class="login-input">
                        <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        <h5>Username</h5>
                        <input type="text" id="username" name="username" autocomplete="off" class="input" required>
                        <span class="line"></span>
                    </div>
                    <div class="login-input">
                        <img src="<?= BASEURL ?>/assets/img/icons/password.png">
                        <h5>Password</h5>
                        <input type="password" id="password" name="password" autocomplete="off" class="input" required>
                        <span class="line"></span>
                        <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" id="eye">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                        </svg>
                        
                        <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-eye-slash display-none" viewBox="0 0 16 16" id="eye-slash">
                            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                            <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                        </svg>
                    </div>
                    <?php if (isset($errorToLogin)) : ?>
                        <p class="loginError">Username atau password salah!</p>
                    <?php endif; ?>
                    
                    <input type="submit" id="submit" name="submit" value="Masuk">
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASEURL ?>/assets/js/adminlogin-script.js"></script>
</body>
</html>