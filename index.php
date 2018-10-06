<?php
/**
 * Front controller
 * Should be called by cron or smth
 */

require_once 'vendor/autoload.php';

use Darkie\AvitoTelegramBot\AvitoParser;
use TelegramBot\Api\BotApi;

/** @var string $url */
$url = 'https://www.avito.ru/moskva/kvartiry/sdam/na_dlitelnyy_srok?s=104&user=1&s_trg=4&metro=115-125&f=550_5703-5704';

/** @var AvitoParser $parser */
$parser = new AvitoParser($url);

/** @var array $oldHrefArray Parse last actual ads */
$oldHrefArray = file_exists('parsed.json') ? json_decode(file_get_contents('parsed.json'), true) : [];

/** @var BotApi $telegramBot */
$telegramBot = new BotApi('ACCESS_TOKEN');

/** @var int $chatId */
$chatId = 123;


/** @var array $actual */
$actual = $parser->getNewAds();

/** @var array $item */
foreach ($actual as $item) {
    if (!in_array($item['href'], $oldHrefArray)) {
        $message = "*НОВАЯ КВАРТИРА:*\n";
        $message .= "*$item[name]*\n\n";
        $message .= "*Дата:* $item[date]\n";
        $message .= "*Адрес:* $item[address]\n";
        $message .= "*Цена:* $item[price]\n";
        $message .= "[Ссылка здесь]($item[href])";
        $telegramBot->sendMessage($chatId, $message, 'markdown');
    }
}

/** @var array $actualHrefArray */
$actualHrefArray = array_map(function($item) {return $item['href'];}, $actual);

//update json with actual ads
file_put_contents('parsed.json', json_encode($actualHrefArray));