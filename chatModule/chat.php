

<?php
require '../additionals/_database.php';
session_start();
session_regenerate_id();

$bookID = $_GET['bookId'];
if(!isset($bookID)){
  header("Location:index.html");
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Book Chat</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./chat.css">
  <link rel="stylesheet" href="../style.css">

  
</head>
<body class="gradient-background-2">
  <div class="container mt-5">
    
  <?php
    echo '<h1>' .$bookID. ' book chat</h1>';
    ?>

    <div id="chat-messages" class="mb-3 bg-dark text-dark container">
      <!-- Chat messages will be displayed here -->
      <div id="message-container" class="message-container"></div>
    </div>
    <form id="chat-form">
      <div class="input-group mb-3">
        <input type="text" id="message-input" class="form-control" placeholder="Type your message" autocomplete="off">
        <input type="hidden" id="current-user" value="<?php echo $_SESSION['user_id']?>">
        <input type="hidden" id="book-id" value="<?php echo $bookID ?>">
        <input type="hidden" id="reply-message-id" value="">
        <button type="submit" class="btn btn-primary">Send</button>

      </div>
    </form>
    <button  class="btn btn-primary back">back</button>
  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Load chat messages on page load
      loadChatMessages();

      // Submit chat message
      $('#chat-form').submit(function(e) {
        e.preventDefault();
        var message = $('#message-input').val().trim();
        var currentUser = $('#current-user').val();
        var bookId = $('#book-id').val();
        var replyMessageId = $('#reply-message-id').val();
        if (message !== '') {
          $.post('../chatModule/send_message.php', { message, currentUser, bookId, replyMessageId }, function(data) {
            // Clear input field
            $('#message-input').val('');
            // Reset reply message ID
            $('#reply-message-id').val('');
            // Reload chat messages
            loadChatMessages();
          });
        }
      });

      // Handle reply button click
      $(document).on('click', '.receiver .reply-button', function() {
        var messageContainer = $(this).closest('.message');
        var replyMessageId = messageContainer.data('message-id');
        var senderName = messageContainer.find('.sender-name').text();
        $('#message-input').val('');
        $('#reply-message-id').val(replyMessageId);
      });

      // Handle delete button click
      $(document).on('click', '.sender .delete-button', function() {
        var messageContainer = $(this).closest('.message');
        var messageId = messageContainer.data('message-id');
     
        deleteChatMessage(messageId);
      });

      // Handle message click to show/hide reply button
      $(document).on('click', '.message', function() {
        var replyButton = $(this).find('.reply-button');
        replyButton.toggle();
      });

      // Load chat messages using AJAX
      function loadChatMessages() {
        $.get('../chatModule/get_messages.php', { currentUser: $('#current-user').val(), bookId: $('#book-id').val() }, function(data) {
          
          var messages = JSON.parse(data);
          var messageContainer = $('#message-container');
          // Clear previous messages
          messageContainer.empty();
          // Display chat messages
          messages.forEach(function(message) {
            var messageId = message.id;
            var sender = message.sender;
            var senderId = message.senderId;
            var text = message.text;
            var timestamp = message.timestamp;
            var replyTo = message.replyTo;
            var userId = '<?php echo $_SESSION['email']; ?>';
            var senderName = $('<span class="sender-name"></span>').text(sender);
            var messageText = $('<div></div>').text(text); 
            var timestampElement = $('<div class="timestamp"></div>').text(timestamp);

            var chatMessage = $('<div class="message"></div>')
              .data('message-id', messageId)
              .append(senderName, '<br>', messageText, ' ', timestampElement);

            if (senderId ===userId) {// $('#current-user').val()
              chatMessage.addClass('sender');
              var deleteButton = $('<span class="delete-button">Delete</span>').hide();
              chatMessage.append(' ', deleteButton);
            } else {
              chatMessage.addClass('receiver');
              var replyButton = $('<span class="reply-button btn btn-outline-primary">Reply</span>').hide();
              chatMessage.append(' ', replyButton);
            }

            // Check if the message is a reply and attach it to the corresponding receiver's message
            if (replyTo) {
              var replyMessage = $('<div class="reply-message"></div>').text(replyTo);
              chatMessage.append(replyMessage);
            }

            messageContainer.append(chatMessage);
          });
        });
      }

      $(document).on('click', '.sender', function() {
  var messageContainer = $(this).closest('.message');
  var messageId = messageContainer.data('message-id');
  var popoverContent = $('<span class="delete-button btn btn-danger">Delete</span>');
  
  // Initialize popover
  messageContainer.popover({
    content: popoverContent,
    html: true,
    trigger: 'manual',
    placement: 'top'
  }).popover('show');
  
  // Handle delete button click
  $(document).on('click', '.delete-button', function() {
    deleteChatMessage(messageId);
    messageContainer.popover('dispose');
  });
});
$(document).on('click', function(event) {
  if (!$(event.target).closest('.message').length) {
    $('.message').popover('hide').popover('dispose');
  }
});


// Function to delete a chat message
function deleteChatMessage(messageId) {
  $.post('./delete_message.php', { messageId }, function(data) {
    // Reload chat messages
    loadChatMessages();
  });
}

    });


    $(document).on('click',' .back',function(){
      window.location='../customer_order.php';
    });
  </script>
</body>
</html>
