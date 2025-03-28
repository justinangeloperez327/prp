<?php

namespace App\Console\Commands;

use App\Imports\DataImport;
use App\Imports\OrderItemsImport;
use App\Imports\OrdersImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class DataSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds data from xlsx file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dataFile = database_path('data.xlsx');

        if (! file_exists($dataFile)) {
            $this->error("File [{$dataFile}] does not exist and can therefore not be imported.");

            return;
        }
        Excel::import(new DataImport, $dataFile);
        $this->info('Data imported successfully');

        $ordersFile = database_path('orders.csv');

        if (! file_exists($ordersFile)) {
            $this->error("File [{$ordersFile}] does not exist and can therefore not be imported.");

            return;
        }
        Excel::import(new OrdersImport, $ordersFile);
        $this->info('Orders imported successfully');

        $orderItemsFile = database_path('order_items.csv');

        if (! file_exists($orderItemsFile)) {
            $this->error("File [{$orderItemsFile}] does not exist and can therefore not be imported.");

            return;
        }

        Excel::import(new OrderItemsImport, $orderItemsFile);
        $this->info('Order items imported successfully');
    }
}
