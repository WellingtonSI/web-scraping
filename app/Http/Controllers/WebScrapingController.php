<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Weidner\Goutte\GoutteFacade;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Illuminate\Support\Facades\Http;


class WebScrapingController extends Controller
{

    public function webScraping()
    {
        $client =  new Client();
        $result = $client->get('https://www.hoteis.com/Hotel-Search?destination=Opaba%20Praia%20Hotel&regionId=&latLong=-14.816838,-39.025248&selected=3957818&d1=2023-05-24&startDate=2023-05-24&d2=2023-05-25&endDate=2023-05-25&adults=2&rooms=1');

        $html = $result->getBody()->getContents();

        $crawler = new Crawler($html);


        $link = $crawler->filter('#app-layer-base > div > main > div > div > div > div > div.uitk-layout-flex-item.uitk-layout-flex-item-flex-grow-1 > section:nth-child(2) > div > div.uitk-spacing.search-results-listing.uitk-spacing-padding-small-block-three.uitk-spacing-padding-medium-blockstart-one.uitk-spacing-padding-large-blockstart-three > div > div:nth-child(2) > div:nth-child(3) > div:nth-child(2) > div:nth-child(2) > div > a')->attr('href');


        $result = $client->get("https://www.hoteis.com" . $link);

        $html = $result->getBody()->getContents();

        $crawler = new Crawler($html);


        $values = $crawler->filter('#Offers > div:nth-child(3) > div > div.uitk-layout-grid-item')->each(function (Crawler $node, $i) {
            dump($i);
        });

        dump($crawler->filter('#Offers > div:nth-child(3) > div > div.uitk-layout-grid-item > div > div:nth-child(2) > div:nth-child(2) > div > h3')->text());

        //dump('oi');
    }
}
