<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $message = htmlspecialchars($_POST['message']);

    if (!$username || !$message) {
        echo json_encode(["status" => "error", "message" => "Neplatné vstupní údaje."]);
        exit;
    }

    $dataFile = 'data.json';
    $data = [];

    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true) ?: [];
    }

    $data[] = ["username" => $username, "message" => $message, "timestamp" => time()];

    if (file_put_contents($dataFile, json_encode($data))) {
        echo json_encode(["status" => "success", "message" => "Zpráva byla uložena."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Nepodařilo se uložit zprávu."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Nepovolená metoda."]);
}
?>
