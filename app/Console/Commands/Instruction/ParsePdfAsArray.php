<?php

declare(strict_types=1);

namespace App\Console\Commands\Instruction;

use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;

class ParsePdfAsArray
{
    /**
     * @const Represents index of position X in pdf to array parse
     */
    private const INDEX_POSITION_X = 4;

    /**
     * @const Represents index of position Y in pdf to array parse
     */
    private const INDEX_POSITION_Y = 5;

    /**
     * @var Parser $parser
     */
    public function __construct(private Parser $parser)
    {
    }

    /**
     * Realize the parse of PDF object to array.
     * 
     * @var Document $pdf
     * @var array $map
     * @var mixed $pageNumber
     * @var int $pageOffset
     * @return array
     */
    public function execute(
        Document $pdf,
        array $map,
        $pageNumber = null,
        int $pageOffset = 0
    ): array {
        $pages = $pdf->getPages();
        if ($pageNumber) {
            $pages = $pdf->getPages()[$pageNumber];
        }

        if ($pageOffset) {
            array_slice($pages, $pageOffset);
        }

        $result = [];
        foreach ($pages as $n => $page) {
            $content = $page->getDataTm();
            array_walk($map, function ($data) use ($n, &$content, &$result) {
                if ($value = $this->fetchPositionValue($content, $data['position'])) {
                    $result[0][] = $data['cell'];
                    $result[($n + 1)][] = $value;
                }
            });
        }

        // Merge headers cells
        $result[0] = array_unique($result[0]);

        return $result;
    }

    /**
     * Fetch cell value of given position in array
     * 
     * @var array $content
     * @var string $position
     * @return string
     */
    private function fetchPositionValue(array $content, string $position): string
    {
        $value = "";
        if (!$position) {
            return $value;
        }

        // The x and y positions on the map are separated by comma (',')
        $positionX = explode(",", $position)[0];
        $positionY = explode(",", $position)[1];

        foreach ($content as $cell) {
            for ($i = 0; $i < count($content); $i++) {
                if (
                    $cell[0][self::INDEX_POSITION_X] == $positionX &&
                    $cell[0][self::INDEX_POSITION_Y] == $positionY
                ) {
                    $value = (string) $cell[1];
                }
            }
        }

        return $value;
    }
}
