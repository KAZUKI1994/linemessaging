<?php
/**
 * Created by PhpStorm.
 * User: higashiguchi0kazuki
 * Date: 8/20/17
 * Time: 18:06
 */

$accessToken = "VfOwvkCaFH+/Vl+2UgpMTDjyFElbWP6jioVCWKxnpVu3IpAsYyGe+2hNoTAhMHl9RnZXv85XMy7RIq10C0akCwUD8gx/XIVgbucCNVsTrmyYQsrmhwlsmadGaya/whJyUh+PAWcyqCbrL80fK5jBsAdB04t89/1O/w1cDnyilFU=";

$jsonString = file_get_contents('php://input');
error_log($jsonString);
$jsonObj = json_decode($jsonString);

$message = $jsonObj->{"eventes"}[0]->("message");
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

if($message->{"text"} == "確認"){
    $messageData = [
        'type' => 'template',
        'altText' => '確認ダイアログ',
        'template' => [
            'type' => 'confirm',
            'text' => 'Are you sure?',
            'actions' => [
                [
                    'type' => 'message',
                    'label' => 'I am sure.',
                    'text' => 'I am sure.'
                ],
                [
                    'type' => 'message',
                    'label' => 'I am so so.',
                    'text' => 'I am so so.'
                ],
            ]
        ]
    ];
}elseif($message->{"text"}=="ボタン"){
    $messageData = [
        'type' => 'template',
        'altText' => 'ボタン',
        'template' => [
            'type' => 'buttons',
            'title' => 'This is the Title.',
            'text' => 'Choose me.',
            'actions' => [
                [
                    'type' => 'postback',
                    'label' => 'send the post to webhook.',
                    'data' => 'value'
                ],
                [
                    'type' => 'uri',
                    'label' => 'move to google.',
                    'uri' => 'https://google.com'
                ]
            ]

        ]
    ];
}elseif($message->{"text"}=='カルーセル'){
    $messageData = [
        'type' => 'template',
        'altText' => 'カルーセル',
        'template' => [
            'type' => 'carousel',
            'columns' => [
                [
                    'title' => 'carousel 1',
                    'text' => 'carousel 1',
                    'actions' => [
                        [
                            'type' => 'postback',
                            'label' => 'send the post to webhook.',
                            'data' => 'value'
                        ],
                        [
                            'type' => 'uri',
                            'label' => '口コミ見る',
                            'uri' => 'http://clinic.e-kuchikomi.info/'
                        ]
                    ]
                ],
                [
                    'title' => 'carousel 2',
                    'text' => 'carousel 2',
                    'actions' => [
                        [
                            'type' => 'postback',
                            'label' => 'send the post to webhook.',
                            'data' => 'value'
                        ],
                        [
                            'type' => 'uri',
                            'label' => 'ごにょごにょ見る',
                            'uri' => 'http://clinic.e-kuchikomi.info/'
                        ]
                     ]
                ],
            ]
        ]
    ];
}else{
    $messageData = [
        'type' => 'text',
        'text' => $message->{"text"}
    ];
}

$response = [
    'replyToken' => $replyToken,
    'messages' => [$messageData]
];
error_log(json_encode($response));

$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMERQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charset=UTF-8',
    'Authorization: Bearer'.$accessToken
));
$result = curl_exec($ch);
error_log($result);
curl_close($ch);