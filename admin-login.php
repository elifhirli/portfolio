<?php require_once "includes/admin_auth.php"; ?>
<?php include "config/database.php"; ?>

<?php
if (isAdminLoggedIn()) {
    header('Location: /portfolio/admin.php');
    exit;
}

$error = '';
startAdminSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'INVALID_SECURITY_TOKEN';
    } elseif (loginAdmin($conn, $_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: /portfolio/admin.php');
        exit;
    } else {
        $error = 'INVALID_ADMIN_CREDENTIALS';
    }
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<main>
    <section class="admin-login">
        <div class="section-header">
            <h2>ADMIN_LOGIN</h2>
            <span>SECURE_SESSION</span>
        </div>

        <form class="admin-form admin-login-form" method="post" action="admin-login.php" autocomplete="off">
            <span class="panel-title">AUTH_REQUIRED</span>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">

            <?php if ($error !== '') { ?>
                <p class="admin-alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php } ?>

            <div class="admin-form-field">
                <label for="username">USERNAME</label>
                <input id="username" type="text" name="username" required>
            </div>

            <div class="admin-form-field">
                <label for="password">PASSWORD</label>
                <input id="password" type="password" name="password" required>
            </div>

            <button class="admin-button" type="submit">LOGIN</button>
        </form>
    </section>
</main>

<?php include "includes/footer.php"; ?>
