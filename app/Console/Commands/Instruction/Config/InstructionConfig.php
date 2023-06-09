<?php

declare(strict_types=1);

namespace App\Console\Commands\Instruction\Config;

/**
 * General instructions configuration
 */
class InstructionConfig
{
    public const TEST_PAGES_URL = 'https://testpages.herokuapp.com/styled/';

    public static function getHost(): string
    {
        return env('APP_URL') . ':' . env('WEBDRIVER_PORT');
    }

    public static function getInstruction1Url(): string
    {
        return self::TEST_PAGES_URL . 'tag/table.html';
    }

    public static function getInstruction2Url(): string
    {
        return self::TEST_PAGES_URL . 'basic-html-form-test.html';
    }

    public static function getInstruction3Url(): string
    {
        return self::TEST_PAGES_URL . 'download/download.html';
    }

    public static function getInstruction4Url(): string
    {
        return self::TEST_PAGES_URL . 'file-upload-test.html';
    }
}
