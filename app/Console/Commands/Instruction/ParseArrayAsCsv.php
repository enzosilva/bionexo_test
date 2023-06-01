<?php

declare(strict_types=1);

namespace App\Console\Commands\Instruction;

class ParseArrayAsCsv
{
    /**
     * Makes a CSV file from given array data
     * 
     * @var array $data
     * @var string $filename
     * @return void
     */
    public static function execute(array $data, string $filename = 'pdf'): void
    {
        $fp = fopen("$filename.csv", 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}
