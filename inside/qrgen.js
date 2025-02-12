function generateQR() {
    let Input = "Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.";
    let qrImageGen = document.getElementById("qrImageGen");

    // API used to generate QR code
    qrImageGen.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(Input)}`;
    //qrImageGen.style.display = "block";
}