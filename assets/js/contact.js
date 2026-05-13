const contactForm = document.getElementById("contact-form");

if (contactForm) {
    contactForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(contactForm);
        const status = document.getElementById("form-status");

        if (status) {
            status.textContent = "TRANSMITTING_MESSAGE...";
        }

        try {
            const response = await fetch("/portfolio/ajax/contact-submit.php", {
                method: "POST",
                body: formData
            });

            const result = await response.text();

            if (status) {
                status.textContent = result;
            }

            if (response.ok) {
                contactForm.reset();
            }
        } catch (error) {
            if (status) {
                status.textContent = "TRANSMISSION_ERROR";
            }
        }
    });
}
