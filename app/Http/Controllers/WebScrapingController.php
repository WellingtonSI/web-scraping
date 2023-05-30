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

        $query = [
            "operationName" => "PropertyOffersQuery",
            "query" => 'query PropertyOffersQuery($context: ContextInput!, $propertyId: String!, $searchCriteria: PropertySearchCriteriaInput, $shoppingContext: ShoppingContextInput, $travelAdTrackingInfo: PropertyTravelAdTrackingInfoInput, $searchOffer: SearchOfferInput, $referrer: String) {
                propertyOffers(
                  context: $context
                  propertyId: $propertyId
                  referrer: $referrer
                  searchCriteria: $searchCriteria
                  searchOffer: $searchOffer
                  shoppingContext: $shoppingContext
                  travelAdTrackingInfo: $travelAdTrackingInfo
                ) {
                    id
                    categorizedListings {
                        ...PropertyUnitCategorizationFragment
                    }
                }
              }


              fragment PropertyUnitCategorizationFragment on LodgingCategorizedUnit {
                header {
                    text
                }
                features {
                    text
                }
                primarySelections {
                    propertyUnit {
                        ratePlans {
                            id
                            amenities {
                                description
                            }
                            paymentPolicy {
                              price {
                                displayMessages {
                                    lineItems {
                                      ...PriceMessageFragment
                                      ...EnrichedMessageFragment
                                    }
                                }
                              }
                            }
                            priceDetails{
                              lodgingPrepareCheckout{
                                action{
                                  totalPrice{
                                    amount
                                  }
                                }
                              }
                            }
                        }
                    }
                }
              }
              
              fragment PriceMessageFragment on DisplayPrice {
                price {
                  formatted
                }
              }
              
              fragment EnrichedMessageFragment on LodgingEnrichedMessage {
                value
              }
              ',
            "variables" => [
                "propertyId"=> "3957818",
                "searchCriteria"=> [
                    "primary"=> [
                        "dateRange"=> [
                            "checkInDate"=> [
                                "day"=> 12,
                                "month"=> 6,
                                "year"=> 2023
                            ],
                            "checkOutDate"=> [
                                "day"=> 13,
                                "month"=> 6,
                                "year"=> 2023
                            ]
                        ],
                        "destination"=> [
                            "coordinates"=> [
                                "latitude"=> -14.816838,
                                "longitude"=> -39.025248
                            ],
                            "pinnedPropertyId"=> "3957818",
                        ],
                        "rooms"=> [
                            [
                                "adults"=> 2,
                                "children"=> []
                            ]
                        ]
                    ]
                ],
                "searchOffer"=> [
                    "offerPrice"=> [
                        "offerTimestamp" => "1685017471604",
                        "price"=> [
                            "amount"=> 250,
                            "currency"=> "BRL"
                        ]
                    ],
                ],
                "referrer"=> "HSR",
                "context"=> [
                    "siteId"=> 301800003,
                    "locale"=> "pt_BR",
                    "eapid"=> 3,
                    "currency"=> "BRL",
                    "device"=> [
                        "type"=> "DESKTOP"
                    ],
                    "identity"=> [
                        "duaid"=> "b895f215-3bf5-4fbc-971f-dc46a6e3cc6b",
                    ]
                ]
                
            ] 
        ];


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.hoteis.com/graphql');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($query));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: www.hoteis.com';
        $headers[] = 'Accept: */*';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.5';
        $headers[] = 'Client-Info: shopping-pwa,07da2d4e6bdc94b0df448dcf1386c2827c84a3c7,us-west-2';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Origin: https://www.hoteis.com';
        $headers[] = 'Referer: https://www.hoteis.com';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Site: same-origin';
        $headers[] = 'Sec-Gpc: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36';
        $headers[] = 'X-Page-Id: page.Hotels.Infosite.Information,H,30';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        dump($result);
    }
}
