<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScrapingDaninnController extends Controller
{
    public function scrapingDaninn()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://nacionalinn.letsbook.com.br/D/Reserva/ConsultaDisponibilidade?checkin=21%2F08%2F2023&checkout=22%2F08%2F2023&hotel=72&adultos=2&criancas=2,3');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: nacionalinn.letsbook.com.br';
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.6';
        $headers[] = 'Cookie: NACIONALINN-PMW-SEARCHES=[{"Chegada":"2023-08-21T00:00:00","Partida":"2023-08-22T00:00:00","CodigoHotel":"72","Adultos":"2","Criancas":"2,3"}]';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $crawler = new Crawler($result);


        $crawler->filter('table#tblAcomodacoes > tbody > tr')->each(function (Crawler $node, $i){
            dump($node->filter('div.flex-table-row > span')->text());
            dump("Máx. Adultos = ".$node->filter('div.adultos')->attr('data-n'));
            dump("Máx. Crianças = ".$node->filter('div.criancas')->attr('data-n'));
            $node->filter('table#tblCondicoes > tbody > tr')->each(function (Crawler $node2, $id){
                dump($node2->filter('li.nomeTarifa')->text());
                //dump(is_null($node2->filter('span.valorSemDesconto')->text()) ? :$node2->filter('span.valorSemDesconto')->text());
                //dump($node2->filter('span.precentualDesconto')->text());
                dump($node2->filter('span.valorFinal')->text());
            });
            dump('---------------------------------');
        });
    
    }
}
