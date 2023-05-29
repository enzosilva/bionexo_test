<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserAmount;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Instruction1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beecare:instruction1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch username, amount and populate the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverUrl = 'http://localhost:9515';

        $driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
        $driver->get('https://testpages.herokuapp.com/styled/tag/table.html');

        $data = [];

        $rows = $driver->findElements(WebDriverBy::cssSelector('#mytable tbody tr'));
        foreach ($rows as $i => $row) {
            $cells = $row->findElements(WebDriverBy::tagName('td'));
            foreach ($cells as $j => $cell) {
                // Where even represents the cells that contain 'name' and odd 'amount'
                if (($j % 2) === 0) {
                    $username = (string) $cell->getText();
                    $data[$i]['name'] = $username;

                    continue;
                }

                $amount = (float) $cell->getText();
                $data[$i]['amount'] = $amount;
            }
        }

        try {
            array_walk($data, function ($value) {
                $user = new User(['name' => $value['name']]);
                $user->save();

                $userAmount = new UserAmount(['amount' => $value['amount'], 'user_id' => $user->id]);
                $userAmount->save();
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $driver->quit();
    }
}
