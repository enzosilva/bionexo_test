<?php

declare(strict_types=1);

namespace App\Console\Commands\Instruction\Data;

/**
 * Getters and setters to manage file paths and names
 */
class File
{
    public function getDownloadBasePath(): string
    {
        return config('beecare.download_base_path', '/home/enzosilva/Downloads');
    }

    public function setDownloadBasePath(string $downloadBasePath): self
    {
        config(['beecare.download_base_path' => $downloadBasePath]);

        return $this;
    }

    public function getFilename(): string
    {
        return config('beecare.filename', 'textfile.txt');
    }

    public function setFilename(string $filename): self
    {
        config(['beecare.filename' => $filename]);

        return $this;
    }
}
