<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Instruction\Config\InstructionConfig;
use App\Console\Commands\Instruction\Data\File;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class Instruction3 extends Command
{
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
     * @var File $file
     */
    public function __construct(private File $file)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $serverUrl = InstructionConfig::getServerUrl();
        $downloadBasePath = $this->file->getDownloadBasePath();

        $options = new ChromeOptions();
        $prefs = array('download.default_directory' => $downloadBasePath);
        $options->setExperimentalOption('prefs', $prefs);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $driver = RemoteWebDriver::create($serverUrl, $capabilities);
        $driver->get(InstructionConfig::getInstruction3Url());

        $directLinkDownloadButton = $driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('direct-download'))
        );

        $directLinkDownloadButton->click();
        do {
            $downloadedFilename = $this->file->getDownloadedFilename();
            if (file_exists("$downloadBasePath/$downloadedFilename")) {
                sleep(1);

                $this->file->setDownloadedFilename('Teste TKS.txt');
                rename(
                    "$downloadBasePath/$downloadedFilename",
                    "$downloadBasePath/{$this->file->getDownloadedFilename()}"
                );
            }
        } while (!file_exists("$downloadBasePath/{$this->file->getDownloadedFilename()}"));

        $driver->quit();
    }
}
