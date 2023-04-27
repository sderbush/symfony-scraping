<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Spatie\Browsershot\Browsershot;

class MovieScraper
{

    public function __construct(
        private LoggerInterface $logger,
        private string $url,
        private string $parseScriptPath,
    ) {
    }


    public function loadTop10()
    {
        try {
            $evaluate = Browsershot::url($this->url)
                ->noSandbox()
                ->setExtraHttpHeaders([
                    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 YaBrowser/23.3.0.2295 Yowser/2.5 Safari/537.36',
                    'referer' => 'https://sso.kinopoisk.ru/',
                    'accept-language' => 'ru,en;q=0.9',
                    'accept-encoding' => 'gzip, deflate, br',
                    'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                    'cookie' => 'disable_server_sso_redirect=1'
                ])
                ->evaluate(file_get_contents($this->parseScriptPath));

            return json_decode($evaluate, true);
        } catch (\Exception $e) {
            $this->logger->error("Loading movies failed: " . $e->getMessage());
            return null;
        }
    }
    
}
