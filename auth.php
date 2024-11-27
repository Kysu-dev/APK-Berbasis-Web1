<?php
session_start();
include 'koneksi.php';

// Mengaktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function debug($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Query untuk memeriksa kredensial
    $query = "SELECT id_karyawan, username, password, jabatan FROM karyawan WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    
    if($result) {
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Debug: Cek data user
            // debug($user);
            
            // Verifikasi password
            if($password == $user['password']) {
                // Set session
                $_SESSION['user_id'] = $user['id_karyawan'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['jabatan'] = $user['jabatan'];
                
                // Debug: Cek session
                // debug($_SESSION);
                
                // Redirect berdasarkan jabatan
                switch($user['jabatan']) {
                    case 'owner':
                        header("Location: dashboard_owner.php");
                        exit();
                    case 'admin':
                        header("Location: dashboard_admin.php");
                        exit();
                    case 'karyawan':
                        header("Location: dashboard_karyawan.php");
                        exit();
                    default:
                        header("Location: login.html?error=invalid_jabatan");
                        exit();
                }
            } else {
                header("Location: login.html?error=wrong_password");
                exit();
            }
        } else {
            header("Location: login.html?error=user_not_found");
            exit();
        }
    } else {
        header("Location: login.html?error=query_failed&msg=" . urlencode(mysqli_error($koneksi)));
        exit();
    }
}

// Jika sampai di sini, berarti form tidak disubmit dengan benar
header("Location: login.html?error=invalid_request");
exit();
?>