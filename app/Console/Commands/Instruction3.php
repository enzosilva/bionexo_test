<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Instruction\Config\InstructionConfig;
use App\Console\Commands\Instruction\Data\File;
use App\Console\Commands\Instruction\WebDriverFactory;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class Instruction3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction3 {browser=chrome}';

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
        $downloadBasePath = $this->file->getDownloadBasePath();

        $options = new ChromeOptions();
        $prefs = array('download.default_directory' => $downloadBasePath);
        $options->setExperimentalOption('prefs', $prefs);

        $capabilities = DesiredCapabilities::chrome();
        $browserOptions = $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        // TODO: improve WebDriverFactory to receive browser options dynamicaly
        $driver = WebDriverFactory::execute(
            InstructionConfig::getInstruction3Url(),
            $this->argument('browser'),
            $browserOptions
        );

        $directLinkDownloadButton = $driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('direct-download'))
        );

        $directLinkDownloadButton->click();
        do {
            $downloadedFilename = $this->file->getFilename();
            if (file_exists("$downloadBasePath/$downloadedFilename")) {
                // Give a little time to browser process the file download
                sleep(3);

                $this->file->setFilename('Teste TKS.txt');
                rename(
                    "$downloadBasePath/$downloadedFilename",
                    "$downloadBasePath/{$this->file->getFilename()}"
                );
            }
        } while (!file_exists("$downloadBasePath/{$this->file->getFilename()}"));

        $driver->quit();
    }
}
