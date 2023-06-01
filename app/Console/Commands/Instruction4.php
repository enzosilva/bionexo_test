<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Instruction\Config\InstructionConfig;
use App\Console\Commands\Instruction\Data\File;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverRadios;

class Instruction4 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload downloaded file in Instruction 3';

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

        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
        $driver->get(InstructionConfig::getInstruction4Url());

        $this->file->setDownloadedFilename('Teste TKS.txt');

        $driver->findElement(WebDriverBy::name('filename'))
            ->setFileDetector(new LocalFileDetector())
            ->sendKeys("$downloadBasePath/{$this->file->getDownloadedFilename()}");

        $radiosElement = $driver->findElement(WebDriverBy::name('filetype'));
        $radios = new WebDriverRadios($radiosElement);

        match (pathinfo($this->file->getDownloadedFilename(), PATHINFO_EXTENSION)) {
            'jpg', 'jpeg', 'png', 'nimageext' => $radios->selectByValue('image'),
            'txt', 'pdf', 'csv', 'nfileext' => $radios->selectByValue('text')
        };

        $uploadButton = $driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::xpath('//input[@type="submit"]'))
        );

        $uploadButton->click();

        try {
            $explanationElement = $driver->wait()->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.explanation p'))
            );

            echo "{$explanationElement->getText()}\n";

            $downloadBasePath = $this->file->getDownloadBasePath();
            $this->file->setDownloadedFilename('success_instruction_4.png');

            $driver->takeScreenshot("$downloadBasePath/{$this->file->getDownloadedFilename()}");
        } catch (\Exception $e) {
            throw new \Exception("Something unexpected happened: {$e->getMessage()}.");
        }

        $driver->quit();
    }
}
