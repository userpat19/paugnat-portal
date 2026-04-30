/**
 * admin.js
 * Manages all admin dashboard interactions:
 *   - Loading and displaying colleges and events
 *   - Updating college points with validation
 *   - Creating, editing, and deleting events with validation
 */

/** @type {Array<{id: number, event_name: string, event_date: string}>} */
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

/**
 * Fetches colleges from the backend and populates the dropdown and standings table.
 */
function loadColleges() {
    fetch("../backend/getColleges.php")
        .then(function (response) {
            if (!response.ok) throw new Error("Network error: " + response.status);
            return response.json();
        })
        .then(function (collegesData) {
            const collegeDropdown = document.getElementById("collegeId");
            const collegeStandingsTable = document.getElementById("collegesTable");

            if (!Array.isArray(collegesData) || collegesData.length === 0) {
                collegeDropdown.innerHTML = '<option value="">No colleges found</option>';
                collegeStandingsTable.innerHTML = '<tr><td colspan="3" class="text-center opacity-75">No colleges available.</td></tr>';
                return;
            }

            collegeDropdown.innerHTML = '<option value="">Select a college</option>';
            collegeStandingsTable.innerHTML = "";

            collegesData.forEach(function (college, rankIndex) {
                // Populate the dropdown
                const dropdownOption = document.createElement("option");
                dropdownOption.value = college.id;
                dropdownOption.textContent = college.name;
                collegeDropdown.appendChild(dropdownOption);

                // Populate the standings table
                const tableRow = document.createElement("tr");
                tableRow.innerHTML = `
                    <td class="opacity-75">${rankIndex + 1}</td>
                    <td class="fw-bold">${college.name}</td>
                    <td class="text-end text-ustp-gold fw-bold">${college.points} pts</td>
                `;
                collegeStandingsTable.appendChild(tableRow);
            });
        })
        .catch(function (fetchError) {
            console.error("Error loading colleges:", fetchError);
            document.getElementById("collegeId").innerHTML = '<option value="">Error loading colleges</option>';
            document.getElementById("collegesTable").innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load colleges.</td></tr>';
        });
}

/**
 * Fetches events from the backend and populates the event dropdown and upcoming events table.
 */
function loadEvents() {
    fetch("../backend/getEvents.php")
        .then(function (response) {
            if (!response.ok) throw new Error("Network error: " + response.status);
            return response.json();
        })
        .then(function (eventsData) {
            const eventDropdown = document.getElementById("eventSelect");
            const upcomingEventsTable = document.getElementById("eventsTable");

            cachedEventsList = Array.isArray(eventsData) ? eventsData : [];

            eventDropdown.innerHTML = '<option value="">Create New Event</option>';
            upcomingEventsTable.innerHTML = "";

            if (cachedEventsList.length === 0) {
                upcomingEventsTable.innerHTML = '<tr><td colspan="3" class="text-center opacity-75">No events available.</td></tr>';
                return;
            }

            cachedEventsList.forEach(function (event) {
                // Populate the event selection dropdown
                const dropdownOption = document.createElement("option");
                dropdownOption.value = event.id;
                dropdownOption.textContent = `${event.event_name} (${event.event_date})`;
                eventDropdown.appendChild(dropdownOption);

                // Populate the upcoming events table
                const tableRow = document.createElement("tr");
                tableRow.innerHTML = `
                    <td class="opacity-75">${event.id}</td>
                    <td class="fw-bold">${event.event_name}</td>
                    <td class="text-end text-info">${event.event_date}</td>
                `;
                upcomingEventsTable.appendChild(tableRow);
            });
        })
        .catch(function (fetchError) {
            console.error("Error loading events:", fetchError);
            document.getElementById("eventSelect").innerHTML = '<option value="">Error loading events</option>';
            document.getElementById("eventsTable").innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load events.</td></tr>';
        });
}

// ─── Event Form Helpers ───────────────────────────────────────────────────────

/**
 * Fills the event name and date fields based on the selected event ID.
 * Clears fields when "Create New Event" is selected (eventId is empty).
 *
 * @param {string} selectedEventId - The ID of the selected event, or "" for a new event.
 */
function populateEventFields(selectedEventId) {
    const matchedEvent = cachedEventsList.find(function (event) {
        return String(event.id) === String(selectedEventId);
    });

    document.getElementById("eventName").value = matchedEvent ? matchedEvent.event_name : "";
    document.getElementById("eventDate").value = matchedEvent ? matchedEvent.event_date : "";

    const imageFileInput = document.getElementById("eventImage");
    if (imageFileInput) imageFileInput.value = "";

    const deleteEventButton = document.getElementById("deleteEventBtn");
    if (deleteEventButton) deleteEventButton.disabled = !selectedEventId;
}

// ─── Action Handlers ──────────────────────────────────────────────────────────

