<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class PacotesDecolarController extends Controller
{
    public function pacotesDecolar()
    {
        $origin = 'CIT_6574';
        $destiny = 'CIT_4430';
        $date_start = '2023-12-14';
        $date_end = '2023-12-16';
        $adults = 2;
        $childrens = 0;
        $result = self::mountCurlTakeUrlFirstStep($origin, $destiny, $date_start,$date_end,$adults,$childrens);

        $data_hotels = [];
        $url = json_decode($result,true)['url'];
        $pc_id = '';
        $current_step_encoded_url  = base64_encode("https://www.decolar.com/".$url);
    
        if (preg_match('/\/results\/(.*?)\?/', $url, $matches)) {
            $pc_id = $matches[1];
           
        } else {
            return "Nenhum pc_id encontrado.";
        }
    
        $result = self::mountCurlTakeHtml($url);

        if (preg_match('/<script id="s-accommodations-state" type="application\/json">(.*?)<\/script>/', $result, $matches)) {
            $json = str_replace('&q;', '"', $matches[1]);
           
        } else {
            return "Nenhum json encontrado.";
        }

        $crawler = new Crawler($result);

        $result = json_decode($json, true);

        if(isset($result['errorInfo'])){
            return "Key ['errorInfo'] não retornada.";
        }

        if(!is_null($result['errorInfo'])){
            return "Error algo pegar os valores do hotel escolhido pela decolar.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues'] não retornada.";
        }

        if(!is_array($result['packageSelectedAccommodationByCurrencyValues'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues'] não é um array.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL'] não retornada.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation'] não retornada.";
        }

        if(empty($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation'] está vazio.";
        }
        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['name'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['name'] não retornada.";
        }
        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['location'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['location'] não retornada.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['location']['city_id'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['location']['city_id'] não retornada.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices'] não retornada.";
        }
        if(empty($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices'] está vazio.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown'] não retornada.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model'] não retornada.";
        }

        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['header'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['header'] não retornada.";
        }
        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['body'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['body'] não retornada.";
        }
        if(!isset($result['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['footer'])){
            return "Key ['packageSelectedAccommodationByCurrencyValues']['BRL']['prices']['typed_breakdown']['model']['footer'] não retornada.";
        }


        if($result['packageSelectedAccommodationByCurrencyValues']['BRL']['accommodation']['kind'] === "HOTEL")
            array_push($data_hotels,self::getRateHotel($result['packageSelectedAccommodationByCurrencyValues']['BRL']));

        //dd($data_hotels);

        $script = $crawler->filter('body > script')->text();
        $search_id = '';
        $x_hash = '';
        $page_view_id ='';
        $abvc = '';
        $trip_item ='';
        $search_params ='';
        $flow = '';
       
        // função preg_match para encontrar a correspondência
        if (preg_match('/"search-id", "(.*?)"\);/', $script, $matches)) {
            $search_id = $matches[1];
        
        } else {
            return "Nenhum search_id encontrado.";
        }
        if (preg_match('/requestUrl = "(.*?)";/', $script, $matches)) {

            $url = $matches[1];
           
        } else {
            return "Nenhum requestUrl encontrado.";
        }
        if (preg_match('/"x-hash", "(.*?)"\);/', $script, $matches)) {

            $x_hash = $matches[1];
           
        } else {
            return "Nenhum x-hash encontrado.";
        }

        if (preg_match('/"page-view-id", "(.*?)"\);/', $script, $matches)) {

            $page_view_id = $matches[1];
           
        } else {
            return "Nenhum page_view_id encontrado.";
        }

        if (preg_match('/abcv=(.*?)&/', $script, $matches)) {

            $abvc = $matches[1];
           
        } else {
            return "Nenhum abvc encontrado.";
        }

        // if (preg_match('/&trip_item=(.*?)&/', $url , $matches)) {

        //     $trip_item = $matches[1];
           
        // } else {
            
        //     dd("Nenhum trip_item solicitado encontrado.");
          
        // }

        // if (preg_match('/\?searchParams=(.*?)&/', $url , $matches)) {

        //     $search_params = $matches[1];
           
        // } else {
        //     dd("Nenhum searchParams solicitado encontrado.");
          
        // }

        // if (preg_match('/&flow=(.*?)&/', $url , $matches)) {

        //     $flow = $matches[1];
           
        // } else {
        //     dd("Nenhum flow solicitado encontrado.");
          
        // }
        //dump($page_view_id,$search_params,$trip_item,$search_id,$abvc,$x_hash,$flow);

        //hotel escolhido pela decolar e informações do voo
        // $result = json_decode(self::mountCurlFlightAccommodation($page_view_id,$trip_item,$search_params,$search_id,$abvc,$x_hash,$flow,$pc_id),true);

        // if(!is_array($result['items'])){
        //     return "Key items não é um array.";
        // }
        // if(!isset($result['items'])){
        //     return "Key items não retornada.";
        // }

        // if(!isset($result['airlines'])){
        //     return "Key airlines não retornada.";
        // }

        // if(!is_array($result['airlines'])){
        //     return "Key airlines não é um array.";
        // }

        // $position = array_search('HOTEL', array_column($result['items'], 'type'));

        // if(!$position){
        //     return "Array de HOTEL não retornado.";
        // }

        // if(!isset($result['items'][$position]['offer'])){
        //     return "Key [items][$position][offer] não retornada.";
        // }
        // if(!isset($result['items'][$position]['summary'])){
        //     return "Key [items][$position][summary] não retornada.";
        // }
        // if(!isset($result['items'][$position]['summary']['name'])){
        //     return "Key ['items'][$position]['summary']['name'] não retornada.";
        // }
    
        // $airlines = array_keys($result['airlines']);

        // if(empty($airlines)){
        //     return "Array airlines está vazio.";
        // }

        // if(!isset($result['airlines'][$airlines[0]]['name'])){
        //     return "Key ['airlines'][".$airlines[0]."]['name'] não retornada.";
        // }

        // $airlines_name = $result['airlines'][$airlines[0]]['name'];


        // dd($result['airlines'][$airlines[0]][]);
        // array_push($data_hotels,[
        //     "id" => $result['items'][$position]['offer'],
        //     "name" => $result['items'][$position]['summary']['name']
        // ]);
        //dump($url);


        //etapa para pegar nomes,id e valores dos hotéis de sugestão dos hotéis da cidade de destino
        $ratesPacotes = self::mountCurlTakeRates($url,$search_id,$x_hash,$page_view_id);
        //dd($ratesPacotes);

        if(!is_array($ratesPacotes['availability'])){
            return "Key prices não é um array.";
        }
        if(!isset($ratesPacotes['availability'])){
            return "Key availability não retornada.";
        }


        $page=1;
        do{
            foreach($ratesPacotes['availability'] as $hotel){

                if(!isset($hotel['accommodation'])){
                    return "Key [availability][accommodation] não retornada.";
                }

                if(!isset($hotel['prices'])){
                    return "Key [availability][prices] não retornada.";
                }
        
                if(!isset($hotel['accommodation']['kind'])){
                    return "Key [availability][kind] não retornada.";
                }

                if(!isset($hotel['accommodation']['name'])){
                    return "Key [availability][name] não retornada.";
                }
                

                if($hotel['accommodation']['kind'] === "HOTEL"){
                    array_push($data_hotels,self::getRateHotel($hotel));
                    // $hasDiscount=false;
                    // $amount=null;
                    // $tax_amount=null;
                    // $amount_with_discount=null;
                    // $discount_percentage=null;
                    // $discount = null;
                    // $flight_value=null;
                    // $accommodation_value=null;
    
                    // //valores do hotel
                    // if(!isset($accommodation['prices'])){
                    //     return "Key prices não retornada.";
                    // }
                    // if(!is_array($accommodation['prices'])){
                    //     return "Key prices não é um array.";
                    // }
                    // if(!isset($accommodation['prices']['typed_breakdown'])){
                    //     return "Key typed_breakdown não retornada.";
                    // }
                    // if(!isset($accommodation['prices']['typed_breakdown']['model'])){
                    //     return "Key prices[typed_breakdown]['model'] não retornada.";
                    // }
                    // if(!isset($accommodation['prices']['typed_breakdown']['model']['header'])){
                    //     return "Key prices[typed_breakdown]['model']['header'] não retornada.";
                    // }
                    // if(!isset($accommodation['prices']['typed_breakdown']['model']['body'])){
                    //     return "Key prices[typed_breakdown]['model']['body'] não retornada.";
                    // }
                    // if(!isset($accommodation['prices']['typed_breakdown']['model']['footer'])){
                    //     return "Key prices[typed_breakdown]['model']['footer'] não retornada.";
                    // }

                    // // --------------------------- flight --------------------------------------------- //

                    // $position = array_search('FLIGHT', array_column($accommodation['prices']['typed_breakdown']['model']['header'], 'type'));


                    // if($position  === false){
                    //     return "Key prices[typed_breakdown]['model']['header']['type'] == FLIGHT não retornada.";
                    // }

                    // $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['header'][$position]['prices'], 'currency'));
                    
                    // if($position_prices === false){
                    //     return "Key prices[typed_breakdown]['model']['header']['prices']['currency'] == BRL não retornada.";
                    // }
  
                    // $flight_value = $accommodation['prices']['typed_breakdown']['model']['header'][$position]['prices'][$position_prices]['value'];
 
                    // // --------------------------- accommodation --------------------------------------------- //

                    // $position = array_search('HOTEL', array_column($accommodation['prices']['typed_breakdown']['model']['header'], 'type'));


                    // if($position  === false){
                    //     return "Key prices[typed_breakdown]['model']['header']['type'] == HOTEL não retornada.";
                    // }

                    // $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['header'][$position]['prices'], 'currency'));
    
                    // if($position_prices === false){
                    //     return "Key prices[typed_breakdown]['model']['header']['prices']['currency'] == BRL não retornada.";
                    // }

                    // $accommodation_value = $accommodation['prices']['typed_breakdown']['model']['header'][$position]['prices'][$position_prices]['value'];

                    // // --------------------------- taxes --------------------------------------------- //

                    // $position = array_search('TAXES', array_column($accommodation['prices']['typed_breakdown']['model']['body'], 'type'));
    
                    // if($position  === false){
                    //     return "Key prices[typed_breakdown]['model']['body']['type'] == TAXES não retornada.";
                    // }
    
                    // $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));
    
                    // if($position_prices === false){
                    //     return "Key prices[typed_breakdown]['model']['body']['prices']['currency'] == BRL não retornada.";
                    // }
    
                    // $tax_amount = $accommodation['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];


                    // // --------------------------- discount --------------------------------------------- //
                    // $position = array_search('DISCOUNT', array_column($accommodation['prices']['typed_breakdown']['model']['body'], 'type'));
    
                    // if($position){
                    //     $hasDiscount=true;
    
                    //     $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));
    
                    //     $discount =  $accommodation['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];
    
                    //     // --------------------------- final price --------------------------------------------- //
                    //     $position = array_search('FINAL_PRICE', array_column($accommodation['prices']['typed_breakdown']['model']['footer'], 'type'));
    
    
                    //     if($position_prices === false){
                    //         return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
                    //     }
    
                    //     $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));
    
                    //     $amount_with_discount = $accommodation['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];
    
                    //     $amount = $amount_with_discount + $discount;
    
                    //     $discount_percentage = (float) number_format( ($discount/$amount)*100 , 2);
    
                    // }else{
                    //      // --------------------------- final price --------------------------------------------- //
                    //     $position = array_search('FINAL_PRICE', array_column($accommodation['prices']['typed_breakdown']['model']['footer'], 'type'));
    
                    //     if($position_prices === false){
                    //         return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
                    //     }
    
                    //     $position_prices = array_search('BRL', array_column($accommodation['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));
    
                    //     $amount = $accommodation['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];
    
                    // }
    
                    // // array_push($data_room,[
                    // //     'flight_value' => $flight_value,
                    // //     'accommodation_value' => $accommodation_value,
                    // //     'hasDiscount' => $hasDiscount,
                    // //     'amount' => $amount,
                    // //     'tax_amount' => $tax_amount,
                    // //     'amount_with_discount' => $amount_with_discount,
                    // //     'discount_percentage' =>$discount_percentage,
                    // // ]);

                    // array_push($data_hotels,[
                    //     "id" => $accommodation['accommodation']['id'],
                    //     "name" => $accommodation['accommodation']['name'],
                    //     "infos_value" => [
                    //         'flight_value' => $flight_value,
                    //         'accommodation_value' => $accommodation_value,
                    //         'hasDiscount' => $hasDiscount,
                    //         'amount' => $amount,
                    //         'tax_amount' => $tax_amount,
                    //         'amount_with_discount' => $amount_with_discount,
                    //         'discount_percentage' =>$discount_percentage,
                    //     ]
                        
                    // ]);
                }
                
            }
            $pageAtual= $page;
            $page++;
            $url = str_replace("&page=$pageAtual&", "&page=$page&", $url);
            $ratesPacotes = self::mountCurlTakeRates($url,$search_id,$x_hash,$page_view_id);
        }while($ratesPacotes['availability']);
        
        dd($data_hotels);

        //pegar parte do link para poder acessar informaçẽos dos valores do quartos do hotel
        // $result = self::mountCurlTakeUrlSecondStep($pc_id,$page_view_id,$x_hash, $search_id,$abvc,$trip_item,$search_params,$current_step_encoded_url);


        // $url =  json_decode($result,true)['url'];

        // if (preg_match('/\/detail\/(.*?)\?/', $url, $matches)) {
        //     $pc_id = $matches[1];
           
        // } else {
        //     dd("Nenhum detail/pc_id solicitado encontrado.");
          
        // }
        // if (preg_match('/abcv=(.*?)&/', $url, $matches)) {

        //     $abvc = $matches[1];
           
        // } else {
        //     dd("Nenhum abcv solicitado encontrado.");
          
        // }
        // if (preg_match('/searchId=([a-f0-9\-]+)/', $url, $matches)) {

        //     $search_id = $matches[1];
           
        // } else {
        //     dd("Nenhum searchID solicitado encontrado.");
          
        // }

        // $result = self::mountCurlRatesHotelHtml($url);

        // $crawler = new Crawler($result);

        // $script = $crawler->filter('#s-accommodations-state')->text();

        // if (preg_match('/x-hash&q;:&q;(.*?)&q;/', $script, $matches)) {

        //     $x_hash = $matches[1];
           
        // } else {
        //     dd("Nenhum x-hash 2 solicitado encontrado.");
          
        // }

        // if (preg_match('/page-view-id&q;:&q;(.*?)&q;/', $script, $matches)) {

        //     $page_view_id = $matches[1];
           
        // } else {
        //     dd("Nenhum page-view-id solicitado encontrado.");
          
        // }
        
        // $hotel_id = $data_hotels[0]['id'];

        // $response=[];

        // //valores dos quartos do hotel
        // $result = json_decode(self::mountCurlTakeRatesHotel($pc_id,$hotel_id,$trip_item,$destiny,$abvc,$search_id,$page_view_id,$x_hash),true);
        // $references = $result['references'];
        // $name_hotel = $data_hotels[0]['name'];
        // $name_room = null;
        // $room_packs = [];
       
        // dump($references);        

        // //dd($response);
        // $data_rooms = [];
        // foreach($result['data']['room_groups'] as $room){

        //     $data_room = [];
        //     if(isset($room['room_types'][0]['id'])){
        //          // Verifica se o código existe na estrutura e obtém o nome associado
        //          if (isset($references['room_types'][$room['room_types'][0]['id']]['name'])) {
                
        //             $room_name = $references['room_types'][$room['room_types'][0]['id']]['name'];
                   
        //             //dd($response);
        //         } else {
        //             echo "Código não encontrado na estrutura room_types reference.";
        //         }
          
        //     }else{
        //         echo "Código não encontrado na estrutura room_types.";
        //     }
            
        //     $changes_cancellations = null;
        //     foreach($room['room_packs'] as $room_info){

        //         //meal info
        //         if(isset($room_info['meal_plan_id'])){
        //             if($references['meal_plans'][$room_info['meal_plan_id']]['text']){
        //                 $meal_info = $references['meal_plans'][$room_info['meal_plan_id']]['text'];
        //             }else{

        //             }
        //         }else{
        //             echo "não foi";
        //         }

                
        //         //opção de alterar ou cancelar
        //         if(is_array($room_info['cancellation_policy'])){
        //             if(isset($room_info['cancellation_policy']['status']))
        //             {
        //                 if($room_info['cancellation_policy']['status'] === "non_refundable"){
        //                     $changes_cancellations = false;
        //                 }else if($room_info['cancellation_policy']['status'] === "fully_refundable"){
        //                     $changes_cancellations = true;
        //                 }
        //             }else{

        //             }
        //         }else{
        //             echo "não foi";
        //         }

        //         $hasDiscount=false;
        //         $amount=null;
        //         $tax_amount=null;
        //         $amount_with_discount=null;
        //         $discount_percentage=null;
        //         $discount = null;

        //         //valores do quarto
        //         if(!isset($room_info['prices'])){
        //             return "Key prices não retornada.";
        //         }
        //         if(!is_array($room_info['prices'])){
        //             return "Key prices não é um array.";
        //         }
        //         if(!isset($room_info['prices']['typed_breakdown'])){
        //             return "Key typed_breakdown não retornada.";
        //         }
        //         if(!isset($room_info['prices']['typed_breakdown']['model'])){
        //             return "Key prices[typed_breakdown]['model'] não retornada.";
        //         }
        //         if(!isset($room_info['prices']['typed_breakdown']['model']['body'])){
        //             return "Key prices[typed_breakdown]['model']['body'] não retornada.";
        //         }
        //         if(!isset($room_info['prices']['typed_breakdown']['model']['footer'])){
        //             return "Key prices[typed_breakdown]['model']['footer'] não retornada.";
        //         }

        //         $position = array_search('TAXES', array_column($room_info['prices']['typed_breakdown']['model']['body'], 'type'));

        //         if($position  === false){
        //             return "Key prices[typed_breakdown]['model']['body']['type'] == TAXES não retornada.";
        //         }

        //         $position_prices = array_search('BRL', array_column($room_info['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));

        //         if($position_prices === false){
        //             return "Key prices[typed_breakdown]['model']['body']['prices']['currency'] == BRL não retornada.";
        //         }

        //         $tax_amount = $room_info['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];

        //         $position = array_search('DISCOUNT', array_column($room_info['prices']['typed_breakdown']['model']['body'], 'type'));

        //         if($position){
        //             $hasDiscount=true;

        //             $position_prices = array_search('BRL', array_column($room_info['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));

        //             $discount =  $room_info['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];


        //             $position = array_search('FINAL_PRICE', array_column($room_info['prices']['typed_breakdown']['model']['footer'], 'type'));


        //             if($position_prices === false){
        //                 return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
        //             }

        //             $position_prices = array_search('BRL', array_column($room_info['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));

        //             $amount_with_discount = $room_info['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];

        //             $amount = $amount_with_discount + $discount;

        //             $discount_percentage = (int) number_format( ($discount/$amount)*100 , 2);

        //         }else{

        //             $position = array_search('FINAL_PRICE', array_column($room_info['prices']['typed_breakdown']['model']['footer'], 'type'));

        //             if($position_prices === false){
        //                 return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
        //             }

        //             $position_prices = array_search('BRL', array_column($room_info['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));

        //             $amount = $room_info['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];

        //         }

        //         array_push($data_room,[
        //             'meal_info' => $meal_info,
        //             'changes_cancellations' => $changes_cancellations,
        //             'hasDiscount' => $hasDiscount,
        //             'amount' => $amount,
        //             'tax_amount' => $tax_amount,
        //             'amount_with_discount' => $amount_with_discount,
        //             'discount_percentage' =>$discount_percentage,
        //         ]);
        //     }
        //     array_push($data_rooms,[
        //         "room_name" => $room_name, 
        //         "infos" => $data_room
        //     ]);
        // }

        // array_push($response,[
        //     "name_hotel" => $name_hotel, 
        //     "info_rooms" => $data_rooms
        // ]);
        // dd($response);

        
        // $name = 'Rio de janeiro';
        // $name = strtolower($name);
        // $name_query = str_replace(" ", "%20", $name);
        // $name_id =[] ;

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com/suggestions?locale=pt_BR&profile=sbox-cp-vh&hint='.$name_query);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        // $headers = array();
        // $headers[] = 'Accept: */*';
        // $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        // $headers[] = 'Cache-Control: no-cache';
        // $headers[] = 'Connection: keep-alive';
        // $headers[] = 'Pragma: no-cache';
        // $headers[] = 'Referer: https://www.decolar.com/pacotes/';
        // $headers[] = 'Sec-Fetch-Dest: empty';
        // $headers[] = 'Sec-Fetch-Mode: cors';
        // $headers[] = 'Sec-Fetch-Site: same-origin';
        // $headers[] = 'Sec-Gpc: 1';
        // $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        // $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        // $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        // $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $result = json_decode(curl_exec($ch),true);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);

        // if(isset($result['items'][0])){
           
        //     foreach($result['items'][0]['items'] as $city){
        //         $name_city = explode(',',$city['display'])[0];
        //         //var_dump(strtolower($name),strtolower($name_city));
        //         if($name === strtolower($name_city)){
        //             array_push($name_id,[
        //                 'id' => $city['id'],
        //                 'name' => $name_city,
        //                 'type' => $city['target']['type'],
        //                 'display' => $city['display']
        //             ]);
        //         }
                
        //     }
            
        // }
        // return $name_id;
    }

    private function getRateHotel($hotel){

        $hasDiscount=false;
        $amount=null;
        $tax_amount=null;
        $amount_with_discount=null;
        $discount_percentage=null;
        $discount = null;
        $flight_value=null;
        $accommodation_value=null;

        //valores do hotel
        if(!isset($hotel['prices'])){
            return "Key prices não retornada.";
        }
        if(!is_array($hotel['prices'])){
            return "Key prices não é um array.";
        }
        if(!isset($hotel['prices']['typed_breakdown'])){
            return "Key typed_breakdown não retornada.";
        }
        if(!isset($hotel['prices']['typed_breakdown']['model'])){
            return "Key prices[typed_breakdown]['model'] não retornada.";
        }
        if(!isset($hotel['prices']['typed_breakdown']['model']['header'])){
            return "Key prices[typed_breakdown]['model']['header'] não retornada.";
        }
        if(!isset($hotel['prices']['typed_breakdown']['model']['body'])){
            return "Key prices[typed_breakdown]['model']['body'] não retornada.";
        }
        if(!isset($hotel['prices']['typed_breakdown']['model']['footer'])){
            return "Key prices[typed_breakdown]['model']['footer'] não retornada.";
        }

        // --------------------------- flight --------------------------------------------- //

        $position = array_search('FLIGHT', array_column($hotel['prices']['typed_breakdown']['model']['header'], 'type'));


        if($position  === false){
            return "Key prices[typed_breakdown]['model']['header']['type'] == FLIGHT não retornada.";
        }

        $position_prices = array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['header'][$position]['prices'], 'currency'));
        
        if($position_prices === false){
            return "Key prices[typed_breakdown]['model']['header']['prices']['currency'] == BRL não retornada.";
        }

        $flight_value = (float) $hotel['prices']['typed_breakdown']['model']['header'][$position]['prices'][$position_prices]['value'];

        // --------------------------- accommodation --------------------------------------------- //

        $position = array_search('HOTEL', array_column($hotel['prices']['typed_breakdown']['model']['header'], 'type'));


        if($position  === false){
            return "Key prices[typed_breakdown]['model']['header']['type'] == HOTEL não retornada.";
        }

        $position_prices = array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['header'][$position]['prices'], 'currency'));

        if($position_prices === false){
            return "Key prices[typed_breakdown]['model']['header']['prices']['currency'] == BRL não retornada.";
        }

        $accommodation_value = (float) $hotel['prices']['typed_breakdown']['model']['header'][$position]['prices'][$position_prices]['value'];

        // --------------------------- taxes --------------------------------------------- //

        $position = array_search('TAXES', array_column($hotel['prices']['typed_breakdown']['model']['body'], 'type'));

        if($position  === false){
            return "Key prices[typed_breakdown]['model']['body']['type'] == TAXES não retornada.";
        }

        $position_prices =  array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));

        if($position_prices === false){
            return "Key prices[typed_breakdown]['model']['body']['prices']['currency'] == BRL não retornada.";
        }

        $tax_amount = (float) $hotel['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];


        // --------------------------- discount --------------------------------------------- //
        $position = array_search('DISCOUNT', array_column($hotel['prices']['typed_breakdown']['model']['body'], 'type'));

        if($position){
            $hasDiscount=true;

            $position_prices = array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['body'][$position]['prices'], 'currency'));

            $discount = $hotel['prices']['typed_breakdown']['model']['body'][$position]['prices'][$position_prices]['value'];

            // --------------------------- final price --------------------------------------------- //
            $position = array_search('FINAL_PRICE', array_column($hotel['prices']['typed_breakdown']['model']['footer'], 'type'));


            if($position_prices === false){
                return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
            }

            $position_prices = array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));

            $amount_with_discount = (float) $hotel['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];

            $amount = (float) $amount_with_discount + $discount;

            $discount_percentage = (float) number_format( ($discount/$amount)*100 , 2);

        }else{
                // --------------------------- final price --------------------------------------------- //
            $position = array_search('FINAL_PRICE', array_column($hotel['prices']['typed_breakdown']['model']['footer'], 'type'));

            if($position_prices === false){
                return "Key prices[typed_breakdown]['model']['footer']['prices']['currency'] == BRL não retornada.";
            }

            $position_prices = array_search('BRL', array_column($hotel['prices']['typed_breakdown']['model']['footer'][$position]['prices'], 'currency'));

            $amount = (float) $hotel['prices']['typed_breakdown']['model']['footer'][$position]['prices'][$position_prices]['value'];

        }

        return [
            'hotel_id' => $hotel['accommodation']['id'],
            'city_id' => $hotel['accommodation']['location']['city_id'],
            'name_hotel' => $hotel['accommodation']['name'],
            'flight_value' => $flight_value,
            'accommodation_value' => $accommodation_value,
            'hasDiscount' => $hasDiscount,
            'amount' => $amount,
            'tax_amount' => $tax_amount,
            'amount_with_discount' => $amount_with_discount,
            'discount_percentage' =>$discount_percentage
        ];
        
    }

    private function mountCurlTakeUrlFirstStep($id_origin, $id_destiny, $start, $end, $adults,$childrens){
        $text_childrens = $childrens === 0 ? '' : "-$childrens:10";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com/pkg-loader/api/FH/'.$id_origin.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$adults.$text_childrens.'?locale=pt-BR&from=PSB&nw=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/vnd.sail+json';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://www.decolar.com/trip/start/FH/'.$id_origin.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$adults.$text_childrens.'?from=PSB&nw=true';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Original-Referer: https://www.decolar.com/pacotes/';
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'X-Domain: www.decolar.com';
        $headers[] = 'x-user-id: '.Str::uuid();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }
    private function mountCurlTakeHtml($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Fetch-User: ?1';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    private function mountCurlTakeRates($url, $search_id, $x_hash,$page_view_id){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-store';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Expires: 0';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Cp-Step-Num: 0';
        $headers[] = 'Flow: shopping';
        $headers[] = 'Page-View-Id: '.$page_view_id;
        $headers[] = 'Product: flight-hotel';
        $headers[] = 'Search-Id: '.$search_id;
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Step-Flow: detail';
        $headers[] = 'Upa-Tracking: pageview';
        $headers[] = 'X-Client: s-accommodations';
        $headers[] = 'X-Hash: '.$x_hash;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch),true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    private function mountCurlTakeUrlSecondStep($pc_id,$page_view_id,$x_hash, $search_id,$abvc,$trip_item,$search_params,$current_step_encoded_url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com/s-accommodations/api/next-step/'.$pc_id.'?real_traffic=true&real_traffic_4=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"clone_trip\":false,\"current_step_encoded_url\":\"$current_step_encoded_url\",\"trip_item\":\"$trip_item\",\"search_id\":\"$search_id\",\"abcv\":\"$abvc\",\"search_params\":\"$search_params\"}");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Origin: https://www.decolar.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Cp-Step-Num: 0';
        $headers[] = 'Flow: shopping';
        $headers[] = 'Page-View-Id: '.$page_view_id;
        $headers[] = 'Product: flight-hotel';
        $headers[] = 'Search-Id: '.$search_id;
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Step-Flow: results';
        $headers[] = 'Traceparent: 00-97fba4843b6b6cb8e2f734f260585e00-f806a06e68084197-01';
        $headers[] = 'Tracestate: 66629@nr=0-1-210850-371224014-f806a06e68084197----1696528639756';
        $headers[] = 'X-Client: s-accommodations';
        $headers[] = 'X-Hash: '.$x_hash;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    private function mountCurlRatesHotelHtml($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Fetch-Dest: document';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Fetch-User: ?1';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    private function mountCurlTakeRatesHotel($pc_id,$hotel_id,$trip_item,$destiny_id,$abvc,$search_id,$page_view_id,$x_hash){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com/s-accommodations/api/accommodations/trip/'.$pc_id.'/availability/rooms/'.$hotel_id.'?trip_item='.$trip_item.'&destination_id='.$destiny_id.'&abcv='.$abvc.'&real_traffic=true&real_traffic_4=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-store';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Expires: 0';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36';
        $headers[] = 'Cp-Step-Num: 0';
        $headers[] = 'Flow: shopping';
        $headers[] = 'Page-View-Id: '.$page_view_id;
        $headers[] = 'Product: flight-hotel';
        $headers[] = 'Search-Id: '.$search_id;
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"117\", \"Not;A=Brand\";v=\"8\", \"Chromium\";v=\"117\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Step-Flow: detail';
        $headers[] = 'Upa-Tracking: pageview';
        $headers[] = 'X-Client: s-accommodations';
        $headers[] = 'X-Hash: '.$x_hash;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    private function mountCurlFlightAccommodation($pageviewId,$tripItem,$searchParams,$searchId,$abcv,$x_hash,$flow,$pc_id){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.decolar.com/toure/wizard/carts/$pc_id?locale=pt-BR&pageviewId=$pageviewId&flow=$flow&step=hotels&tripItem=$tripItem&stepNum=0&searchParams=$searchParams&searchId=$searchId&abcv=$abcv&real_traffic=true&real_traffic_4=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://www.decolar.com';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36';
        $headers[] = 'X-Client: s-accommodations';
        $headers[] = 'X-Service-Version: 2';
        $headers[] = 'Cp-Step-Num: 0';
        $headers[] = 'Flow: shopping';
        $headers[] = "Page-View-Id: $pageviewId";
        $headers[] = 'Page-View-Referrer: https://www.decolar.com';
        $headers[] = 'Product: flight-hotel';
        $headers[] = "Search-Id: $searchParams";
        $headers[] = 'Sec-Ch-Ua: \"Brave\";v=\"119\", \"Chromium\";v=\"119\", \"Not?A_Brand\";v=\"24\"';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Model: \"\"';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        $headers[] = 'Step-Flow: results';
        $headers[] = "X-Hash: $x_hash";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }
}
