<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new ProductCategoriesSheetImport,
            1 => new ProductsSheetImport,
            2 => new ProductItemsSheetImport,
            3 => new CustomersSheetImport,
            4 => new ContactsSheetImport,
            5 => new DiscountsSheetImport,
        ];
    }
}
