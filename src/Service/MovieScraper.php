<?php

namespace App\Service;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Psr\Log\LoggerInterface;

class MovieScraper
{

    public function __construct(
        private LoggerInterface $logger,
        private string $url,
        private string $parseScriptPath,
        private string $remoteChromeWebdriver,
    ) {
    }


    public function loadTop10()
    {
        try {
            $driver = RemoteWebDriver::create($this->remoteChromeWebdriver, DesiredCapabilities::chrome());
            $driver->get($this->url);
            $result = $driver->executeScript('return ' . file_get_contents($this->parseScriptPath));
            $driver->quit();

            return json_decode($result, true);
        } catch (\Exception $e) {
            $this->logger->error("Loading movies failed: " . $e->getMessage());
            return null;
        }
    }
    
}
