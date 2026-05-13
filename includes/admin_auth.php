<?php

const ADMIN_SESSION_TIMEOUT = 1800;

function startAdminSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/portfolio',
        'domain' => '',
        'secure' => $secureCookie,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);

    session_name('portfolio_admin_session');
    session_start();
}

function isAdminLoggedIn() {
    startAdminSession();

    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }

    if (!empty($_SESSION['admin_last_activity']) && time() - $_SESSION['admin_last_activity'] > ADMIN_SESSION_TIMEOUT) {
        logoutAdmin();
        return false;
    }

    $_SESSION['admin_last_activity'] = time();
    return true;
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: /portfolio/admin-login.php');
        exit;
    }
}

function loginAdmin($conn, $username, $password) {
    startAdminSession();

    $stmt = mysqli_prepare($conn, "SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1");

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = $result ? mysqli_fetch_assoc($result) : null;

    if (!$admin || !password_verify((string) $password, $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = (int) $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_last_activity'] = time();

    if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
        $newHash = password_hash((string) $password, PASSWORD_DEFAULT);
        $updateStmt = mysqli_prepare($conn, "UPDATE admin_users SET password_hash = ? WHERE id = ?");

        if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, "si", $newHash, $_SESSION['admin_user_id']);
            mysqli_stmt_execute($updateStmt);
        }
    }

    return true;
}

function logoutAdmin() {
    startAdminSession();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

function csrfToken() {
    startAdminSession();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    startAdminSession();
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}

?>
