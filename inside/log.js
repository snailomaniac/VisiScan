function loadLogs() {
    fetch("fetch_logs.php")
    .then(response => response.json())
    .then(data => {
        let tableBody = document.getElementById("logTable");
        tableBody.innerHTML = "";
        data.forEach(log => {
            let formattedDate = new Date(log.scan_date).toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });

            let row = `<tr>
                <td>${log.username}</td>
                <td>${formattedDate}</td>
                <td>${log.check_in}</td>
                <td>${log.check_out ? log.check_out : '--'}</td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    })
    .catch(error => console.error("Error fetching logs:", error));
}

loadLogs();
setInterval(loadLogs, 5000);