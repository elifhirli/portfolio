<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>
<?php include "config/database.php"; ?>

<?php
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); //çekilen veri sayfanın yapısını bozmasın diye
} 
?>
<main>

<section class="page-header">
    <h1>My Projects</h1>
    <p>Here are some of the projects I have worked on.</p>
</section>

<section class="project-section">
    <div class="section-header">
        <h2>COMPLETED_PROJECTS</h2>
        <span>STATUS: STABLE</span>
    </div>

    <div class="projects-container">
        <?php
        $query = "SELECT * FROM projects WHERE status = 'completed' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($project = mysqli_fetch_assoc($result)) {
                $isEduStream = strtolower(trim($project['title'])) === 'edustream app';
                $eduStreamQrImage = 'assets/images/projects/edustream-qr.jpg';
        ?>
                <div class="project-card">
                    <div class="<?php echo $isEduStream ? 'project-slider' : 'project-preview'; ?>">
                        <?php if (!empty($project['image'])) { ?>
                            <img src="<?php echo e(trim($project['image'])); ?>" alt="<?php echo e($project['title']); ?> preview">
                        <?php } else { ?>
                            <span>PROJECT_PREVIEW</span>
                        <?php } ?>

                        <?php if ($isEduStream) { ?>
                            <img src="<?php echo e($eduStreamQrImage); ?>" alt="<?php echo e($project['title']); ?> QR scanner preview">
                        <?php } ?>
                    </div>

                    <div class="project-content">
                        <h3><?php echo strtoupper(e($project['title'])); ?></h3>
                        <p><?php echo e($project['description']); ?></p>
                        <span><?php echo strtoupper(e($project['technologies'])); ?></span>

                        <div class="project-links">
                            <?php if (!empty($project['github_link'])) { ?>
                                <a href="<?php echo e($project['github_link']); ?>" target="_blank" rel="noopener noreferrer">GITHUB</a>
                            <?php } else { ?>
                                <span class="server-unavailable">SOURCE PRIVATE</span>
                            <?php } ?>

                            <?php if (!empty($project['live_link'])) { ?>
                                <a href="<?php echo e($project['live_link']); ?>" target="_blank" rel="noopener noreferrer">LIVE DEMO</a>
                            <?php } elseif ($isEduStream) { ?>
                                <span class="server-unavailable">LIVE LINK SOON</span>
                            <?php } else { ?>
                                <span class="server-unavailable">SERVER UNAVAILABLE</span>
                            <?php } ?>
                        </div>


                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='empty-message'>NO_COMPLETED_PROJECTS_FOUND</p>";
        }
        ?>
    </div>
</section>

<section class="project-section">
    <div class="section-header">
        <h2>IN_PROGRESS_PROJECTS</h2>
        <span>STATUS: BUILDING</span>
    </div>

    <div class="projects-container">
        <?php
        $query = "SELECT * FROM projects WHERE status = 'in_progress' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($project = mysqli_fetch_assoc($result)) {
        ?>
                <div class="project-card in-progress">
                    <?php
                    $projectTitle = strtolower(trim($project['title']));
                    $isEduStream = $projectTitle === 'edustream app';
                    $isEmlak = $projectTitle === 'emlak.ai';
                    $eduStreamQrImage = 'assets/images/projects/edustream-qr.jpg';
                    $emlakSecondImage = 'assets/images/projects/emlak2.jpg';
                    ?>

                    <div class="<?php echo ($isEduStream || $isEmlak) ? 'project-slider' : 'project-preview'; ?>">
                        <?php if (!empty($project['image'])) { ?>
                            <img src="<?php echo e(trim($project['image'])); ?>" alt="<?php echo e($project['title']); ?> preview">
                        <?php } else { ?>
                            <span>BUILD_PREVIEW</span>
                        <?php } ?>

                        <?php if ($isEduStream) { ?>
                            <img src="<?php echo e($eduStreamQrImage); ?>" alt="<?php echo e($project['title']); ?> QR scanner preview">
                        <?php } elseif ($isEmlak) { ?>
                            <img src="<?php echo e($emlakSecondImage); ?>" alt="<?php echo e($project['title']); ?> dashboard preview">
                        <?php } ?>
                    </div>

                    <div class="project-content">
                        <h3><?php echo strtoupper(e($project['title'])); ?></h3>
                        <p><?php echo e($project['description']); ?></p>
                        <span><?php echo strtoupper(e($project['technologies'])); ?></span>

                        <div class="project-links">
                            <?php if (!empty($project['github_link'])) { ?>
                                <a href="<?php echo e($project['github_link']); ?>" target="_blank" rel="noopener noreferrer">GITHUB</a>
                            <?php } else { ?>
                                <span class="private-repo">PRIVATE REPO</span>
                            <?php } ?>

                            <?php if (!empty($project['live_link'])) { ?>
                                <a href="<?php echo e($project['live_link']); ?>" target="_blank" rel="noopener noreferrer">VIEW PROTOTYPE</a>
                            <?php } ?>
                        </div>
                    </div>

                </div>
        <?php
            }
        } else {
            echo "<p class='empty-message'>NO_IN_PROGRESS_PROJECTS_FOUND</p>";
        }
        ?>
    </div>
</section>

<section class="project-section">
    <div class="section-header">
        <h2>OFFLINE_PROJECTS</h2>
        <span>SERVER: UNAVAILABLE</span>
    </div>

    <div class="projects-container">
        <?php
        $query = "SELECT * FROM projects WHERE status = 'offline' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($project = mysqli_fetch_assoc($result)) {
        ?>
                <div class="project-card offline">
                    <div class="project-slider">
                        <img src="<?php echo e($project['image']); ?>" alt="<?php echo e($project['title']); ?> preview">
                        <img src="assets/images/projects/klima-2.jpg" alt="<?php echo e($project['title']); ?> preview">
                    </div>

                    <div class="project-content">
                        <h3><?php echo strtoupper(e($project['title'])); ?></h3>
                        <p><?php echo e($project['description']); ?></p>
                        <span><?php echo strtoupper(e($project['technologies'])); ?></span>

                        <div class="project-links">
                            <?php if (!empty($project['github_link'])) { ?>
                                <a href="<?php echo e($project['github_link']); ?>" target="_blank" rel="noopener noreferrer">GITHUB</a>
                            <?php } ?>

                            <span class="server-unavailable">SERVER UNAVAILABLE</span>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='empty-message'>NO_OFFLINE_PROJECTS_FOUND</p>";
        }
        ?>
    </div>
</section>


</main>

<?php include "includes/footer.php"; ?>
