<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;


class WebScrapingController extends Controller
{

    public function webScraping()
    {
        
        $query = [
            "operationName" => "PropertyOffersQuery",
            "query" => 'query PropertyOffersQuery($context: ContextInput!, $propertyId: String!, $searchCriteria: PropertySearchCriteriaInput, $shoppingContext: ShoppingContextInput, $travelAdTrackingInfo: PropertyTravelAdTrackingInfoInput) {
                propertyOffers(
                  context: $context
                  propertyId: $propertyId
                  searchCriteria: $searchCriteria
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
                    graphic {
                        ... on Icon {
                            id
                        }
                    }
                }

                primarySelections {

                    propertyUnit {

                        detailsDialog {
                            ...UnitPropertyOffersDetailsDialogFragment
                        }

                        ratePlans {

                            badge {
                                text
                            }

                            priceDetails{
                              price {

                                options {
                                    strikeOut {
                                        formatted
                                    }
                                }
                                
                              }

                              priceBreakDownSummary {

                                sections {

                                    items {

                                        value {

                                            primaryMessage {
                                                value
                                            }
                                           
                                        }
                                    }

                                }

                              }

                            }
                        }

                    }

                }

              }
              fragment UnitPropertyOffersDetailsDialogFragment on PropertyUnitDetailsDialog {
                content {
                    details {
                            contents {
                                heading 
                                items {
                                    text
  
                                }

                            }
       
                    }

                }

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
                            ]
                        ],
                        "rooms"=> [
                            [
                                "adults"=> 2,
                                "children"=> [ "age" => 8]
                            ]
                        ]
                    ]
                ],
                "context"=> [
                    "siteId"=> 301800003,
                    "locale"=> "pt_BR",
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

        $result = json_decode(curl_exec($ch),true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        //dump($result);

        $data = new Collection();
        $data->put('data', []);

        foreach($result['data']['propertyOffers']['categorizedListings'] as $quarto){
           $pensao = [];
           $ocupacao = [];

            $ids = array_column(array_column($quarto['features'],'graphic'),'id');

            if (in_array('free_breakfast', $ids)) {
                $posicao = array_search('free_breakfast', $ids);
                $pensao = $quarto['features'][$posicao]['text'];

                unset($quarto['features'][$posicao]);
                $quarto['features'] = array_values($quarto['features']);
                
            }

            $informacoesQuarto = array_column ( array_filter($quarto['features'], function ($item) {
                return !empty($item['graphic']) && (!isset($item['graphic']['id']) || $item['graphic']['id'] !== 'done');
            }), 'text' );
            

            $ocupacao = array_values( array_filter($quarto['primarySelections'][0]['propertyUnit']['detailsDialog']['content']['details']['contents'], function ($item) {
                return $item['heading'] === 'Acomoda';
            } ) );

            if(!empty($ocupacao))
                $ocupacao = $ocupacao[0]['items'][0]['text'];

            dump($ocupacao);
            
        
        //    $quarto = [
        //     "nome" => $room['header']['text'],
        //     "pensao" => $room['features'][2]['text'],
        //     "informacoesQuarto"
        //    ];

            
        }


        
    }
}
