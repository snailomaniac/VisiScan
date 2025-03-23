function startScanner() {
    let qrScanner = new Html5Qrcode("qrReader");
    let lastScannedText = null;
    let debounceTimeout = null;

    qrScanner.start(
        { facingMode: "environment" },
        {
            fps: 60,
            qrbox: 300
        },
        (decodedText) => {
            if (decodedText !== lastScannedText) {
                lastScannedText = decodedText;
                
                let username = decodedText.split(" ")[0]; // Extract first word as username
                
                if (debounceTimeout) {
                    clearTimeout(debounceTimeout);
                }
                debounceTimeout = setTimeout(() => {
                    document.getElementById("qrResult").innerText = `Scanned: ${username}`;
                    console.log("New QR scanned:", decodedText);
                    onScanSuccess(decodedText, qrScanner);
                }, 1000);
            }
        },
        (errorMessage) => {
            console.error("QR scanning error:", errorMessage);
        }
    ).catch(err => {
        console.error("Failed to start QR scanner.", err);
    });
}

function onScanSuccess(decodedText, qrScanner) {
    let parts = decodedText.split(" ");
    let username = parts.shift(); // Extract first word as username
    let reason = parts.join(" ").trim(); // Join remaining words as reason

    // Limit reason length (adjust the limit as needed)
    if (reason.length > 50) {
        reason = reason.substring(0, 50) + "..."; // Trim long reasons
    }

    document.getElementById("qrResult").innerText = `Scanned: ${username}`;
    
    qrScanner.stop().then(() => {
        console.log("QR scanner stopped.");

        fetch('logscan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: username, reason: reason }) // Include reason
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Scan logged successfully');

                const actionMessage = data.action === "Check-In" ? "Checked In" : "Checked Out";
                const actionColor = data.action === "Check-In" ? "#0a281d" : "#cdefe3";
                const actionBgColor = data.action === "Check-In" ? "#cdefe3" : "#aa3b3b";

                if (data.user) {
                    const userInfo = document.getElementById("qrUserInfo");
                    const profilePic = data.user.profile_picture ? encodeURI(data.user.profile_picture) : 'default_profile_picture.png';

                    userInfo.innerHTML = `
                        <div class="user-details">
                            <p style="background-color: ${actionBgColor}; color: ${actionColor}; font-weight: bold; padding: 4px 8px; border-radius: 4px">${actionMessage} Successfully!</p>
                            <p><strong>Name:</strong> ${htmlspecialchars(data.user.first_name)} ${htmlspecialchars(data.user.last_name)}</p>
                            <p><strong>Username:</strong> ${htmlspecialchars(username)}</p>
                            <p><strong>Account Verified:</strong> ${htmlspecialchars(data.user.account_status)}</p>
                            <p><strong>Phone:</strong> ${htmlspecialchars(data.user.phone)}</p>
                            <p><strong>Address:</strong> ${htmlspecialchars(data.user.address)}</p>
                            <p><strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                            <p><strong>Purpose of Visit:</strong> ${htmlspecialchars(data.reason)}</p>
                        </div>
                    `;

                    userInfo.style.display = 'block';
                }

                const container = document.querySelector('.content-details');
                const scanAgainBtn = document.createElement('button');
                scanAgainBtn.innerText = 'Scan Again';
                scanAgainBtn.className = 'scan-again-btn';
                scanAgainBtn.onclick = function() {
                    location.reload();
                };
                container.appendChild(scanAgainBtn);
            } else {
                console.error('Failed to log scan:', data.message);
                document.getElementById("qrUserInfo").innerText = 'Error: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById("qrUserInfo").innerText = 'Error processing scan';
        });
    }).catch(err => {
        console.error("Failed to stop QR scanner.", err);
    });
}

function htmlspecialchars(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/"/g, "&quot;")
              .replace(/'/g, "&#039;");
}

window.addEventListener('load', startScanner);
