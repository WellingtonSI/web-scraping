<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class PacotesDecolarController extends Controller
{
    public function pacotesDecolar(){
         
        $origin = 'CIT_6574';
        $destiny = 'CIT_4430';
        $date_start = '2023-12-14';
        $date_end = '2023-12-16';
        $people = 2;
        $result = self::mountCurlTakeUrlFirstStep($origin, $destiny, $date_start,$date_end,$people);


        $url = json_decode($result,true)['url'];
        $pc_id = '';
        $current_step_encoded_url  = base64_encode("https://www.decolar.com/".$url);
    
        if (preg_match('/\/results\/(.*?)\?/', $url, $matches)) {
            // O URL solicitado estará em $matches[1]
            $pc_id = $matches[1];
           
        } else {
            dd("Nenhum URL solicitado encontrado.");
          
        }
    
        $result = self::mountCurlTakeHtml($url);

        $crawler = new Crawler($result);

        $script = $crawler->filter('body > script')->text();
        $search_id = '';
        $x_hash = '';
        $page_view_id ='';
        $abvc = '';
        $trip_item ='';
        $search_params ='';
        $data_hotels = [];
        // função preg_match para encontrar a correspondência
        if (preg_match('/"search-id", "(.*?)"\);/', $script, $matches)) {
            // O URL solicitado estará em $matches[1]
            $search_id = $matches[1];
           
        } else {
            dd("Nenhum URL solicitado encontrado.");
          
        }
        if (preg_match('/requestUrl = "(.*?)";/', $script, $matches)) {

            $url = $matches[1];
           
        } else {
            dd("Nenhum search-id solicitado encontrado.");
          
        }
        if (preg_match('/"x-hash", "(.*?)"\);/', $script, $matches)) {

            $x_hash = $matches[1];
           
        } else {
            dd("Nenhum x-hash solicitado encontrado.");
          
        }

        if (preg_match('/"page-view-id", "(.*?)"\);/', $script, $matches)) {

            $page_view_id = $matches[1];
           
        } else {
            dd("Nenhum x-hash 1 solicitado encontrado.");
          
        }

        if (preg_match('/abcv=(.*?)&/', $script, $matches)) {

            $abvc = $matches[1];
           
        } else {
            dd("Nenhum x-hash solicitado encontrado.");
          
        }

        if (preg_match('/&trip_item=(.*?)&/', $url , $matches)) {

            $trip_item = $matches[1];
           
        } else {
            dd("Nenhum search-id solicitado encontrado.");
          
        }

        $ratesPacotes = self::mountCurlTakeRates($url,$search_id,$x_hash,$page_view_id);
    
        foreach($ratesPacotes['availability'] as $accommodation){
            array_push($data_hotels,[
                "id" => $accommodation['accommodation']['id'],
                "name" => $accommodation['accommodation']['name'],
                "geo_id" => $accommodation['accommodation']['location']['city']['geo_id']
            ]);
        }
        
        $result = self::mountCurlTakeUrlSecondStep($pc_id,$page_view_id,$x_hash, $search_id,$abvc,$trip_item,$search_params,$current_step_encoded_url);

        $url =  json_decode($result,true)['url'];

        if (preg_match('/\/detail\/(.*?)\?/', $url, $matches)) {
            $pc_id = $matches[1];
           
        } else {
            dd("Nenhum detail/pc_id solicitado encontrado.");
          
        }
        if (preg_match('/abcv=(.*?)&/', $url, $matches)) {

            $abvc = $matches[1];
           
        } else {
            dd("Nenhum abcv solicitado encontrado.");
          
        }
        if (preg_match('/searchId=([a-f0-9\-]+)/', $url, $matches)) {

            $search_id = $matches[1];
           
        } else {
            dd("Nenhum searchID solicitado encontrado.");
          
        }

        $result = self::mountCurlRatesHotelHtml($url);

        $crawler = new Crawler($result);

        $script = $crawler->filter('#s-accommodations-state')->text();

        if (preg_match('/x-hash&q;:&q;(.*?)&q;/', $script, $matches)) {

            $x_hash = $matches[1];
           
        } else {
            dd("Nenhum x-hash 2 solicitado encontrado.");
          
        }

        if (preg_match('/page-view-id&q;:&q;(.*?)&q;/', $script, $matches)) {

            $page_view_id = $matches[1];
           
        } else {
            dd("Nenhum page-view-id solicitado encontrado.");
          
        }
        
        $hotel_id = $data_hotels[0]['id'];

        $response=[];

        $result = json_decode(self::mountCurlTakeRatesHotel($pc_id,$hotel_id,$trip_item,$destiny,$abvc,$search_id,$page_view_id,$x_hash),true);
        $references = $result['references'];
        $name_hotel = $data_hotels[0]['name'];
        $name_room = null;
        $room_packs = [];
       
        dump($references);        

        //dd($response);
        foreach($result['data']['room_groups'] as $room){

            if(isset($room['room_types'][0]['id'])){
                 // Verifica se o código existe na estrutura e obtém o nome associado
                 if (isset($references['room_types'][$room['room_types'][0]['id']]['name'])) {
                
                    $name_room = $references['room_types'][$room['room_types'][0]['id']]['name'];
                   
                    //dd($response);
                } else {
                    echo "Código não encontrado na estrutura room_types reference.";
                }
          
            }else{
                echo "Código não encontrado na estrutura room_types.";
            }

            $data_rooms = [];
            $changes_cancellations = null;
            foreach($room['room_packs'] as $room_info){

                //meal info
                if(isset($room_info['meal_plan_id'])){
                    if($references['meal_plans'][$room_info['meal_plan_id']]['text']){
                        $meal_info = $references['meal_plans'][$room_info['meal_plan_id']]['text'];
                    }else{

                    }
                }else{

                }

                
                //opção de alterar ou cancelar
                if(is_array($room_info['cancellation_policy'])){
                    if(isset($room_info['cancellation_policy']['status']))
                    {
                        if($room_info['cancellation_policy']['status'] === "non_refundable"){
                            $changes_cancellations = false;
                        }else if($room_info['cancellation_policy']['status'] === "fully_refundable"){
                            $changes_cancellations = true;
                        }
                    }else{

                    }
                }else{

                }

                
                if(is_array($room_info['prices']))

   
            }
         
        }

        // array_push($response,[
        //     "name_hotel" => $name_hotel, 
        //     "info_rooms" => [
                
        //     ]
        // ]);
        //dd($result['data']['room_groups']);

        
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

    private function mountCurlTakeUrlFirstStep($id_origin, $id_destiny, $start, $end, $people){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.decolar.com/pkg-loader/api/FH/'.$id_origin.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$people.'?locale=pt-BR&from=PSB&nw=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/vnd.sail+json';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Referer: https://www.decolar.com/trip/start/FH/'.$id_origin.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$id_destiny.'/'.$start.'/'.$end.'/'.$people.'?from=PSB&nw=true';
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
}