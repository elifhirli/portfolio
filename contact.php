<?php include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<main>

<section class="contact-page">

    <div class="section-header">
        <h2>CONTACT</h2>
        <span>MESSAGE_TRANSMISSION</span>
    </div>

    <div class="contact-form-panel">

        <span class="panel-title">SEND_MESSAGE</span>

        <form id="contact-form">

            <div class="form-group">
                <label>FULL_NAME</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>EMAIL_ADDRESS</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>SUBJECT</label>
                <input type="text" name="subject" required>
            </div>

            <div class="form-group">
                <label>MESSAGE_BODY</label>
                <textarea name="message" rows="6" required></textarea>
            </div>

            <button type="submit" class="terminal-btn primary">
                TRANSMIT_MESSAGE
            </button>

        </form>

        <p id="form-status"></p>

    </div>

</section>

<?php include "includes/connection_endpoint.php"; ?>

</main>

<?php include "includes/footer.php"; ?>

