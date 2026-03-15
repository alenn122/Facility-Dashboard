<?php
// Disable error reporting for clean output
error_reporting(0);

// 1. ESP32 Handshake (Auto-find)
if (isset($_GET['check_server'])) {
    header('Content-Type: text/plain');
    echo "RFID_SERVER_ACTIVE";
    exit;
}

// 2. ESP32 Sends Tag
if (isset($_POST['raw_rfid'])) {
    $tag = trim($_POST['raw_rfid']);
    if(!empty($tag)){
        file_put_contents('last_scan.txt', $tag);
    }
    echo "OK";
    exit;
}

// 3. Web Page Polls for Tag
if (isset($_GET['get_last_scan'])) {
    header('Content-Type: text/plain');
    if (file_exists('last_scan.txt')) {
        $data = trim(file_get_contents('last_scan.txt'));
        if(!empty($data)){
            echo $data;
            unlink('last_scan.txt'); // Delete after sending
        }
    }
    exit;
}
?>