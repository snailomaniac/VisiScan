async function loadAdminLogs() {
    try {
        let response = await fetch("admin_fetch_logs.php");
        let data = await response.json();
        
        console.log("Fetched data:", data); // Debugging

        let tableBody = document.getElementById("logTable");
        tableBody.innerHTML = "";

        if (data.error) {
            tableBody.innerHTML = `<tr><td colspan="12">${data.error}</td></tr>`;
            return;
        }

        data.forEach(log => {
            let formattedDate = new Date(log.scan_date).toLocaleDateString('en-US', { 
                weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' 
            });

            let row = `<tr>
                <td>${formattedDate}</td>
                <td>${log.check_in}</td>
                <td>${log.check_out ? log.check_out : '--'}</td>
                <td>${log.username}</td>
                <td><img src="../profiles/${log.profile_picture}" width="100px" height="100px" alt="Profile Picture"></td>
                <td>${log.first_name}</td>
                <td>${log.last_name}</td>
                <td>${log.reason}</td>
                <td>${log.email}</td>
                <td>${log.phone}</td>
                <td>${log.address}</td>
                <td>${log.birth_date}</td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    } catch (error) {
        console.error("Error fetching logs:", error);
    }
}

// Load logs on page load & refresh every 5 seconds
loadAdminLogs();
setInterval(loadAdminLogs, 5000);
