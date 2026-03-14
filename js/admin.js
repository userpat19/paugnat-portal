function updatePoints() {

    const college = document.getElementById("college").value;
    const points = document.getElementById("points").value;
    const message = document.getElementById("message");

    if (points === "" || Number(points) <= 0) {
        message.innerHTML = '<span class="text-danger">Please enter a valid number of points.</span>';
        return;
    }

    fetch("php/update_points.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${college}&points=${points}`
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            message.innerHTML = '<span class="text-success">Score updated successfully.</span>';
            document.getElementById("points").value = "";
        } 
        else {
            message.innerHTML = `<span class="text-danger">${data.message}</span>`;
        }

    })
    .catch(error => {
        console.error(error);
        message.innerHTML = '<span class="text-danger">Server error.</span>';
    });

}


// Secret admin shortcut
document.addEventListener("keydown", function(e){

    if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "a") {
        window.location.href = "admin.html";
    }

});