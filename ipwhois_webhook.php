<?php
/**
 * Telegram Webhook + WHOIS + Country Flag
 * Works on both Telegram and CLI (for testing)
 */

$bot_token = "YOUR_TELEGRAM_BOT_TOKEN";

// ----------------- CLI Mode -----------------
if (php_sapi_name() === 'cli') {
    echo "Telegram WHOIS Bot Test Mode\n";
    echo "Usage: php ipwhois_webhook.php <IP>\n\n";

    global $argv;
    if (!isset($argv[1])) exit("Please provide an IP address.\n");

    $ip = $argv[1];
    if (!filter_var($ip, FILTER_VALIDATE_IP)) exit("Invalid IP address.\n");

    echo getWhois($ip) . "\n";
    exit;
}

// ----------------- Webhook Mode -----------------
$update = json_decode(file_get_contents('php://input'), true);
if (!$update) exit;

$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? "");

if (!$chat_id || !$text) exit;

if (in_array($text, ['/start','/help'])) {
    $reply = "Welcome! Send a valid IP address and I will return WHOIS info with country flag.";
} elseif (filter_var($text, FILTER_VALIDATE_IP)) {
    $reply = getWhois($text);
} else {
    $reply = "❌ Invalid input. Send a valid IP address.";
}

sendMessage($chat_id, $reply);

// ---------------- Functions -----------------
function getWhois($ip) {
    $api_url = "https://cdn.t-w.dev/whois?ip=" . urlencode($ip);
    $ch = curl_init($api_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT => 5
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response,true);
    if (!$data) return "⚠️ Error fetching WHOIS";

    $output = "WHOIS Lookup\n";
    foreach ($data as $key => $value) {
        if (empty($value)) continue;
        if ($key === 'logo' || $key === 'country_name') continue;

        if ($key === 'country') {
            $flag = countryCodeToFlag($value);
            $value .= " $flag";
        }

        $output .= ucfirst($key) . ": $value\n";
    }
    return $output;
}

function countryCodeToFlag($code) {
    $code = strtoupper($code);
    $flag = '';
    if (strlen($code) === 2) {
        $flag .= mb_chr(127397 + ord($code[0]));
        $flag .= mb_chr(127397 + ord($code[1]));
    }
    return $flag;
}

function sendMessage($chat_id, $text){
    global $bot_token;
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    $post = [
        "chat_id" => $chat_id,
        "text" => $text
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_RETURNTRANSFER => true
    ]);
    curl_exec($ch);
    curl_close($ch);
}
