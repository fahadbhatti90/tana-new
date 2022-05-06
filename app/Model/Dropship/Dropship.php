<?php

namespace App\Model\Dropship;

use Illuminate\Database\Eloquent\Model;
use DB;

class Dropship extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_dropship';

    /**
     * insert Data for dropship Table.
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_dropship')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }
    /**
     * Fetch Data for src_sc_sale Table for 1st page verify.
     */
    public static function fetchData()
    {
        return DB::select("SELECT 
        `mgmt_vendor`.vendor_id						AS `vendor_id`	 
       ,`mgmt_vendor`.vendor_name						AS `vendor_name`
       ,COUNT( DISTINCT CAST(`src_dropship`.shipped_date AS DATE))		AS `no_of_days`
       ,MAX(`src_dropship`.shipped_date)					AS `max_shipped_date`
       ,COUNT(*)								AS `rows_count`
       ,CASE 
        WHEN SUM(`Dup`.`has_duplicate_rows`) IS NULL  THEN 'No'
        WHEN SUM(`Dup`.`has_duplicate_rows`) >= 1 THEN 'Yes'
        END									AS `Duplicate`
   FROM `src_dropship`
   
   LEFT JOIN(
   SELECT   Temp.fk_vendor_id 					AS `vendor_id`
       ,Temp.shipped_date					AS  `shipped_date`
       ,CASE WHEN Temp.Duplicate_Count > 1 THEN 1 
        ELSE 0 
        END 							AS `has_duplicate_rows`	
       ,COUNT(*)						AS `row_count`
   FROM (
   SELECT 
       `src_dropship`.`fk_vendor_id`
       ,`src_dropship`.`order_id`
       ,`src_dropship`.`order_status`
       ,`src_dropship`.`warehouse_code`
       ,`src_dropship`.`order_place_date`
       ,`src_dropship`.`required_ship_date`
       ,`src_dropship`.`item_cost`
       ,`src_dropship`.`sku`
       ,`src_dropship`.`asin`
       ,`src_dropship`.`item_title`
       ,`src_dropship`.`item_quantity`
       ,`src_dropship`.`tracking_id`
       ,CAST(`src_dropship`.`shipped_date`AS DATE) AS shipped_date
       ,COUNT(*) AS Duplicate_Count
   FROM `src_dropship`
   GROUP BY   1,2,3,4,5,6,7,8,9,10,11,12,13
   HAVING Duplicate_Count>1) AS `Temp`
   GROUP BY 1,2,3) AS `Dup`
   
   ON `src_dropship`.`fk_vendor_id` = `Dup`.`vendor_id`
    AND CAST(`src_dropship`.`shipped_date` AS DATE) = `Dup`.`shipped_date`
   
   INNER JOIN `mgmt_vendor`
   ON `src_dropship`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
   GROUP BY 1,2
   ORDER BY 2");
    }

    /**
     * Move Data from src_dropship To core by sp.
     * @param $id
     * @return array
     */
    public static function moveSelectedDataToCore($id)
    {
        return DB::select('call sp_etl_core_dropship(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_dropship To core by sp.
     */
    public static function moveDataToCore()
    {
        return DB::select('call sp_etl_core_dropship(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_dropship WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete duplicate record main verify page.
     */
    public static function removeDuplicateRecords()
    {
        return DB::select("DELETE FROM src_dropship
        WHERE  `row_id` IN (SELECT 
        src.`row_id`
        FROM (SELECT `row_id`
        ,ROW_NUMBER() OVER(PARTITION BY `fk_vendor_id`
        ,`order_id`
        ,`order_status`
        ,`warehouse_code`
        ,`order_place_date`
        ,`required_ship_date`
        ,`item_cost`
        ,`sku`
        ,`asin`
        ,`item_title`
        ,`item_quantity`
        ,`tracking_id`
        ,`shipped_date`) AS row_num
        FROM `src_dropship`) AS src
        WHERE src.row_num > 1)");
    }
}
