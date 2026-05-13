<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>
<?php include "config/database.php"; ?>

<?php
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<main>

    <section class="hero-terminal">

        <div class="status-bar">
            <span><span class="status-dot">■</span> STATUS: <strong>ACTIVE</strong></span>
            <span>LAST_COMMIT: <strong id="last-commit">SYNCING...</strong></span>
            <span>LOC: <strong>ISTANBUL, TR</strong></span>
            <span class="xref">X-REF: <strong>#49F2A</strong></span>
        </div>

        <p class="hero-label">SOFTWARE ENGINEER</p>

        <h1 class="hero-title">ELIF NUR HIRLI</h1>

        <div class="hero-box">
            <span>INSTITUTION_ID</span>
            HALIÇ UNIVERSITY / SOFTWARE ENGINEERING (3RD GRADE)
        </div>

        <p class="hero-desc">
            Third-year Software Engineering student with experience in full-stack development
            using React, Node.js, and Express.js. Skilled in RESTful API development, Python
            (NumPy, Matplotlib), and Object-Oriented Programming (Java). Passionate about
            building scalable and secure software solutions.
        </p>

    </section>

    <section class="dashboard-grid">

        <div class="dashboard-block">
            <h2 class="section-title">EDUCATION_MANIFEST</h2>

            <div class="terminal-panel">
                <span class="panel-title">ACADEMIC_RECORD</span>

                <p class="terminal-line degree"><strong>BACHELOR'S DEGREE</strong></p>
                <p class="terminal-line">Haliç University</p>
                <p class="terminal-line">Software Engineering (3rd Grade)</p>
                <p class="terminal-line">2022-Present</p>
                <p class="terminal-line"><span class="gpa">GPA: 3.14</span></p>
            </div>
        </div>

        <div class="dashboard-block">
            <h2 class="section-title">LANGUAGE_PROFICIENCY</h2>

            <div class="terminal-panel language-panel">
                <span class="panel-title"></span>

                <div class="language-row">
                    <strong>ENGLISH</strong>
                    <span>[ C1 ]</span>
                </div>

                <div class="language-row">
                    <strong>TURKISH</strong>
                    <span>[ NATIVE ]</span>
                </div>
            </div>
        </div>

    </section>

    <section class="experience-log">
        <div class="section-header">
            <h2>EXPERIENCE_LOG</h2>
            <span>CAREER_TRACE</span>
        </div>

        <div class="experience-grid">
            <?php
            $experienceQuery = "SELECT * FROM experience ORDER BY sort_order ASC, start_date DESC";
            $experienceResult = mysqli_query($conn, $experienceQuery);

            if ($experienceResult && mysqli_num_rows($experienceResult) > 0) {
                $entryNumber = 1;

                while ($experience = mysqli_fetch_assoc($experienceResult)) {
                    $lines = array_filter(array_map('trim', explode("\n", $experience['details'] ?? '')));
                    $startDate = !empty($experience['start_date']) ? date('m.Y', strtotime($experience['start_date'])) : '';
                    $endDate = !empty($experience['end_date']) ? date('m.Y', strtotime($experience['end_date'])) : 'Present';
            ?>
                    <article class="experience-card">
                        <span class="panel-title">EXP_ENTRY_<?php echo str_pad((string) $entryNumber, 2, '0', STR_PAD_LEFT); ?></span>

                        <h3><?php echo e($experience['company']); ?></h3>
                        <p class="experience-role">[<?php echo e($experience['role']); ?>]</p>
                        <p class="experience-period">[ <?php echo e($startDate); ?> - <?php echo e($endDate); ?> ]</p>

                        <?php if (!empty($experience['technologies'])) { ?>
                            <p class="experience-tech">[ <?php echo e($experience['technologies']); ?> ]</p>
                        <?php } ?>

                        <div class="experience-lines">
                            <?php foreach ($lines as $line) { ?>
                                <p>&gt; <?php echo e($line); ?></p>
                            <?php } ?>
                        </div>
                    </article>
            <?php
                    $entryNumber++;
                }
            } else {
                echo "<p class='empty-message'>NO_EXPERIENCE_LOG_FOUND</p>";
            }
            ?>
        </div>
    </section>

    <section class="featured-projects">
        <div class="section-header">
            <h2>FEATURED_PROJECTS</h2>
            <span>X-REF: 001 — 001</span>
        </div>

        <?php
        $query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        while ($project = mysqli_fetch_assoc($result)) {
        ?>
            <div class="featured-card">
                <span class="panel-title">PROJECT_MODULE_01</span>

                <div class="project-meta">
                    <span>[ <?php echo strtoupper($project['technologies']); ?> ]</span>
                    <!-- <span class="live-badge">[ LIVE ]</span> -->
                </div>

                <h3><?php echo strtoupper($project['title']); ?></h3>

                <p><?php echo $project['description']; ?></p>

                <?php if (!empty($project['live_link'])) { ?>
                    <a href="<?php echo $project['live_link']; ?>" target="_blank">
                        <?php echo strtoupper(parse_url($project['live_link'], PHP_URL_HOST) ?: 'LIVE_DEMO'); ?> ↗
                    </a>
                <?php } else { ?>
                    <span>LIVE_LINK_SOON</span>
                <?php } ?>
            </div>
        <?php } ?>

    </section>

    <section class="technical-stack" id="skills">

    <div class="section-header">
        <h2>TECHNICAL_STACK</h2>
        <span>CAPABILITIES_MANIFEST</span>
    </div>

    <div class="stack-layout">

        <div class="skills-grid">
            <span class="panel-title">SKILL_GRID_V1.0</span>

            <div class="skill-column">
                <h4>01 // LANGUAGES</h4>

                <div class="skill-row">
                    <span>PYTHON</span>
                    <small>ADVANCED</small>
                </div>

                <div class="skill-row">
                    <span>C++</span>
                    <small>ADVANCED</small>
                </div>

                <div class="skill-row">
                    <span>JS</span>
                    <small>INT-ADV</small>
                </div>

                <div class="skill-row">
                    <span>JAVA</span>
                    <small>INT-ADV</small>
                </div>

                <div class="skill-row">
                    <span>SQL</span>
                    <small>ADVANCED</small>
                </div>
            </div>

            <div class="skill-column">
                <h4>02 // FRAMEWORKS</h4>

                <div class="skill-row">
                    <span>NODE.JS</span>
                    <small>INTERMEDIATE</small>
                </div>

                <div class="skill-row">
                    <span>REACT.JS</span>
                    <small>INTERMEDIATE</small>
                </div>

                <div class="skill-row">
                    <span>.NET</span>
                    <small>BEG-INT</small>
                </div>

                <div class="skill-row">
                    <span>TAURI</span>
                    <small>BEG-INT</small>
                </div>
            </div>

            <div class="skill-column">
                <h4>03 // ARCHITECTURE</h4>

                <div class="skill-row">
                    <span>OOP</span>
                    <small>INT-ADV</small>
                </div>

                <div class="skill-row">
                    <span>REST API</span>
                    <small>INTERMEDIATE</small>
                </div>

                <div class="skill-row">
                    <span>DATA ANALYSIS</span>
                    <small>NUMPY/MPL</small>
                </div>
            </div>
        </div>

        <div class="stack-side">

            <div class="mini-panel">
                <span class="panel-title">GITHUB_CONTRIBUTIONS</span>

                <div class="contribution-grid small">
                    <?php for ($i = 0; $i < 72; $i++) { ?>
                        <span></span>
                    <?php } ?>
                </div>

                <div class="mini-panel-footer">
                    <span id="repo-count">LOADING...</span>
                    <span id="github-user">FETCHING...</span>
                </div>

                <div class="repo-list" id="repo-list">
                    <span>LOADING_REPOS...</span>
                </div>
            </div>

            <div class="mini-panel" id="system-log">
                <span class="panel-title">SYSTEM_LOG</span>

                <p class="terminal-line"><span class="ok">[OK]</span> LOAD_THEME_DARK</p>
                <p class="terminal-line"><span class="info">[INFO]</span> FETCHING_PROJECTS...</p>
                <p class="terminal-line"><span class="ok">[OK]</span> PORTFOLIO_LOADED</p>
                <p class="terminal-line"><span class="warn">[WARN]</span> LEARNING_MODE_ACTIVE</p>
                <p class="terminal-line">> AWAITING_INPUT_</p>
            </div>

        </div>

    </div>

</section>

<?php include "includes/connection_endpoint.php"; ?>

</main>

<?php include "includes/footer.php"; ?>
