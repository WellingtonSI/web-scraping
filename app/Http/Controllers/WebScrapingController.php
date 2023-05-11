<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebScrapingController extends Controller
{

    public function webScraping()
    {
        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://www.hoteis.com/Hotel-Search?destination=Opaba%20Praia%20Hotel&regionId=&latLong=-14.816838,-39.025248&selected=3957818&d1=2023-05-24&startDate=2023-05-24&d2=2023-05-25&endDate=2023-05-25&adults=2&rooms=1');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        // $headers = array();
        // $headers[] = 'Authority: www.hoteis.com';
        // $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8';
        // $headers[] = 'Accept-Language: pt-BR,pt;q=0.6';
        // $headers[] = 'Cache-Control: no-cache';
        // $headers[] = 'Cookie: linfo=v.4,|0|0|255|1|0||||||||1046|0|0||0|0|0|-1|-1; CRQS=t|3018`s|301800003`l|pt_BR`c|BRL; currency=BRL; tpid=v.1,3018; MC1=GUID=3c0b06b495974df3ac6472a189c86b92; DUAID=3c0b06b4-9597-4df3-ac64-72a189c86b92; OIP=gdpr|-1; akacd_pr_20=1688859778~rv=14~id=3cf63281c75549cc107281053aa89ef2; CRQSS=e|3; iEAPID=3; HMS=efb59506-79fa-48c7-a69a-ec7b7565f0fa; _abck=D3F2C9DF391B05577EBCE84C81EF1DB6~0~YAAQJX/NFycuZf6HAQAAQd5OBglChyj84meU82CMnOI9ju+KcJsVRplBOBx5+A0zLjDzEPGteEpGPou/Zmf6s28lZT2IzSUMzJD5SlGb3r8zTywjO3lYx6CvYMyWNP1atTd1EgIfdm5uu8exwMIfSN7t5D2z+tIr0gdKdjr6omAwLiyh0sjW7NR7nOzONrWYMA/bDAyGb3c5wnoZZJrrcvBjC0QCsB9Tm+dFd2gc9wm0mqVy5YiDkeusCFIF+yUMYqOKdpY2M2aWTx7EngfL989+3Uqmtw6lULJZmYL0qf3WozCGRAz/Xf6Bj/J4ApIdKzIurFD4VymoiVAr7GWiEDBcqCXnH4/UFAOv+ka9oNEo5LNo/S/8sH98nX7l44fEvlKb9jzQTzer6X4dsiTQLcMSJFRBzWY=~-1~-1~-1; bm_sz=51C2B56D10180D55CBE9AF73CFAF744E~YAAQJX/NFyguZf6HAQAAQd5OBhO3wUrP3BrGGlHGMRsOoZoLe5j+XqCRbj1W8FooKDTxnmT7bAbDWxugwSMqTHq2SqHJ/rX6ffR+rgZOL69tOswjpznqZxK1Rmwx48CoR/5YhcZLH5sZgr40p2ryNcBdmMKoUdIVJuqo2sYxr4Bmmo78gJNrDSNhdiBtVKU/q/LkYEjYPsVgKq2wr3GIh9g6AodxLJP2DIVLH9SL7WNeutK1Hz/nRV/WBq/fWdNoEiJLt4snImGniSgCkzidvd3jt3DvHf7JL4+tSpHMp53MnDQ=~4274486~3556163; s_ppv=%5B%5BB%5D%5D; s_ips=1; AMCVS_C00802BE5330A8350A490D4C%40AdobeOrg=1; s_cc=true; session_id=efb59506-79fa-48c7-a69a-ec7b7565f0fa; AWSELB=D79B53F10ADCF9DDDF09C7B84896C09A6222EC2F5DC82C53E2E9201DC264A9501B993E8251F2A58F84B43F61ADD9D865003C2FA4F694A16BCA7C2E33DBFDF7BA275782A609; AWSELBCORS=D79B53F10ADCF9DDDF09C7B84896C09A6222EC2F5DC82C53E2E9201DC264A9501B993E8251F2A58F84B43F61ADD9D865003C2FA4F694A16BCA7C2E33DBFDF7BA275782A609; JSESSIONID=8DA2B9DA68841DB81C3E205DDF6D5636; cesc=%7B%22marketingClick%22%3A%5B%22false%22%2C1683733352584%5D%2C%22hitNumber%22%3A%5B%2212%22%2C1683733352584%5D%2C%22visitNumber%22%3A%5B%224%22%2C1683733011955%5D%2C%22entryPage%22%3A%5B%22noonewillmatchthis%22%2C1683733352584%5D%2C%22seo%22%3A%5B%22SEO.U.google.com%22%2C1683675778311%5D%2C%22cid%22%3A%5B%22SEO.U.google.com%22%2C1683675778311%5D%7D; s_ppn=Homepage; AMCV_C00802BE5330A8350A490D4C%40AdobeOrg=1585540135%7CMCIDTS%7C19487%7CMCMID%7C20164232515601156027274485000544075421%7CMCAID%7CNONE%7CMCOPTOUT-1683740553s%7CNONE%7CvVersion%7C4.4.0; page_name=Homepage; s_tp=2541';
        // $headers[] = 'Pragma: no-cache';
        // $headers[] = 'Referer: https://www.hoteis.com/';
        // $headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"112\", \"Brave\";v=\"112\", \"Not:A-Brand\";v=\"99\"';
        // $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        // $headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
        // $headers[] = 'Sec-Fetch-Dest: document';
        // $headers[] = 'Sec-Fetch-Mode: navigate';
        // $headers[] = 'Sec-Fetch-Site: same-origin';
        // $headers[] = 'Sec-Fetch-User: ?1';
        // $headers[] = 'Sec-Gpc: 1';
        // $headers[] = 'Upgrade-Insecure-Requests: 1';
        // $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);

        // return $result;
    }
}
