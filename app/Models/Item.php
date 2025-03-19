<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'uid',
        'name',
        'description',
        'use_description',

        'quantity_on_hand',
        'quantity_committed',
        'quantity_on_order',
        'quantity_available',

        'average_cost',
        'current_value',
        'base_selling_price',
        'is_bought',
        'is_sold',
        'is_inventoried',
        'cost_of_sales_account',
        'asset_account',
        'location_details',
        'default_sell_location',
        'default_receive_location',
        'last_modified',
        'photo_uri',
        'uri',
        'row_version',

        'custom_list_1',
        'custom_list_2',
        'custom_list_3',
        'custom_field_1',
        'custom_field_2',
        'custom_field_3',

        'expense_account',
        'income_account',
        'buying_details',
        'selling_details',
    ];
}
