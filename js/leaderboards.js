document.addEventListener("DOMContentLoaded", function () {
    const leaderboard = document.getElementById("leaderboard");

    function getBadgeColor(index) {
        if (index === 0) return "bg-warning text-dark";
        if (index === 1) return "bg-secondary";
        if (index === 2) return "bg-danger";
        return "bg-primary";
    }

    function getRank(index) {
        if (index === 0) return "🥇";
        if (index === 1) return "🥈";
        if (index === 2) return "🥉";
        return `${index + 1}.`;
    }

    function loadLeaderboard() {
        fetch("php/get_colleges.php")
            .then(response => response.json())
            .then(colleges => {
                leaderboard.innerHTML = "";

                colleges.forEach((college, index) => {
                    const li = document.createElement("li");
                    li.className = "list-group-item d-flex justify-content-between align-items-center py-3";

                    li.innerHTML = `
                        <div>
                            <span class="fw-bold">${getRank(index)} ${college.name}</span>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-success add10">+10</button>
                                <button class="btn btn-sm btn-dark add50">+50</button>
                            </div>
                        </div>

                        <span class="badge ${getBadgeColor(index)} rounded-pill fs-6">
                            ${college.points} pts
                        </span>
                    `;

                    li.querySelector(".add10").addEventListener("click", () => {
                        updatePoints(college.id, 10);
                    });

                    li.querySelector(".add50").addEventListener("click", () => {
                        updatePoints(college.id, 50);
                    });

                    leaderboard.appendChild(li);
                });
            })
            .catch(error => {
                console.error("Error loading leaderboard:", error);
            });
    }

    function updatePoints(id, points) {
        const formData = new FormData();
        formData.append("id", id);
        formData.append("points", points);

        fetch("php/update_points.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLeaderboard();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error updating points:", error);
        });
    }

    loadLeaderboard();
});