<?php

declare(strict_types=1);

namespace App\Console\Commands\Instruction;

use App\Console\Commands\Instruction\Config\InstructionConfig;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * Factory to provide a WebDriver according to parameters
 */
class WebDriverFactory
{
    /**
     * Execute function to provide a WebDriver according to parameters
     * 
     * @var string $url
     * @var string $browser
     * @var mixed $browserOptions
     * @return RemoteWebDriver
     */
    public static function execute(
        string $url,
        string $browser,
        $browserOptions = null
    ): RemoteWebDriver {
        $host = InstructionConfig::getHost();

        $browser = match ($browser) {
            'firefox' => ($browserOptions) ? $browserOptions : DesiredCapabilities::firefox(),
            'chrome' => ($browserOptions) ? $browserOptions : DesiredCapabilities::chrome()
        };

        $driver = RemoteWebDriver::create($host, $browser);
        return $driver->get($url);
    }
}
