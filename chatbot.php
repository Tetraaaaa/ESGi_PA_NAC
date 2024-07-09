<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot PCS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/chatbot.css">
    <style>
        .chatbot-container #chatbot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: url('image/chatbot.jpg') no-repeat center center;
            background-size: cover;
            cursor: pointer;
            z-index: 1000;
        }

        .chatbot-container #chatbox-container {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            display: none;
            flex-direction: column;
            z-index: 1000;
            border-radius: 10px;
        }

        .chatbot-container #chatbox {
            flex: 1;
            padding: 10px;
            overflow-y: scroll;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
        }

        .chatbot-container #userInput, .chatbot-container #language, .chatbot-container button {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            background-color: white;
            color: black;
        }

        .chatbot-container #userInput {
            border-radius: 0;
            border-top: 1px solid #ccc;
        }

        .chatbot-container .message {
            padding: 10px 15px;
            margin: 10px;
            border-radius: 20px;
            display: inline-block;
            max-width: 80%;
            word-wrap: break-word;
        }

        .chatbot-container .user {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            border-radius: 20px 20px 0 20px;
        }

        .chatbot-container .bot {
            background-color: #28a745;
            color: white;
            align-self: flex-start;
            border-radius: 20px 20px 20px 0;
        }

        .chatbot-container #chatbox-container header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .chatbot-container .separator {
            border-top: 1px solid #ccc;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="chatbot-container">
        <div id="chatbot-icon"></div>

        <div id="chatbox-container">
            <header>Chatbot PCS</header>
            <div id="chatbox"></div>
            <hr class="separator">
            <input type="text" id="userInput" placeholder="Posez votre question...">
            <hr class="separator">
            <select id="language">
                <option value="fr">Français</option>
                <option value="en">English</option>
            </select>
            <hr class="separator">
            <button onclick="sendMessage()">Envoyer</button>
        </div>
    </div>

    <script>
    document.getElementById("chatbot-icon").addEventListener("click", function() {
        var chatboxContainer = document.querySelector(".chatbot-container #chatbox-container");
        if (chatboxContainer.style.display === "none" || chatboxContainer.style.display === "") {
            chatboxContainer.style.display = "flex";
        } else {
            chatboxContainer.style.display = "none";
        }
    });

    function sendMessage() {
        var userInput = document.querySelector(".chatbot-container #userInput").value;
        var language = document.querySelector(".chatbot-container #language").value;
        if (userInput.trim() === "") return;

        var chatbox = document.querySelector(".chatbot-container #chatbox");
        var userMessageDiv = document.createElement("div");
        userMessageDiv.className = "message user";
        userMessageDiv.textContent = userInput;
        chatbox.appendChild(userMessageDiv);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "chatbot_back.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText).response;
                var botMessageDiv = document.createElement("div");
                botMessageDiv.className = "message bot";
                botMessageDiv.textContent = response;
                chatbox.appendChild(botMessageDiv);
                chatbox.scrollTop = chatbox.scrollHeight;
            } else {
                console.error("Erreur: ", xhr.status, xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error("Erreur de requête");
        };
        xhr.send("message=" + encodeURIComponent(userInput) + "&language=" + encodeURIComponent(language));

        document.querySelector(".chatbot-container #userInput").value = "";
    }
    </script>
</body>
</html>
