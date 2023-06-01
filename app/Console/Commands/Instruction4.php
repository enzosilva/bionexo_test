<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Instruction\Config\InstructionConfig;
use App\Console\Commands\Instruction\Data\File;
use App\Console\Commands\Instruction\WebDriverFactory;
use Facebook\WebDriver\Remote\LocalFileDetector;
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
    protected $signature = 'beecare:instruction4 {browser=chrome}';

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
        $driver = WebDriverFactory::execute(
            InstructionConfig::getInstruction4Url(),
            $this->argument('browser')
        );

        $downloadBasePath = $this->file->getDownloadBasePath();
        $this->file->setFilename('Teste TKS.txt');

        $driver->findElement(WebDriverBy::name('filename'))
            ->setFileDetector(new LocalFileDetector())
            ->sendKeys("$downloadBasePath/{$this->file->getFilename()}");

        $radiosElement = $driver->findElement(WebDriverBy::name('filetype'));
        $radios = new WebDriverRadios($radiosElement);

        match (pathinfo($this->file->getFilename(), PATHINFO_EXTENSION)) {
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

            $this->file->setFilename('success_instruction_4.png');
            $driver->takeScreenshot("$downloadBasePath/{$this->file->getFilename()}");
        } catch (\Exception $e) {
            throw new \Exception("Something unexpected happened: {$e->getMessage()}.");
        }

        $driver->quit();
    }
}
