function generateQR() {
    let firstName = document.getElementById("firstname").value.trim();
    let lastName = document.getElementById("lastname").value.trim();

    if (!firstName || !lastName) {
        alert("User session not found. Please log in.");
        return;
    }

    let qrData = `${firstName} ${lastName}`;
    let qrImageGen = document.getElementById("qrImageGen");

    qrImageGen.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}`;
}