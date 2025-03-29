<?php

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    file_put_contents("user_logs.txt", "UserID: " . $userId . "\n", FILE_APPEND);
    echo "UserID received: " . htmlspecialchars($userId);
} else {
    echo "No UserID received!";
}
