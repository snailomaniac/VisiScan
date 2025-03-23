function loadLogs() {
    fetch("logfetch.php")
    .then(response => response.json())
    .then(data => {
        console.log("Fetched Logs:", data); // Debugging

        let tableBody = document.getElementById("logTable");
        if (!tableBody) {
            console.error("Error: Table body not found!");
            return;
        }

        tableBody.innerHTML = "";

        data.forEach(log => {
            let formattedDate = new Date(log.scan_date).toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });

            let row = `<tr>
                <td>${formattedDate}</td>
                <td>${log.check_in}</td>
                <td>${log.check_out ?? "--"}</td>
                <td>${log.reason ?? "--"}</td>
            </tr>`;
            
            tableBody.innerHTML += row;
        });
    })
    .catch(error => console.error("Error fetching logs:", error));
}

loadLogs();
