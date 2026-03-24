// Admin dashboard JavaScript
document.addEventListener("DOMContentLoaded", function () {
    // Load colleges for the points form
    loadColleges();

    // Handle points form submission
    const pointsForm = document.getElementById("pointsForm");
    if (pointsForm) {
        pointsForm.addEventListener("submit", function (e) {
            e.preventDefault();
            updatePoints();
        });
    }

    // Handle event form submission
    const eventForm = document.getElementById("eventForm");
    if (eventForm) {
        eventForm.addEventListener("submit", function (e) {
            e.preventDefault();
            updateEvent();
        });
    }
});

function loadColleges() {
    fetch("../backend/getColleges.php")
        .then(response => response.json())
        .then(data => {
            const collegeSelect = document.getElementById("collegeId");
            collegeSelect.innerHTML = '<option value="">Select a college</option>';

            data.forEach(college => {
                const option = document.createElement("option");
                option.value = college.id;
                option.textContent = college.name;
                collegeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error loading colleges:", error);
            document.getElementById("collegeId").innerHTML = '<option value="">Error loading colleges</option>';
        });
}

function updatePoints() {
    const formData = new FormData(document.getElementById("pointsForm"));
    const messageDiv = document.getElementById("pointsMessage");

    fetch("../backend/updatePoints.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.className = data.success ? "alert alert-success mt-3" : "alert alert-danger mt-3";
        messageDiv.textContent = data.message;

        if (data.success) {
            document.getElementById("pointsForm").reset();
        }
    })
    .catch(error => {
        console.error("Error:", error);
        messageDiv.className = "alert alert-danger mt-3";
        messageDiv.textContent = "An error occurred while updating points.";
    });
}

function updateEvent() {
    const formData = new FormData(document.getElementById("eventForm"));
    const messageDiv = document.getElementById("eventMessage");

    fetch("../backend/updateEvents.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.className = data.success ? "alert alert-success mt-3" : "alert alert-danger mt-3";
        messageDiv.textContent = data.message;

        if (data.success) {
            document.getElementById("eventForm").reset();
        }
    })
    .catch(error => {
        console.error("Error:", error);
        messageDiv.className = "alert alert-danger mt-3";
        messageDiv.textContent = "An error occurred while updating event.";
    });
}