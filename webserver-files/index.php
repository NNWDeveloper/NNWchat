<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NNWchat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .chat-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .messages {
            max-height: 300px;
            overflow-y: scroll;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .message {
            margin-bottom: 10px;
        }
        .username {
            font-weight: bold;
        }
        .message-text {
            margin-left: 10px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <h2>Chat</h2>

    <div class="messages">
        <?php
        include('chat.php');  // Načte PHP soubor, který obsluhuje zprávy

        // Načte všechny zprávy
        $messages = loadMessages();
        
        foreach ($messages as $message):
        ?>
            <div class="message">
                <span class="username"><?= htmlspecialchars($message['username']) ?>:</span>
                <span class="message-text"><?= htmlspecialchars($message['message']) ?></span>
                <br>
                <small><?= date('H:i:s', $message['timestamp']) ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="chat.php" method="POST">
        <input type="text" name="username" placeholder="Tvoje jméno" required><br>
        <textarea name="message" placeholder="Napiš zprávu..." required></textarea><br>
        <button type="submit">Odeslat zprávu</button>    <a href="administrace.php">administrace</a>
    </form>
</div>

</body>
</html>
