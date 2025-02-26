<?php

namespace App\Console\Commands;


use App\Imports\ProductImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ProductSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-seed';

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
        // xlsx file to upload data
        $file = database_path('product.xlsx');

        if (!file_exists($file)) {
            $this->error("File [{$file}] does not exist and can therefore not be imported.");
            return;
        }

        // import ProductCategories
        Excel::import(new ProductImport, $file);
        $this->info('Products imported successfully');
    }
}
