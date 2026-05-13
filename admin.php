<?php
require_once "includes/admin_auth.php";
requireAdminLogin();
include "config/database.php";

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function countByQuery($conn, $query) {
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_row($result);
    return (int) ($row[0] ?? 0);
}

function findProject($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, title, description, technologies, image, github_link, live_link, status FROM projects WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_assoc($result) : null;
}

function findExperience($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, company, role, start_date, end_date, technologies, details, sort_order FROM experience WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_assoc($result) : null;
}

$message = '';
$error = '';
$editingProject = null;
$editingExperience = null;

if (isset($_GET['edit'])) {
    $editingProject = findProject($conn, (int) $_GET['edit']);
}

if (isset($_GET['edit_experience'])) {
    $editingExperience = findExperience($conn, (int) $_GET['edit_experience']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'INVALID_SECURITY_TOKEN';
    } elseif (($_POST['form_type'] ?? '') === 'delete_project') {
        $projectId = (int) ($_POST['project_id'] ?? 0);

        if ($projectId <= 0) {
            $error = 'INVALID_PROJECT_ID';
        } else {
            $stmt = mysqli_prepare($conn, "DELETE FROM projects WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $projectId);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'PROJECT_DELETED';

                if ($editingProject && (int) $editingProject['id'] === $projectId) {
                    $editingProject = null;
                }
            } else {
                $error = 'PROJECT_DELETE_FAILED';
            }
        }
    } elseif (($_POST['form_type'] ?? '') === 'experience') {
        $experienceId = (int) ($_POST['experience_id'] ?? 0);
        $company = trim($_POST['company'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');
        $experienceTechnologies = trim($_POST['experience_technologies'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $startDateValue = $startDate !== '' ? $startDate : null;
        $endDateValue = $endDate !== '' ? $endDate : null;

        if ($company === '' || $role === '' || $details === '') {
            $error = 'PLEASE_FILL_REQUIRED_EXPERIENCE_FIELDS';
        } elseif ($experienceId > 0) {
            $stmt = mysqli_prepare($conn, "UPDATE experience SET company = ?, role = ?, start_date = ?, end_date = ?, technologies = ?, details = ?, sort_order = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssssssii", $company, $role, $startDateValue, $endDateValue, $experienceTechnologies, $details, $sortOrder, $experienceId);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'EXPERIENCE_UPDATED';
                $editingExperience = findExperience($conn, $experienceId);
            } else {
                $error = 'EXPERIENCE_UPDATE_FAILED';
            }
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO experience (company, role, start_date, end_date, technologies, details, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssssi", $company, $role, $startDateValue, $endDateValue, $experienceTechnologies, $details, $sortOrder);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'EXPERIENCE_CREATED';
            } else {
                $error = 'EXPERIENCE_CREATE_FAILED';
            }
        }
    } else {
        $projectId = (int) ($_POST['project_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $technologies = trim($_POST['technologies'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $githubLink = trim($_POST['github_link'] ?? '');
        $liveLink = trim($_POST['live_link'] ?? '');
        $status = trim($_POST['status'] ?? 'completed');
        $allowedStatuses = ['completed', 'in_progress', 'offline'];

        if ($title === '' || $description === '' || $technologies === '' || !in_array($status, $allowedStatuses, true)) {
            $error = 'PLEASE_FILL_REQUIRED_FIELDS';
        } elseif ($projectId > 0) {
            $stmt = mysqli_prepare($conn, "UPDATE projects SET title = ?, description = ?, technologies = ?, image = ?, github_link = ?, live_link = ?, status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $technologies, $image, $githubLink, $liveLink, $status, $projectId);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'PROJECT_UPDATED';
                $editingProject = findProject($conn, $projectId);
            } else {
                $error = 'PROJECT_UPDATE_FAILED';
            }
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO projects (title, description, technologies, image, github_link, live_link, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, "sssssss", $title, $description, $technologies, $image, $githubLink, $liveLink, $status);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'PROJECT_CREATED';
            } else {
                $error = 'PROJECT_CREATE_FAILED';
            }
        }
    }
}

$totalProjects = countByQuery($conn, "SELECT COUNT(*) FROM projects");
$completedProjects = countByQuery($conn, "SELECT COUNT(*) FROM projects WHERE status = 'completed'");
$inProgressProjects = countByQuery($conn, "SELECT COUNT(*) FROM projects WHERE status = 'in_progress'");
$offlineProjects = countByQuery($conn, "SELECT COUNT(*) FROM projects WHERE status = 'offline'");
$totalExperience = countByQuery($conn, "SELECT COUNT(*) FROM experience");
$projects = mysqli_query($conn, "SELECT id, title, status, technologies, created_at FROM projects ORDER BY created_at DESC");
$experienceRows = mysqli_query($conn, "SELECT id, company, role, start_date, end_date, technologies, sort_order FROM experience ORDER BY sort_order ASC, start_date DESC");
?>

<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<main>
    <?php if (isset($_COOKIE['last_admin_login'])) { ?>

        <div class="admin-cookie-info">
            LAST LOGIN:
            <?php echo htmlspecialchars($_COOKIE['last_admin_login']); ?>
        </div>

    <?php } ?>

<section class="admin-dashboard">
    <div class="section-header">
        <h2>ADMIN_DASHBOARD</h2>
        <span><a href="admin-logout.php">LOGOUT</a></span>
    </div>

    <div class="admin-metrics">
        <article class="admin-metric">
            <span>TOTAL_PROJECTS</span>
            <strong><?php echo $totalProjects; ?></strong>
        </article>

        <article class="admin-metric">
            <span>COMPLETED</span>
            <strong><?php echo $completedProjects; ?></strong>
        </article>

        <article class="admin-metric">
            <span>IN_PROGRESS</span>
            <strong><?php echo $inProgressProjects; ?></strong>
        </article>

        <article class="admin-metric">
            <span>OFFLINE</span>
            <strong><?php echo $offlineProjects; ?></strong>
        </article>

        <article class="admin-metric">
            <span>EXPERIENCE</span>
            <strong><?php echo $totalExperience; ?></strong>
        </article>
    </div>

    <div class="admin-grid">
        <section class="admin-panel">
            <span class="panel-title"><?php echo $editingProject ? 'EDIT_PROJECT' : 'ADD_PROJECT'; ?></span>

            <form class="admin-form" method="post" action="admin.php<?php echo $editingProject ? '?edit=' . (int) $editingProject['id'] : ''; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <input type="hidden" name="project_id" value="<?php echo e($editingProject['id'] ?? '0'); ?>">

                <?php if ($message !== '') { ?>
                    <p class="admin-alert is-success"><?php echo e($message); ?></p>
                <?php } ?>

                <?php if ($error !== '') { ?>
                    <p class="admin-alert"><?php echo e($error); ?></p>
                <?php } ?>

                <div class="admin-form-field">
                    <label for="title">TITLE *</label>
                    <input id="title" type="text" name="title" value="<?php echo e($editingProject['title'] ?? ''); ?>" required>
                </div>

                <div class="admin-form-field">
                    <label for="description">DESCRIPTION *</label>
                    <textarea id="description" name="description" rows="5" required><?php echo e($editingProject['description'] ?? ''); ?></textarea>
                </div>

                <div class="admin-form-field">
                    <label for="technologies">TECHNOLOGIES *</label>
                    <input id="technologies" type="text" name="technologies" value="<?php echo e($editingProject['technologies'] ?? ''); ?>" required>
                </div>

                <div class="admin-form-field">
                    <label for="image">IMAGE_PATH</label>
                    <input id="image" type="text" name="image" value="<?php echo e($editingProject['image'] ?? ''); ?>">
                </div>

                <div class="admin-form-field">
                    <label for="github_link">GITHUB_LINK</label>
                    <input id="github_link" type="url" name="github_link" value="<?php echo e($editingProject['github_link'] ?? ''); ?>">
                </div>

                <div class="admin-form-field">
                    <label for="live_link">LIVE_LINK</label>
                    <input id="live_link" type="url" name="live_link" value="<?php echo e($editingProject['live_link'] ?? ''); ?>">
                </div>

                <div class="admin-form-field">
                    <label for="status">STATUS *</label>
                    <?php $currentStatus = $editingProject['status'] ?? 'completed'; ?>
                    <select id="status" name="status" required>
                        <option value="completed" <?php echo $currentStatus === 'completed' ? 'selected' : ''; ?>>completed</option>
                        <option value="in_progress" <?php echo $currentStatus === 'in_progress' ? 'selected' : ''; ?>>in_progress</option>
                        <option value="offline" <?php echo $currentStatus === 'offline' ? 'selected' : ''; ?>>offline</option>
                    </select>
                </div>

                <div class="admin-actions">
                    <button class="admin-button" type="submit"><?php echo $editingProject ? 'UPDATE_PROJECT' : 'ADD_PROJECT'; ?></button>
                    <?php if ($editingProject) { ?>
                        <a class="admin-button secondary" href="admin.php">CANCEL_EDIT</a>
                    <?php } ?>
                </div>
            </form>
        </section>

        <section class="admin-panel">
            <span class="panel-title">PROJECT_RECORDS</span>

            <div class="admin-table">
                <div class="admin-table-head">
                    <span>TITLE</span>
                    <span>STATUS</span>
                    <span>TECH</span>
                    <span>ACTION</span>
                </div>

                <?php if ($projects && mysqli_num_rows($projects) > 0) { ?>
                    <?php while ($project = mysqli_fetch_assoc($projects)) { ?>
                        <div class="admin-table-row">
                            <strong><?php echo e($project['title']); ?></strong>
                            <span><?php echo e($project['status']); ?></span>
                            <span><?php echo e($project['technologies']); ?></span>
                            <span class="admin-row-actions">
                                <a href="admin.php?edit=<?php echo (int) $project['id']; ?>">EDIT</a>
                                <form method="post" action="admin.php" onsubmit="return confirm('Delete this project?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <input type="hidden" name="form_type" value="delete_project">
                                    <input type="hidden" name="project_id" value="<?php echo (int) $project['id']; ?>">
                                    <button class="admin-link-button" type="submit">DELETE</button>
                                </form>
                            </span>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="admin-empty">NO_PROJECT_RECORDS_FOUND</p>
                <?php } ?>
            </div>
        </section>
    </div>

    <div class="admin-grid admin-grid-spaced">
        <section class="admin-panel">
            <span class="panel-title"><?php echo $editingExperience ? 'EDIT_EXPERIENCE' : 'ADD_EXPERIENCE'; ?></span>

            <form class="admin-form" method="post" action="admin.php<?php echo $editingExperience ? '?edit_experience=' . (int) $editingExperience['id'] : ''; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <input type="hidden" name="form_type" value="experience">
                <input type="hidden" name="experience_id" value="<?php echo e($editingExperience['id'] ?? '0'); ?>">

                <div class="admin-form-field">
                    <label for="company">COMPANY *</label>
                    <input id="company" type="text" name="company" value="<?php echo e($editingExperience['company'] ?? ''); ?>" required>
                </div>

                <div class="admin-form-field">
                    <label for="role">ROLE *</label>
                    <input id="role" type="text" name="role" value="<?php echo e($editingExperience['role'] ?? ''); ?>" required>
                </div>

                <div class="admin-form-split">
                    <div class="admin-form-field">
                        <label for="start_date">START_DATE</label>
                        <input id="start_date" type="date" name="start_date" value="<?php echo e($editingExperience['start_date'] ?? ''); ?>">
                    </div>

                    <div class="admin-form-field">
                        <label for="end_date">END_DATE</label>
                        <input id="end_date" type="date" name="end_date" value="<?php echo e($editingExperience['end_date'] ?? ''); ?>">
                    </div>
                </div>

                <div class="admin-form-field">
                    <label for="experience_technologies">TECHNOLOGIES</label>
                    <input id="experience_technologies" type="text" name="experience_technologies" value="<?php echo e($editingExperience['technologies'] ?? ''); ?>">
                </div>

                <div class="admin-form-field">
                    <label for="details">DETAILS *</label>
                    <textarea id="details" name="details" rows="6" required><?php echo e($editingExperience['details'] ?? ''); ?></textarea>
                </div>

                <div class="admin-form-field">
                    <label for="sort_order">SORT_ORDER</label>
                    <input id="sort_order" type="number" name="sort_order" value="<?php echo e($editingExperience['sort_order'] ?? '0'); ?>">
                </div>

                <div class="admin-actions">
                    <button class="admin-button" type="submit"><?php echo $editingExperience ? 'UPDATE_EXPERIENCE' : 'ADD_EXPERIENCE'; ?></button>
                    <?php if ($editingExperience) { ?>
                        <a class="admin-button secondary" href="admin.php">CANCEL_EDIT</a>
                    <?php } ?>
                </div>
            </form>
        </section>

        <section class="admin-panel">
            <span class="panel-title">EXPERIENCE_RECORDS</span>

            <div class="admin-table admin-table-experience">
                <div class="admin-table-head">
                    <span>COMPANY</span>
                    <span>ROLE</span>
                    <span>PERIOD</span>
                    <span>ACTION</span>
                </div>

                <?php if ($experienceRows && mysqli_num_rows($experienceRows) > 0) { ?>
                    <?php while ($experience = mysqli_fetch_assoc($experienceRows)) { ?>
                        <div class="admin-table-row">
                            <strong><?php echo e($experience['company']); ?></strong>
                            <span><?php echo e($experience['role']); ?></span>
                            <span>
                                <?php
                                $startDate = !empty($experience['start_date']) ? date('m.Y', strtotime($experience['start_date'])) : '-';
                                $endDate = !empty($experience['end_date']) ? date('m.Y', strtotime($experience['end_date'])) : 'Present';
                                echo e($startDate . ' - ' . $endDate);
                                ?>
                            </span>
                            <span><a href="admin.php?edit_experience=<?php echo (int) $experience['id']; ?>">EDIT</a></span>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="admin-empty">NO_EXPERIENCE_RECORDS_FOUND</p>
                <?php } ?>
            </div>
        </section>
    </div>
</section>

</main>

<?php include "includes/footer.php"; ?>
