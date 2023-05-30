<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;

class Instruction2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverUrl = 'http://localhost:9515';

        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
        $driver->get('https://testpages.herokuapp.com/styled/basic-html-form-test.html');

        $driver->findElement(WebDriverBy::name('username'))
            ->sendKeys('test');

        $driver->findElement(WebDriverBy::name('password'))
            ->sendKeys('123456');

        $driver->findElement(WebDriverBy::name('comments'))
            ->clear()
            ->sendKeys('Lorem ipsum dolor sit amet...');

        $filepath = '/home/enzosilva/Documentos/test.txt';
        $driver->findElement(WebDriverBy::name('filename'))
            ->setFileDetector(new LocalFileDetector())
            ->sendKeys($filepath);

        $checkboxesElement = $driver->findElement(WebDriverBy::name('checkboxes[]'));
        $checkboxes = new WebDriverCheckboxes($checkboxesElement);
        $checkboxes->deselectAll();
        $checkboxes->selectByValue('cb1');

        $radiosElement = $driver->findElement(WebDriverBy::name('radioval'));
        $radios = new WebDriverRadios($radiosElement);
        $radios->selectByValue('rd3');

        $multipleSelectElement = $driver->findElement(WebDriverBy::name('multipleselect[]'));
        $multipleSelect = new WebDriverSelect($multipleSelectElement);
        $multipleSelect->deselectAll();
        $multipleSelect->selectByValue('ms1');
        $multipleSelect->selectByValue('ms2');

        $dropdownElement = $driver->findElement(WebDriverBy::name('dropdown'));
        $dropdown = new WebDriverSelect($dropdownElement);
        $dropdown->selectByValue('dd5');

        $submitButton = $driver->wait()->until(
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::xpath('//input[@type="submit"]'))
        );

        $submitButton->click();

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
