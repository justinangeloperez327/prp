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
        $file = storage_path('app/product.xlsx');

        // import ProductCategories
        Excel::import(new ProductImport, $file);
        $this->info('Products imported successfully');
    }
}
