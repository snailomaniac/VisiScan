console.log("Camera.js loaded"); // Debugging line

document.addEventListener("DOMContentLoaded", function () {
    let cameraFeed = document.getElementById("cameraFeed");
    let cameraContainer = document.getElementById("cameraContainer");
    let imagePreview = document.getElementById("imagePreview");
    let capturedImage = document.getElementById("capturedImage");
    let reasoningSection = document.getElementById("reasoningSection");
    let reasonInput = document.getElementById("reasonInput");
    let qrImageGen = document.getElementById("qrImageGen");
    let qrSection = document.getElementById("qrSection");

    let username = document.getElementById("username").value.trim(); // Ensure username is captured

    if (!username) {
        console.error("Username not found in session.");
        return;
    }

    reasoningSection.style.display = "none";
    qrSection.style.display = "none";

    if (hasProfilePicture) {
        reasoningSection.style.display = "block";
        return;
    }

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: { width: 720, height: 720, facingMode: "user" } })
        .then(s => {
            stream = s;
            cameraFeed.srcObject = stream;
            cameraContainer.style.display = "flex";
        })
        .catch(error => {
            console.error("Error accessing camera:", error);
            alert("Camera access denied.");
        });
    }

    window.captureImage = function () {
        let canvas = document.createElement("canvas");
        let context = canvas.getContext("2d");

        let sideLength = Math.min(cameraFeed.videoWidth, cameraFeed.videoHeight);
        canvas.width = sideLength;
        canvas.height = sideLength;

        let xOffset = (cameraFeed.videoWidth - sideLength) / 2;
        let yOffset = (cameraFeed.videoHeight - sideLength) / 2;

        context.drawImage(cameraFeed, xOffset, yOffset, sideLength, sideLength, 0, 0, sideLength, sideLength);
        
        let imageData = canvas.toDataURL("image/png");

        cameraContainer.style.display = "none";
        imagePreview.style.display = "block";
        capturedImage.src = imageData;
    };

    window.retakeImage = function () {
        cameraContainer.style.display = "flex";
        imagePreview.style.display = "none";
        startCamera();
    };

    window.confirmImage = function () {
        let imageData = capturedImage.src;
        if (!imageData) {
            alert("Please capture an image first.");
            return;
        }

        let formData = new FormData();
        formData.append("image", imageData);

        fetch("upload_profile.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Profile picture updated successfully!");
                imagePreview.style.display = "none";
                reasoningSection.style.display = "block";
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error uploading image:", error);
            alert("Error uploading image.");
        });
    };

    window.submitReason = function () {
        let username = document.getElementById("username").value.trim();
        let reason = document.getElementById("reasonInput").value.trim();
        
        if (!username) {
            alert("Username is required!");
            return;
        }
        if (!reason) {
            alert("Please enter a reason.");
            return;
        }
    
        let maxReasonLength = 50; 
        if (reason.length > maxReasonLength) {
            reason = reason.substring(0, maxReasonLength) + "...";
        }
    
        let qrText = username + " - " + reason;
    
        if (typeof generateQR === "function") {
            generateQR(qrText);
        } else {
            console.error("generateQR function is not defined!");
        }
    };    

    startCamera();
});
