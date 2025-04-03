<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Company Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="../includes/company/style/style.css">
    <style>
        body {
            font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .chat-container {
            display: flex;
            height: calc(100vh - 120px);
            margin-top: 120px;
            background: white;
            position: relative;
            z-index: 1;
        }

        .chat-sidebar {
            width: 300px;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
        }

        .chat-search {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
            background: #f8f9fa;
        }

        .chat-search input {
            width: 80%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .chat-search input:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.1);
        }

        .chat-search i {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 18px;
            pointer-events: none;
        }

        .chat-search input::placeholder {
            color: #999;
        }

        .chat-conversations {
            flex: 1;
            overflow-y: auto;
        }

        .conversation {
            display: flex;
            align-items: center;
            padding: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .conversation:hover {
            background-color: #f5f5f5;
        }

        .conversation.active {
            background-color: #f0f0f0;
        }

        .conversation img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .conversation-info {
            flex: 1;
        }

        .conversation-info h4 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .conversation-info p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }

        .conversation .time {
            font-size: 12px;
            color: #666;
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .chat-user-info {
            display: flex;
            align-items: center;
        }

        .chat-user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .chat-user-info h4 {
            margin: 0;
            color: #333;
        }

        .status {
            font-size: 12px;
            color: #4CAF50;
        }

        .chat-actions i {
            margin-left: 20px;
            font-size: 20px;
            color: #666;
            cursor: pointer;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column-reverse;
            gap: 15px;
            background: #f8f9fa;
        }

        .message {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            max-width: 70%;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.received {
            align-self: flex-start;
        }

        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .message-content {
            background: #f0f0f0;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
        }

        .message.sent .message-content {
            background: #6c63ff;
            color: white;
        }

        .message-content p {
            margin: 0;
        }

        .message-content .time {
            font-size: 10px;
            margin-top: 5px;
            display: block;
        }

        .chat-input {
            padding: 15px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            outline: none;
        }

        .chat-input i {
            font-size: 20px;
            color: #666;
            cursor: pointer;
        }

        .chat-input i.bx-send {
            color: #6c63ff;
        }

        @media (max-width: 768px) {
            .chat-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php include 'comp_navbar&tab.php'; ?>

    <!-- Chat Interface -->
    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="chat-search">
                <input type="text" placeholder="Search conversations...">
                <i class="bx bx-search"></i>
            </div>
            <div class="chat-conversations">
                <div class="conversation active">
                    <img src="../fortest/images/person_1.jpg" alt="User">
                    <div class="conversation-info">
                        <h4>John Doe</h4>
                        <p>Last message preview...</p>
                    </div>
                    <span class="time">2:30 PM</span>
                </div>
                <div class="conversation">
                    <img src="../fortest/images/person_2.jpg" alt="User">
                    <div class="conversation-info">
                        <h4>Jane Smith</h4>
                        <p>Last message preview...</p>
                    </div>
                    <span class="time">1:45 PM</span>
                </div>
                <div class="conversation">
                    <img src="../fortest/images/person_3.jpg" alt="User">
                    <div class="conversation-info">
                        <h4>Mike Johnson</h4>
                        <p>Last message preview...</p>
                    </div>
                    <span class="time">12:15 PM</span>
                </div>
            </div>
        </div>
        <div class="chat-main">
            <div class="chat-header">
                <div class="chat-user-info">
                    <img src="../fortest/images/person_1.jpg" alt="User">
                    <div>
                        <h4>John Doe</h4>
                        <span class="status">Online</span>
                    </div>
                </div>
                <div class="chat-actions">
                    <i class="bx bx-phone"></i>
                    <i class="bx bx-video"></i>
                </div>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message sent">
                    <div class="message-content">
                        <p>Hi John! Thanks for your interest. Would you like to schedule an interview?</p>
                        <span class="time">2:31 PM</span>
                    </div>
                </div>
                <div class="message received">
                    <img src="../fortest/images/person_1.jpg" alt="User">
                    <div class="message-content">
                        <p>Hello! I'm interested in the Software Engineer position.</p>
                        <span class="time">2:30 PM</span>
                    </div>
                </div>
            </div>
            <div class="chat-input">
                <i class="bx bx-paperclip"></i>
                <input type="text" placeholder="Type a message...">
                <i class="bx bx-smile"></i>
                <i class="bx bx-send"></i>
            </div>
        </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            document.getElementById('currentTime').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Auto scroll to bottom of chat
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Scroll to bottom when page loads
        window.addEventListener('load', scrollToBottom);

        // Scroll to bottom when new message is added
        const chatInput = document.querySelector('.chat-input input');
        const sendButton = document.querySelector('.chat-input i.bx-send');

        function addNewMessage() {
            const message = chatInput.value.trim();
            if (message) {
                const chatMessages = document.getElementById('chatMessages');
                const now = new Date();
                const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                const newMessage = document.createElement('div');
                newMessage.className = 'message sent';
                newMessage.innerHTML = `
                    <div class="message-content">
                        <p>${message}</p>
                        <span class="time">${timeString}</span>
                    </div>
                `;
                
                chatMessages.insertBefore(newMessage, chatMessages.firstChild);
                chatInput.value = '';
                scrollToBottom();
            }
        }

        sendButton.addEventListener('click', addNewMessage);
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addNewMessage();
            }
        });
    </script>
</body>
</html>


