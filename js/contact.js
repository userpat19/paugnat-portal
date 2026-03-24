// Contact form handling
document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.querySelector("form");
    const messageBox = document.createElement("div");
    messageBox.id = "formMessage";
    contactForm.parentNode.insertBefore(messageBox, contactForm.nextSibling);

    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(contactForm);

            fetch("../backend/saveMessage.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageBox.innerHTML = `<div class="alert alert-success mt-3">${data.message}</div>`;
                    contactForm.reset();
                } else {
                    messageBox.innerHTML = `<div class="alert alert-danger mt-3">${data.message}</div>`;
                }
            })
            .catch(error => {
                messageBox.innerHTML = '<div class="alert alert-danger mt-3">Server error. Please try again later.</div>';
                console.error("Contact submit error:", error);
            });
        });
    }
});