# Telegram WHOIS Bot

A simple Telegram bot that provides **WHOIS information** for any IPv4/IPv6 address, including the **country flag**. Works via **Webhook** and also has a **CLI test mode**.

---

## Features

* Responds to `/start` and `/help` commands.
* Fetches WHOIS info using [Tizian Maxime Weigt's API](https://github.com/Tizian-Maxime-Weigt/IP-to-ASN-and-Whois-API).
* Displays **country flag** next to the country code.
* Works in **Webhook mode** for Telegram.
* **CLI test mode** for quick local testing.
* Ignores unnecessary fields like `logo` from the API.

---

## Installation

1. Clone or download this repository.
2. Upload `ipwhois_webhook.php` to your web server (HTTPS required).
3. Update your Telegram Bot Token in the script:

```php
$bot_token = "YOUR_TELEGRAM_BOT_TOKEN";
```

---

## Setting up the Webhook

Set the Webhook URL for your bot:

```bash
curl "https://api.telegram.org/botYOUR_TELEGRAM_BOT_TOKEN/setWebhook?url=https://yourdomain.com/ipwhois_webhook.php"
```

Telegram will now send all messages to this script automatically.

---

## Usage

### Telegram

* Open your bot in Telegram.
* Send `/start` → receives a welcome message.
* Send an IP address → receives WHOIS information with the country flag.
* Invalid input → receives an error message.

Example:

```
WHOIS Lookup
Ip: 8.8.8.8
Asn: 15169
Country: US 🇺🇸
Description: Google
```

---

### CLI (for testing)

Run the script locally with an IP argument:

```bash
php ipwhois_webhook.php 8.8.8.8
```

Output:

```
WHOIS Lookup
Ip: 8.8.8.8
Asn: 15169
Country: US 🇺🇸
Description: Google
```

---

## Credits

* This project uses the **IP-to-ASN and WHOIS API** by [Tizian Maxime Weigt](https://github.com/Tizian-Maxime-Weigt/IP-to-ASN-and-Whois-API).
* Thanks to the author for providing this free API.

---

## Notes

* Ensure your server uses **HTTPS**. Telegram requires a valid SSL certificate.
* Keep the script short and free from additional `echo` statements when using Webhook mode.
* Country flags are automatically generated from ISO 2-letter codes.

---

## License

This project is **open source** and available under the MIT License.
