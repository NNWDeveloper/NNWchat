<?php
// Funkce pro načítání zpráv ze souboru data.json
function loadMessages() {
    $filename = 'data.json';

    if (!file_exists($filename)) {
        return [];
    }

    $data = file_get_contents($filename);
    return json_decode($data, true);
}

// Funkce pro uložení zpráv do souboru data.json
function saveMessages($messages) {
    $filename = 'data.json';
    file_put_contents($filename, json_encode($messages, JSON_PRETTY_PRINT));
}

// Funkce pro odstranění zprávy
function deleteMessage($messageId) {
    $messages = loadMessages();

    // Pokud existují zprávy a ID zprávy je platné, odstraníme ji
    if (isset($messages[$messageId])) {
        unset($messages[$messageId]);
        // Uložíme zpět upravené zprávy
        saveMessages(array_values($messages));  // array_values pro znovu indexování pole
        return true;
    }

    return false;
}

// Pokud je odeslán formulář pro smazání zprávy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && isset($_POST['message_id'])) {
    $password = $_POST['password'];
    $messageId = $_POST['message_id'];

    // Ověříme heslo
    if ($password === 'tajneheslo') {
        if (deleteMessage($messageId)) {
            echo "Zpráva byla úspěšně smazána.";
        } else {
            echo "Zpráva nebyla nalezena.";
        }
    } else {
        echo "Špatné heslo.";
    }
}

// Načteme zprávy pro výběr
$messages = loadMessages();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace chatu</title>
</head>
<body>
    <h1>Administrace chatu</h1>
    
    <form method="POST">
        <label for="message_id">Vyberte zprávu k odstranění:</label>
        <select name="message_id" id="message_id">
            <?php foreach ($messages as $index => $message): ?>
                <option value="<?= $index ?>"><?= htmlspecialchars($message['username']) ?>: <?= htmlspecialchars($message['message']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <br><br>
        
        <label for="password">Zadejte heslo:</label>
        <input type="password" name="password" id="password" required>
        
        <br><br>
        
        <button type="submit">Smazat zprávu</button>
    </form>

    <br><br>

    <a href="index.php">Zpět na hlavní stránku</a>
</body>
</html>
