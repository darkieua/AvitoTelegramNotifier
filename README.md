# Avito.ru Telegram Notifier
Simple Avito.ru notifier for Telegram Messenger. Used to search for apartments in Moscow (internal use).

## Install dependencies

Via Composer
Projects uses [DomCrawler](https://github.com/symfony/dom-crawler) and [CssSelector](https://github.com/symfony/css-selector) components from Symfony and [Telegram Bot API PHP wrapper](https://github.com/TelegramBot/Api)
``` bash
$ composer install
```

## Usage
All configuration is performed in index.php.
1. Change telegram access token to your's in: 
``` php
$telegramBot = new BotApi('ACCESS_TOKEN');
```

2. Change chat id used to send notifications:
``` php
$chatId = 123;
```

3. Set avito.ru page with ads (parses only ads for today on one page):
``` php
$url = 'https://www.avito.ru/moskva/kvartiry/sdam/na_dlitelnyy_srok?s=104&user=1&s_trg=4&metro=115-125&f=550_5703-5704';
```
4. (Optional) Set crontab rules
``` bash
*/5 * * * * /usr/bin/wget -t 1 -O - http://yoursite.com/avito_bot/index.php >/dev/null 2>&1
```
