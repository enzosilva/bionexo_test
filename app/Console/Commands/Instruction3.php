<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class Instruction3 extends Command
{
    const ORIGINAL_FILENAME = 'textfile.txt';

    const NEW_FILENAME = 'Teste TKS.txt';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and rename file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $serverUrl = 'http://localhost:9515'; // <- add to .env variable
        $downloadBasePath = '/home/enzosilva/Downloads'; // <- add to .env variable

        $options = new ChromeOptions();
        $prefs = array('download.default_directory' => $downloadBasePath);
        $options->setExperimentalOption('prefs', $prefs);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $driver = RemoteWebDriver::create($serverUrl, $capabilities);
        $driver->get('https://testpages.herokuapp.com/styled/download/download.html');

        $directLinkDownloadButton = $driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('direct-download'))
        );

        $directLinkDownloadButton->click();
        do {
            if (file_exists("$downloadBasePath/" . self::ORIGINAL_FILENAME)) {
                sleep(1);

                rename(
                    "$downloadBasePath/" . self::ORIGINAL_FILENAME,
                    "$downloadBasePath/" . self::NEW_FILENAME
                );
            }
        } while (!file_exists("$downloadBasePath/" . self::NEW_FILENAME));

        $driver->quit();
    }
}
