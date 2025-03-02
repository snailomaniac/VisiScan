function startScanner() {
    let qrScanner = new Html5Qrcode("qrReader");

    qrScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        (decodedText) => {
            document.getElementById("qrResult").innerText = "Scanned: " + decodedText;

            // to Prevent from scanning same multiple qrcode
            if (window.lastScannedCode !== decodedText) {
                window.lastScannedCode = decodedText;
                console.log("New QR scanned:", decodedText);
            }
        },
        (errorMessage) => {}
    );
}

startScanner();

function onScanSuccess(qrMessage) {
    console.log("Scanned QR Code: ", qrMessage); // Just to debug
    fetch('logscan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => console.log("Server Response:", data))
    .catch(error => console.error("Error:", error));
    
}
