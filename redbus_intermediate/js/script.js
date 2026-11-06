// Seat selection: selected seats are stored in the page's DOM.
document.addEventListener("DOMContentLoaded", function () {
    const seatButtons = document.querySelectorAll(".seat:not(.booked)");
    seatButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            button.classList.toggle("selected");
            updateSeatTotal();
        });
    });

    const filter = document.getElementById("busFilter");
    if (filter) filter.addEventListener("keyup", filterBusList);
});

function updateSeatTotal() {
    const selected = document.querySelectorAll(".seat.selected");
    let total = 0;
    selected.forEach(function (seat) {
        total += Number(seat.dataset.fare || 0);
    });
    const totalBox = document.getElementById("seatTotal");
    if (totalBox) totalBox.textContent = "Total: ₹" + total;
    const hidden = document.getElementById("selectedSeats");
    if (hidden) {
        const names = [];
        selected.forEach(function (seat) { names.push(seat.dataset.seat); });
        hidden.value = names.join(",");
    }
}

function validateRegistration() {
    const name = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value;

    const checks = [
        [ /^[A-Za-z ]{3,50}$/.test(name), "regNameError", "Use 3–50 letters and spaces." ],
        [ /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email), "regEmailError", "Enter a valid email." ],
        [ /^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/.test(password), "regPasswordError", "Use 6+ characters with a letter and number." ],
    ];
    let valid = true;
    checks.forEach(function (item) {
        document.getElementById(item[1]).textContent = item[0] ? "" : item[2];
        if (!item[0]) valid = false;
    });
    return valid;
}

function validateRegistration() {
    const name = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value;

    const checks = [
        [/^[A-Za-z ]{3,50}$/.test(name),
            "regNameError", "Use 3–50 letters and spaces."],

        [/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email),
            "regEmailError", "Enter a valid email."],

        [/^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/.test(password),
            "regPasswordError", "Use 6+ characters with a letter and number."]
    ];

    let valid = true;

    checks.forEach(function (item) {
        document.getElementById(item[1]).textContent =
            item[0] ? "" : item[2];

        if (!item[0]) valid = false;
    });

    return valid;
}

function validatePassenger() {
    const name = document.getElementById("passengerName").value.trim();
    const email = document.getElementById("passengerEmail").value.trim();
    const phone = document.getElementById("passengerPhone").value.trim();

    const nameOK = /^[A-Za-z ]{3,50}$/.test(name);
    const emailOK = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(email);
    const phoneOK = /^[6-9][0-9]{9}$/.test(phone);

    document.getElementById("nameError").textContent =
        nameOK ? "" : "Enter a valid name.";

    document.getElementById("emailError").textContent =
        emailOK ? "" : "Enter a valid email.";

    document.getElementById("phoneError").textContent =
        phoneOK ? "" : "Enter a valid 10-digit mobile number.";

    return nameOK && emailOK && phoneOK;
}


function filterBusList() {
    const value = document.getElementById("busFilter").value.toLowerCase();
    document.querySelectorAll("#busList li").forEach(function (item) {
        item.style.display = item.textContent.toLowerCase().includes(value) ? "" : "none";
    });
}

function showHelp() {
    const box = document.getElementById("helpBox");
    if (box) box.textContent = "Enter the information requested in this field.";
}
function hideHelp() {
    const box = document.getElementById("helpBox");
    if (box) box.textContent = "";
}
function bringToFront(panel) {
    document.querySelectorAll(".overlap-panel").forEach(function (item) { item.style.zIndex = "1"; });
    panel.style.zIndex = "5";
}

document.addEventListener("mousemove", function (event) {
    const box = document.getElementById("mousePosition");
    if (box) {
        box.textContent = "Client: (" + event.clientX + ", " + event.clientY +
            ") | Screen: (" + event.screenX + ", " + event.screenY + ")";
    }
});
document.addEventListener("keydown", function (event) {
    const box = document.getElementById("keyInfo");
    if (box) box.textContent = "Key pressed: " + event.key;
});