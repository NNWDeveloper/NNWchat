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

// Funkce pro uložení zprávy do souboru data.json
function saveMessage($username, $message) {
    $filename = 'data.json';

    $messages = loadMessages();  // Načte existující zprávy

    // Přidá novou zprávu do pole
    $messages[] = [
        'username' => $username,
        'message' => $message,
        'timestamp' => time()
    ];

    // Uloží nové zprávy do souboru
    file_put_contents($filename, json_encode($messages, JSON_PRETTY_PRINT));
}

// Pokud je odeslána zpráva, uložíme ji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['message'])) {
    $username = htmlspecialchars($_POST['username']);
    $message = htmlspecialchars($_POST['message']);
    
    saveMessage($username, $message);
    
      // Přesměrování na index.php po uložení zprávy
    header("Location: index.php");
    exit;
}
?>


