<?php

namespace App\Model\SellerCentral;

use Illuminate\Database\Eloquent\Model;
use DB;

class SellerCentral extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_sc_sale';

    /**
     * insert Data for src_sc_3p_sales Table.
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_sc_sale')->insert($data);
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
        vendor_id	AS `vendor_id`	 
       ,vendor_name	AS `vendor_name`
       ,COUNT( DISTINCT sale_date)	AS `no_of_days`
       ,MAX(sale_date)	AS `max_sale_date`
       ,COUNT(*)  AS `rows_count`
       ,CASE 
        WHEN `Dup`.`Has Duplicate Row(s)?` IS NULL THEN 'No'
        ELSE `Dup`.`Has Duplicate Row(s)?`
        END							AS `Duplicate`
   FROM `src_sc_sale`
   LEFT JOIN(
   SELECT   Temp.fk_vendor_id 				AS `Vendor Id`
       ,Temp.sale_date					AS  `Sale Date`
       ,CASE WHEN Temp.Duplicate_Count > 1 THEN 'Yes' 
        ELSE 'No' 
        END 						AS `Has Duplicate Row(s)?`	
       ,COUNT(*)					AS `Row Count`
   FROM (
   SELECT 
       `src_sc_sale`.`fk_vendor_id`,
       `src_sc_sale`.`sale_date`,
       COUNT(*) AS Duplicate_Count
   FROM `src_sc_sale`
   GROUP BY `src_sc_sale`.`fk_vendor_id`, `src_sc_sale`.`sale_date`
   HAVING Duplicate_Count >1) AS `Temp`
   GROUP BY 1,2,3) AS `Dup`
   
   ON `src_sc_sale`.`fk_vendor_id` = `Dup`.`Vendor Id`
   
   INNER JOIN `mgmt_vendor`
   ON `src_sc_sale`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
   GROUP BY 1,2,6
   ORDER BY 2");
    }
    /**
     * Fetch Data from src_sc_sale Table for verify 2nd page.
     * @param $id
     * @return array
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT 
        vendor_name						AS `vendor_name`
       ,sale_date						AS `sale_date`
       ,COUNT(*)						AS `rows_count`
       ,CASE WHEN `Dup`.`Has Duplicate Row(s)?` IS NULL 
        THEN 'No' ELSE `Dup`.`Has Duplicate Row(s)?` END	AS `Duplicate`
   FROM `src_sc_sale`
   
   LEFT JOIN(
   SELECT   Temp.fk_vendor_id 	AS `Vendor Id`
       ,Temp.sale_date		AS  `Sale Date`
       ,CASE WHEN Temp.Duplicate_Count > 1 THEN 'Yes' ELSE 'No' END AS `Has Duplicate Row(s)?`	
       ,COUNT(*)		AS `Row Count`
   FROM (
   SELECT 
       `src_sc_sale`.`fk_vendor_id`,
       `src_sc_sale`.`sale_date`,
       COUNT(*) AS Duplicate_Count
   FROM `src_sc_sale`
   GROUP BY `src_sc_sale`.`fk_vendor_id`, `src_sc_sale`.`sale_date`
   HAVING Duplicate_Count >1) AS `Temp`
   GROUP BY 1,2,3) AS `Dup`
   
   ON `src_sc_sale`.`fk_vendor_id` = `Dup`.`Vendor Id`
   AND `src_sc_sale`.`sale_date` = `Dup`.`Sale Date`
   
   INNER JOIN `mgmt_vendor`
   ON `src_sc_sale`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
   WHERE `src_sc_sale`.`fk_vendor_id` ='" . $id . "' 
      GROUP BY 1,2,4
   ORDER BY vendor_name");
    }

    /**
     * Move Data from src_sc_sale To core by sp.
     * @param $id
     * @return array
     */
    public static function moveSelectedDataToCore($id)
    {
        return DB::select('call sp_etl_core_sc_sale(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_sc_sale To core by sp.
     */
    public static function moveDataToCore()
    {
        return DB::select('call sp_etl_core_sc_sale(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_sc_sale WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_sc_sale WHERE fk_vendor_id='$id' and sale_date='$date'", [1]);
    }
}