/**
 * Validates and submits the points update form.
 * Requires a college to be selected and points to be a valid number.
 */
function handleUpdatePoints() {
    const pointsMessageDiv = document.getElementById("pointsMessage");
    const selectedCollegeId = document.getElementById("collegeId").value;
    const pointsInput = document.getElementById("points");
    const pointsValue = pointsInput.value.trim();

    // Client-side validation
    if (!selectedCollegeId) {
        showFeedback(pointsMessageDiv, false, "Please select a college before updating points.");
        return;
    }

    if (pointsValue === "" || isNaN(Number(pointsValue))) {
        showFeedback(pointsMessageDiv, false, "Please enter a valid number for points.");
        return;
    }

    const formData = new FormData(document.getElementById("pointsForm"));

    fetch("../backend/updatePoints.php", {
        method: "POST",
        body: formData
    })
    .then(function (response) {
        if (!response.ok) throw new Error("Network error: " + response.status);
        return response.json();
    })
    .then(function (responseData) {
        showFeedback(pointsMessageDiv, responseData.success, responseData.message);

        if (responseData.success) {
            document.getElementById("pointsForm").reset();
            loadColleges();
            setTimeout(function () { location.reload(); }, 1500);
        }
    })
    .catch(function (fetchError) {
        console.error("Error updating points:", fetchError);
        showFeedback(pointsMessageDiv, false, "An error occurred while updating points. Please try again.");
    });
}

/**
 * Validates and submits the event creation/update form.
 * Requires a non-empty event name and a valid date.
 */
function handleSaveEvent() {
    const eventMessageDiv = document.getElementById("eventMessage");
    const eventNameInput = document.getElementById("eventName");
    const eventDateInput = document.getElementById("eventDate");
    const eventNameValue = eventNameInput.value.trim();
    const eventDateValue = eventDateInput.value.trim();

    // Client-side validation
    if (!eventNameValue) {
        showFeedback(eventMessageDiv, false, "Event name cannot be empty.");
        eventNameInput.focus();
        return;
    }

    if (!eventDateValue) {
        showFeedback(eventMessageDiv, false, "Please select a valid event date.");
        eventDateInput.focus();
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
    .then(function (response) {
        if (!response.ok) throw new Error("Network error: " + response.status);
        return response.json();
    })
    .then(function (responseData) {
        showFeedback(eventMessageDiv, responseData.success, responseData.message);

        if (responseData.success) {
            document.getElementById("eventForm").reset();
            eventDropdown.value = "";
            loadEvents();
            setTimeout(function () { location.reload(); }, 1500);
        }
    })
    .catch(function (fetchError) {
        console.error("Error saving event:", fetchError);
        showFeedback(eventMessageDiv, false, "An error occurred while saving the event. Please try again.");
    });
}

/**
 * Confirms and deletes the currently selected event.
 * Called from the Delete Event button's onclick attribute.
 */
function deleteEvent() {
    const selectedEventId = document.getElementById("eventSelect").value;

    if (!selectedEventId) {
        showFeedback(document.getElementById("eventMessage"), false, "No event selected for deletion.");
        return;
    }

    const isConfirmed = confirm("Are you sure you want to delete this event? This action cannot be undone.");
    if (!isConfirmed) return;

    const eventMessageDiv = document.getElementById("eventMessage");
    const deletePayload = new FormData();
    deletePayload.append("id", selectedEventId);

    fetch("../backend/deleteEvent.php", { method: "POST", body: deletePayload })
        .then(function (response) {
            if (!response.ok) throw new Error("Network error: " + response.status);
            return response.json();
        })
        .then(function (responseData) {
            showFeedback(eventMessageDiv, responseData.success, responseData.message);

            if (responseData.success) {
                document.getElementById("eventForm").reset();
                document.getElementById("eventSelect").value = "";
                document.getElementById("deleteEventBtn").disabled = true;
                loadEvents();
                setTimeout(function () { location.reload(); }, 1200);
            }
        })
        .catch(function (fetchError) {
            console.error("Error deleting event:", fetchError);
            showFeedback(eventMessageDiv, false, "An error occurred while deleting the event. Please try again.");
        });
}

// ─── Utility ─────────────────────────────────────────────────────────────────

/**
 * Displays a success or error alert inside a given container element.
 *
 * @param {HTMLElement} containerElement - The DOM element to render feedback into.
 * @param {boolean}     isSuccess        - True for a success alert, false for danger.
 * @param {string}      feedbackMessage  - The message text to display.
 */
function showFeedback(containerElement, isSuccess, feedbackMessage) {
    const alertClass = isSuccess ? "alert-success" : "alert-danger";
    containerElement.innerHTML = `<div class="alert ${alertClass} mt-3">${feedbackMessage}</div>`;
}