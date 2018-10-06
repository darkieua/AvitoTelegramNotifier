<?php

/**
 * Created by PhpStorm.
 * User: Darkie
 * Date: 06.10.2018
 * Time: 18:02
 */

namespace Darkie\AvitoTelegramBot;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class AvitoParser
{
    /** @var string */
    private $url;

    /**
     * AvitoParser constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getNewAds() {
        $html = file_get_contents($this->url);
        $crawler = new Crawler($html);
        $items = $crawler->filter('div.item');

        $addsArray = [];
        /** @var DOMElement $item */
        foreach ($items as $item) {
            $parsed = $this->parseItem(new Crawler($item));
            if ($parsed !== null) {
                $addsArray[] = $parsed;
            }
        }
        return $addsArray;
    }

    /**
     * @param Crawler $item
     * @return array|null
     */
    private function parseItem(Crawler $item) {
        $date = $item->filter('.js-item-date')->attr('data-absolute-date');
        if (strpos($date, 'Сегодня') !== false) {
            $link = $item->filter('.item-description-title-link');
            $price = $item->filter('.about_bold-price');
            $address = $item->filter('p.address');

            $result = [];
            $result['date'] = trim($date);
            $result['name'] = trim($link->text());
            $result['href'] = 'https://www.avito.ru' . trim($link->attr('href'));
            $result['price'] = trim(str_replace(['₽', 'в месяц'], ['Р',''], $price->text()));
            $result['address'] = trim($address->text());
            return $result;
        }
        return null;
    }

}