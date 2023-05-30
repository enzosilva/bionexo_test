<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverRadios;

class Instruction4 extends Command
{
    const FILENAME_TO_UPLOAD = 'Teste TKS.txt';

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
     * Execute the console command.
     */
    public function handle(): void
    {
        $serverUrl = 'http://localhost:9515'; // <- add to .env variable
        $downloadBasePath = '/home/enzosilva/Downloads'; // <- add to .env variable

        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
        $driver->get('https://testpages.herokuapp.com/styled/file-upload-test.html');

        $driver->findElement(WebDriverBy::name('filename'))
            ->setFileDetector(new LocalFileDetector())
            ->sendKeys("$downloadBasePath/" . self::FILENAME_TO_UPLOAD);

        $radiosElement = $driver->findElement(WebDriverBy::name('filetype'));
        $radios = new WebDriverRadios($radiosElement);

        match (pathinfo(self::FILENAME_TO_UPLOAD, PATHINFO_EXTENSION)) {
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

            echo $explanationElement->getText();
        } catch (\Exception $e) {
            throw new \Exception("Something unexpected happened: {$e->getMessage()}.");
        }

        $driver->quit();
    }
}
