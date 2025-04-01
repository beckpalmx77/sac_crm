<?php
$channelAccessToken = 'ahPczYvQaG4SKGoevcwsEEcsoze8tj0WdLxt+o8fnWniQSUQklq00dCZEccKUQq28OuKwlxVRY3H4twE0iMn424iXILBebwm0QMPw8TqmGr/RlB+9XlJloH539o0qiBsT9UszcbVN/AhOwIc3RJFjwdB04t89/1O/w1cDnyilFU=';
$userId = 'U830227bbb81b38b02922d927707b9677';

$url = 'https://api.line.me/v2/bot/message/push';
$messageData = [
    'to' => $userId,
    'messages' => [
        ['type' => 'text', 'text' => 'ทดสอบส่งข้อความจาก LINE API']
    ]
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $channelAccessToken
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: " . $httpCode . "\n\r";
if ($httpCode == 200) {  // แก้จาก = เป็น ==
    echo "Response: " . $response . "\n\r";
} else {
    echo "cURL Error: " . $error . "\n\r";
}