<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use Illuminate\Routing\Controller as BaseController;

abstract class LineController extends BaseController
{
    protected $accessToken;
    protected $replyToken;
    protected $userId;

    /**
     * 預先儲存使用者基本資訊
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->accessToken = env('access_token');
        $this->replyToken = $request->events[0]['replyToken'];
        $this->userId = $request->events[0]['source']['userId'];
    }


    /**
     * 回覆使用者訊息
     *
     * @param string $message
     * @return void
     */
    protected function replyMessage(string $message)
    {
        $client = new \GuzzleHttp\Client();

        $config = new \LINE\Clients\MessagingApi\Configuration();
        $config->setAccessToken($this->accessToken);

        $messagingApi = new MessagingApiApi(
            client: $client,
            config: $config,
        );

        $text = new TextMessage([
            'type' => 'text',
            'text' => $message,
        ]);

        $request = new ReplyMessageRequest([
            'replyToken' => $this->replyToken,
            'messages' => [$text],
        ]);

        $messagingApi->replyMessage($request);
    }

    /**
     * 取得使用者自己設定的line名字
     *
     * @return string
     */
    protected function getUserLineName(): string
    {
        $client = new \GuzzleHttp\Client();

        $config = new \LINE\Clients\MessagingApi\Configuration();
        $config->setAccessToken($this->accessToken);

        $messagingApi = new MessagingApiApi(
            client: $client,
            config: $config,
        );

        $profile = $messagingApi->getProfile($this->userId);
        $name = $profile['displayName'];

        return $name;
    }
}
