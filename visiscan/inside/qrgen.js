function generateQR() {
    let username = document.getElementById("username").value.trim();

    if (!username) {
        alert("User session not found. Please log in.");
        return;
    }

    let reasonInput = document.getElementById("reasonInput").value;
    if (!reasonInput) {
        alert("Please enter a reason for your visit.");
        return;
    }

    // Limit the total length to ensure the QR code is scannable
    const maxLength = 200;  // Adjust this based on your QR code size and readability
    let fullData = username + " " + reasonInput;

    if (fullData.length > maxLength) {
        // Trim the reason if the total length exceeds the max length
        let trimmedReason = reasonInput.substring(0, maxLength - username.length - 1);
        fullData = username + " " + trimmedReason;
    }

    let qrImageGen = document.getElementById("qrImageGen");
    qrImageGen.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(fullData)}`;
}

window.submitReason = function () {
    let username = document.getElementById("username").value.trim();
    let reasonText = document.getElementById("reasonInput").value.trim();

    if (!username) {
        alert("Username not found.");
        return;
    }
    if (!reasonText) {
        alert("Please enter a reason.");
        return;
    }

    let maxReasonLength = 50;
    if (reasonText.length > maxReasonLength) {
        reasonText = reasonText.substring(0, maxReasonLength) + "...";
    }

    let qrData = `${username} ${reasonText}`;

    document.getElementById("qrImageGen").src =
        `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}`;

    document.getElementById("reasoningSection").style.display = "none";
    document.getElementById("qrSection").style.display = "block";

    console.log("QR Code generated with:", qrData);
};

document.addEventListener("DOMContentLoaded", function () {
});
