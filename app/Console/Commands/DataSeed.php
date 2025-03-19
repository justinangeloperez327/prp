<?php

namespace App\Console\Commands;

use App\Imports\DataImport;
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
        // xlsx file to upload data
        $file = database_path('data.xlsx');

        if (! file_exists($file)) {
            $this->error("File [{$file}] does not exist and can therefore not be imported.");

            return;
        }

        // import ProductCategories
        Excel::import(new DataImport, $file);
        $this->info('Data imported successfully');
    }
}
