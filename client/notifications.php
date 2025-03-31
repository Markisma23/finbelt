<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Notifications</h2>
<div id="notificationArea">
    <p>Loading notifications...</p>
</div>

<script>
// Function to poll for notifications
function fetchNotifications() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'notifications_ajax.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                var notificationArea = document.getElementById('notificationArea');
                if (response.error) {
                    notificationArea.innerHTML = '<p>Error: ' + response.error + '</p>';
                } else {
                    notificationArea.innerHTML = '<p>' + response.notification + '</p>';
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
            }
        } else {
            console.error('Request failed. Returned status of ' + xhr.status);
        }
    };
    xhr.send();
}

// Initial fetch and then poll every 5 seconds
fetchNotifications();
setInterval(fetchNotifications, 5000);
</script>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
