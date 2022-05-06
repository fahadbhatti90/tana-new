<?php

namespace App\Model;

use DB;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_sale_category';

    /**
     * insert Data for src_sales Table.
     */
    public static function Insertion($data)
    {
        return  DB::table('src_sale_category')->insert($data);
    }
}
