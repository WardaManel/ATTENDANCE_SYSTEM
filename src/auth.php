<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function login($email, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'role'  => $user['role'],
            'email' => $user['email']
        ];
        return true;
    }
    return false;
}


function logout() {
    
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header("Location: /Attendance_System/public/login.php");
    exit;
}


function is_logged_in() {
    return isset($_SESSION['user']);
}

function is_admin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

function is_professor() {
    return is_logged_in() && $_SESSION['user']['role'] === 'professor';
}

function is_student() {
    return is_logged_in() && $_SESSION['user']['role'] === 'student';
}

function require_login($roles = []) {
    if (!is_logged_in()) {
        header("Location: /Attendance_System/public/login.php");
        exit;
    }

    if (!empty($roles) && !in_array($_SESSION['user']['role'], $roles)) {
        echo "<h1>Access Denied</h1>";
        exit;
    }
}
