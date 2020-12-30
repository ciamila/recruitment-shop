<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\SlackConfig;
use App\Exception\ShopException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SlackApiService
{
    /**
     * @param string $message
     * 
     * @return void
     */
    public function send(string $message): void 
    {
        try {
            (new Client())->post(
                SlackConfig::SLACK_BASE_URL.SlackConfig::SLACK_BASE_CHANNEL,
                ['text' => $message]
            );
        } catch (ClientException $exception) {
            throw new ShopException('There was problem with sending information on slack');
        }
    }
}