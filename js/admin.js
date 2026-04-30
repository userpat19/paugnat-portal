/**
 * admin.js
 * Manages all admin dashboard interactions:
 *   - Loading and displaying colleges and events
 *   - Updating college points with validation
 *   - Creating, editing, and deleting events with validation
 */

/** @type {Array<{id: number, eventName: string, eventDate: string}>} */
let cachedEventsList = [];

// ─── Initialisation ──────────────────────────────────────────────────────────

document.addEventListener("DOMContentLoaded", function () {
    loadColleges();
    loadEvents();
    attachPointsFormListener();
    attachEventFormListener();
    attachEventSelectListener();
});

// ─── Event Listeners ─────────────────────────────────────────────────────────

function attachPointsFormListener() {
    const pointsForm = document.getElementById("pointsForm");
    if (pointsForm) {
        pointsForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleUpdatePoints();
        });
    }
}

function attachEventFormListener() {
    const eventForm = document.getElementById("eventForm");
    if (eventForm) {
        eventForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleSaveEvent();
        });
    }
}

function attachEventSelectListener() {
    const eventSelect = document.getElementById("eventSelect");
    if (eventSelect) {
        eventSelect.addEventListener("change", function () {
            populateEventFields(this.value);
        });
    }
}

// ─── Data Loading ─────────────────────────────────────────────────────────────

function loadColleges() {
    fetch("../backend/getColleges.php")
        .then(response => {
            if (!response.ok) throw new Error("Network error: " + response.status);
            return response.json();
        })
        .then(collegesData => {
            const collegeDropdown = document.getElementById("collegeId");
            const collegeStandingsTable = document.getElementById("collegesTable");

            if (!Array.isArray(collegesData) || collegesData.length === 0) {
                collegeDropdown.innerHTML = '<option value="">No colleges found</option>';
                collegeStandingsTable.innerHTML =
                    '<tr><td colspan="3" class="text-center opacity-75">No colleges available.</td></tr>';
                return;
            }

            collegeDropdown.innerHTML = '<option value="">Select a college</option>';
            collegeStandingsTable.innerHTML = "";

            collegesData.forEach((college, rankIndex) => {
                const dropdownOption = document.createElement("option");
                dropdownOption.value = college.id;
                dropdownOption.textContent = college.name;
                collegeDropdown.appendChild(dropdownOption);

                const tableRow = document.createElement("tr");
                tableRow.innerHTML = `
                    <td class="opacity-75">${rankIndex + 1}</td>
                    <td class="fw-bold">${college.name}</td>
                    <td class="text-end text-ustp-gold fw-bold">${college.points} pts</td>
                `;
                collegeStandingsTable.appendChild(tableRow);
            });
        })
        .catch(error => {
            console.error("Error loading colleges:", error);
            document.getElementById("collegeId").innerHTML =
                '<option value="">Error loading colleges</option>';
            document.getElementById("collegesTable").innerHTML =
                '<tr><td colspan="3" class="text-center text-danger">Failed to load colleges.</td></tr>';
        });
}

function loadEvents() {
    fetch("../backend/getEvents.php")
        .then(response => {
            if (!response.ok) throw new Error("Network error: " + response.status);
            return response.json();
        })
        .then(eventsData => {
            const eventDropdown = document.getElementById("eventSelect");
            const upcomingEventsTable = document.getElementById("eventsTable");

            cachedEventsList = Array.isArray(eventsData) ? eventsData : [];

            eventDropdown.innerHTML = '<option value="">Create New Event</option>';
            upcomingEventsTable.innerHTML = "";

            if (cachedEventsList.length === 0) {
                upcomingEventsTable.innerHTML =
                    '<tr><td colspan="3" class="text-center opacity-75">No events available.</td></tr>';
                return;
            }

            cachedEventsList.forEach(event => {
                const dropdownOption = document.createElement("option");
                dropdownOption.value = event.id;
                dropdownOption.textContent =
                    `${event.eventName} (${event.eventDate})`;
                eventDropdown.appendChild(dropdownOption);

                const tableRow = document.createElement("tr");
                tableRow.innerHTML = `
                    <td class="opacity-75">${event.id}</td>
                    <td class="fw-bold">${event.eventName}</td>
                    <td class="text-end text-info">${event.eventDate}</td>
                `;
                upcomingEventsTable.appendChild(tableRow);
            });
        })
        .catch(error => {
            console.error("Error loading events:", error);
            document.getElementById("eventSelect").innerHTML =
                '<option value="">Error loading events</option>';
            document.getElementById("eventsTable").innerHTML =
                '<tr><td colspan="3" class="text-center text-danger">Failed to load events.</td></tr>';
        });
}

// ─── Event Helpers ───────────────────────────────────────────────────────────

function populateEventFields(selectedEventId) {
    const matchedEvent = cachedEventsList.find(event =>
        String(event.id) === String(selectedEventId)
    );

    document.getElementById("eventName").value =
        matchedEvent ? matchedEvent.eventName : "";

    document.getElementById("eventDate").value =
        matchedEvent ? matchedEvent.eventDate : "";

    const imageFileInput = document.getElementById("eventImage");
    if (imageFileInput) imageFileInput.value = "";

    const deleteEventButton = document.getElementById("deleteEventBtn");
    if (deleteEventButton) deleteEventButton.disabled = !selectedEventId;
}

// ─── Save / Update Event ─────────────────────────────────────────────────────

function handleSaveEvent() {
    const eventMessageDiv = document.getElementById("eventMessage");
    const eventNameValue = document.getElementById("eventName").value.trim();
    const eventDateValue = document.getElementById("eventDate").value.trim();

    if (!eventNameValue) {
        showFeedback(eventMessageDiv, false, "Event name cannot be empty.");
        return;
    }

    if (!eventDateValue) {
        showFeedback(eventMessageDiv, false, "Please select a valid event date.");
        return;
    }

    const eventDropdown = document.getElementById("eventSelect");
    const selectedEventId = eventDropdown.value;

    const formData = new FormData(document.getElementById("eventForm"));

    if (selectedEventId) {
        formData.append("id", selectedEventId);
    }

    fetch("../backend/updateEvents.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            showFeedback(eventMessageDiv, data.success, data.message);

            if (data.success) {
                document.getElementById("eventForm").reset();
                eventDropdown.value = "";
                loadEvents();
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(err => {
            console.error(err);
            showFeedback(eventMessageDiv, false, "Error saving event.");
        });
}

// ─── Delete Event ────────────────────────────────────────────────────────────

function deleteEvent() {
    const selectedEventId = document.getElementById("eventSelect").value;

    if (!selectedEventId) {
        showFeedback(document.getElementById("eventMessage"), false, "No event selected.");
        return;
    }

    if (!confirm("Delete this event?")) return;

    const formData = new FormData();
    formData.append("id", selectedEventId);

    fetch("../backend/deleteEvent.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            showFeedback(document.getElementById("eventMessage"), data.success, data.message);

            if (data.success) {
                document.getElementById("eventForm").reset();
                document.getElementById("eventSelect").value = "";
                document.getElementById("deleteEventBtn").disabled = true;
                loadEvents();
            }
        })
        .catch(err => {
            console.error(err);
            showFeedback(document.getElementById("eventMessage"), false, "Delete failed.");
        });
}

// ─── Utility ────────────────────────────────────────────────────────────────

function showFeedback(container, success, message) {
    container.innerHTML =
        `<div class="alert ${success ? "alert-success" : "alert-danger"} mt-3">${message}</div>`;
}