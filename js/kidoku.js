// Function to send the latest read message ID to the server
function postLastReadMessageId(lastMessageId) {
    fetch('kidoku.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `lastMessageId=${lastMessageId}`
    })
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    // Example: last read message ID, usually retrieved from local storage or a similar method
    const lastMessageId = localStorage.getItem('lastReadMessageId') || '0';
    postLastReadMessageId(lastMessageId);
  });
  