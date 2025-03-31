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

// For this example, we assume the support user has ID 1.
$supportUserId = 1;
?>
<h2>Support Chat</h2>
<div id="chatWindow" style="border:1px solid #ccc; height:300px; overflow-y:scroll; padding:10px;">
    <p>Loading chat messages...</p>
</div>
<form id="chatForm">
    <input type="hidden" id="recipient_id" value="<?php echo $supportUserId; ?>">
    <textarea id="message" placeholder="Type your message here..." required style="width:100%;"></textarea>
    <button type="submit">Send</button>
</form>

<script>
// Function to fetch chat messages
function fetchChatMessages() {
    var xhr = new XMLHttpRequest();
    // Replace with the correct path to the API endpoint
    xhr.open('GET', 'chat.php?with=' + document.getElementById('recipient_id').value, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                var chatWindow = document.getElementById('chatWindow');
                if (response.status === 'success') {
                    // Clear and re-populate chat window
                    chatWindow.innerHTML = '';
                    response.data.forEach(function(msg) {
                        var p = document.createElement('p');
                        p.innerHTML = '<strong>' + (msg.sender_id == <?php echo $user->id; ?> ? 'You' : 'Support') + ':</strong> ' + msg.message;
                        chatWindow.appendChild(p);
                    });
                    chatWindow.scrollTop = chatWindow.scrollHeight;
                } else {
                    chatWindow.innerHTML = '<p>Error loading messages</p>';
                }
            } catch (e) {
                console.error('Error parsing response', e);
            }
        }
    };
    xhr.send();
}

// Poll for new messages every 5 seconds
setInterval(fetchChatMessages, 5000);
fetchChatMessages();

// Handle form submission to send message
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var recipientId = document.getElementById('recipient_id').value;
    var message = document.getElementById('message').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Clear the message field and reload chat
            document.getElementById('message').value = '';
            fetchChatMessages();
        } else {
            alert('Error sending message');
        }
    };
    xhr.send(JSON.stringify({
        recipient_id: recipientId,
        message: message
    }));
});
</script>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
