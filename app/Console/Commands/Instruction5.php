<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Commands\Instruction\Data\File;
use App\Console\Commands\Instruction\Data\Map\PdfExampleMainPage;
use App\Console\Commands\Instruction\Data\Map\PdfExampleProviderPage;
use App\Console\Commands\Instruction\ParseArrayAsCsv;
use App\Console\Commands\Instruction\ParsePdfAsArray;
use Illuminate\Console\Command;
use Smalot\PdfParser\Parser;

class Instruction5 extends Command
{
    const INDEX_POSITION_X = 4;

    const INDEX_POSITION_Y = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse PDF file and convert to CSV';

    /**
     * @var File $file
     * @var Parser $parser
     * @var ParsePdfAsArray $parsePdfAsArray
     */
    public function __construct(
        private File $file,
        private Parser $parser,
        private ParsePdfAsArray $parsePdfAsArray
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $downloadBasePath = $this->file->getDownloadBasePath();

        $this->file->setFilename('bionexo_test.pdf');
        $downloadedFilename = $this->file->getFilename();

        try {
            $pdf = $this->parser->parseFile("$downloadBasePath/$downloadedFilename");

            $mainMap = PdfExampleMainPage::PDF_MAP;
            $main = $this->parsePdfAsArray->execute($pdf, $mainMap, 0);

            ParseArrayAsCsv::execute($main, 'main');

            $providersMap = PdfExampleProviderPage::PDF_MAP;
            $providers = $this->parsePdfAsArray->execute($pdf, $providersMap, null, 2);

            ParseArrayAsCsv::execute($providers, 'providers');
        } catch (\Exception $e) {
            throw new \Exception("Cannot parse PDF file: {$e->getMessage()}");
        }
    }
}
