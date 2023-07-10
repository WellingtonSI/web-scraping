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

        curl_setopt($ch, CURLOPT_URL, 'https://nacionalinn.letsbook.com.br/D/Reserva/ConsultaDisponibilidade?checkin=10%2F07%2F2023&checkout=11%2F07%2F2023&cidade=AQA&hotel=&adultos=1&criancas=&destino=Araraquara&promocode=&tarifa=&mesCalendario=&nomeUsuarioAbandono=');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: nacionalinn.letsbook.com.br';
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.6';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Cookie: NACIONALINN-PMW-GUID=e6f3fce5-e4a0-44e8-bd6e-bb6867103eaf-742042746; PMWEB-PROMO=224097180167061043171073040036055016077124056015; NACIONALINN-PMW-UTM-SOURCE=campaign-google; NACIONALINN-PMW-UTM-MEDIUM=cpc; NACIONALINN-PMW-UTM-CAMPAIGN=dan-pocos-ferias-magicas-2023; NACIONALINN-PMW-CART-ID=070235196142248158074250034255083178063224223241; NACIONALINN-PMW-HEADER=https://letsimage.s3.sa-east-1.amazonaws.com/letsbook/256/header.png; ASP.NET_SessionId=ilom5crn5dwbhgnoq2t1nc3c; __RequestVerificationToken_L0Q1=euVsKait6vEi7Kali06Vt2Auw24qbcDJTJ6zzk9c3WfES98egnHhDZDku31GJq9Yu9a2vehbwOog5AeE3_TQa4DQvPSiJQL9p3jiiA4jNRk1; NACIONALINN-PMW-SEARCHES=%5b%7b%22Chegada%22%3a%222023-07-10T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-11T00%3a00%3a00%22%2c%22CodigoHotel%22%3anull%2c%22CodigoCidade%22%3a%22AQA%22%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Araraquara%22%2c%22DataBusca%22%3a%222023-07-10T10%3a46%3a44.4704828-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Araraquara%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%2c%7b%22Chegada%22%3a%222023-07-27T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-28T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2291%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Dan+Inn+Uberlandia%22%2c%22DataBusca%22%3a%222023-07-10T10%3a17%3a26.0193945-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Uberl%c3%a2ndia%22%2c%22Estado%22%3a%22Minas+Gerais%22%7d%2c%7b%22Chegada%22%3a%222023-07-27T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-28T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2257%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Nacional+Inn+Limeira%22%2c%22DataBusca%22%3a%222023-07-10T10%3a15%3a17.9051273-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Limeira%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%2c%7b%22Chegada%22%3a%222023-07-10T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-11T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2219%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Euro+Suite+Campos+do+Jord%c3%a3o%22%2c%22DataBusca%22%3a%222023-07-10T10%3a07%3a14.0172673-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Campos+Do+Jordao%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%2c%7b%22Chegada%22%3a%222023-07-10T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-11T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2240%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Dan+Inn+Campinas+Anhanguera%22%2c%22DataBusca%22%3a%222023-07-10T09%3a16%3a29.8935836-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Campinas%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%2c%7b%22Chegada%22%3a%222023-07-10T00%3a00%3a00%22%2c%22Partida%22%3a%222023-07-11T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2278%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Nacional+Inn+Arax%c3%a1+Previd%c3%aancia%22%2c%22DataBusca%22%3a%222023-07-10T09%3a15%3a38.0278789-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Arax%c3%a1%22%2c%22Estado%22%3a%22Minas+Gerais%22%7d%2c%7b%22Chegada%22%3a%222023-08-21T00%3a00%3a00%22%2c%22Partida%22%3a%222023-08-22T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2229%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Dan+Inn+Araraquara%22%2c%22DataBusca%22%3a%222023-07-07T14%3a12%3a49.9026255-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Araraquara%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%2c%7b%22Chegada%22%3a%222023-08-21T00%3a00%3a00%22%2c%22Partida%22%3a%222023-08-22T00%3a00%3a00%22%2c%22CodigoHotel%22%3a%2291%22%2c%22CodigoCidade%22%3anull%2c%22Adultos%22%3a%221%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Dan+Inn+Uberlandia%22%2c%22DataBusca%22%3a%222023-07-07T14%3a10%3a50.0919098-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Uberl%c3%a2ndia%22%2c%22Estado%22%3a%22Minas+Gerais%22%7d%2c%7b%22Chegada%22%3a%222023-08-21T00%3a00%3a00%22%2c%22Partida%22%3a%222023-08-22T00%3a00%3a00%22%2c%22CodigoHotel%22%3anull%2c%22CodigoCidade%22%3a%22AQA%22%2c%22Adultos%22%3a%222%22%2c%22Criancas%22%3a%22%22%2c%22Destino%22%3a%22Araraquara%22%2c%22DataBusca%22%3a%222023-07-07T13%3a31%3a27.7240074-03%3a00%22%2c%22Promocode%22%3a%22%22%2c%22CodigoTarifa%22%3anull%2c%22Cidade%22%3a%22Araraquara%22%2c%22Estado%22%3a%22Sao+Paulo%22%7d%5d';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://nacionalinn.letsbook.com.br/D/Reserva?checkin=10%2F07%2F2023&checkout=11%2F07%2F2023&cidade=AQA&hotel=&adultos=1&criancas=&destino=Araraquara&promocode=&tarifa=&mesCalendario=';
        $headers[] = 'Sec-Ch-Ua: \"Not.A/Brand\";v=\"8\", \"Chromium\";v=\"114\", \"Brave\";v=\"114\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
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
                dump($node2->filter('span.valorSemDesconto')->text());
                dump($node2->filter('span.precentualDesconto')->text());
                dump($node2->filter('span.valorFinal')->text());
            });
            dump('---------------------------------');
        });
    
    }
}
