<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use Line\LineBot\HTTPClient;

class LineController
{
    public function test(Request $request)
    {
        $token = $request->events[0]['replyToken'];
        $client = new \GuzzleHttp\Client();
        $config = new \LINE\Clients\MessagingApi\Configuration();
        $config->setAccessToken(env('test_access_token'));
        $a = new MessagingApiApi(
            client: $client,
            config: $config,
        );

        $text = new TextMessage([
            'type' => 'text', 'text' => 'hello guy'
        ]);

        $request = new ReplyMessageRequest([
            'replyToken' => $token,
            'messages' => [$text],
        ]);
        $a->replyMessage($request);
    }
}
