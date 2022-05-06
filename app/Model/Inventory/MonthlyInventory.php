<?php

namespace App\Model\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MonthlyInventory extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_inventory_monthly';

    /**
     * insert Data for src_inventory_monthly Table.
     * @param $data
     * @return
     * @throws \Throwable
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_inventory_monthly')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }

    /**
     * Fetch Data for src_inventory_monthly Table for 1st page verify.
     */
    public static function fetchData()
    {
        return DB::select("SELECT
        CONCAT(vendor_name,' ',region) AS `Vendor Name`
        ,COUNT( DISTINCT `end_date`) AS `No. of month(s)`
        ,MAX(`end_date`) AS `Max Date`
        ,COUNT(*) AS `Row(s) Count`
        ,vendor_id
        ,CASE WHEN `Dup`.`Duplicate` IS NULL
        THEN 'No'
        ELSE `Dup`.`Duplicate` END AS `Duplicate`
        FROM `src_inventory_monthly`
        LEFT JOIN
        ( SELECT
        Temp.fk_vendor_id AS `Vendor Id`
        ,CASE WHEN Temp.Duplicate_Count > 1
        THEN 'Yes'
        ELSE 'No' END AS `Duplicate`
        FROM (
        SELECT
        `src_inventory_monthly`.`fk_vendor_id`,
        `src_inventory_monthly`.`end_date`,
        COUNT(*) AS Duplicate_Count
        FROM `src_inventory_monthly`
        GROUP BY `src_inventory_monthly`.`fk_vendor_id`,
        `src_inventory_monthly`.`asin`,
        `src_inventory_monthly`.`end_date`
        HAVING Duplicate_Count >1) AS `Temp`
        GROUP BY 1,2) AS `Dup`
        ON `src_inventory_monthly`.`fk_vendor_id` = `Dup`.`Vendor Id`
        INNER JOIN `mgmt_vendor`
        ON `src_inventory_monthly`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
        GROUP BY 1,5,6
        ORDER BY vendor_name");
    }

    /**
     * Fetch Data from src_inventory_monthly Table for verify 2nd page.
     * @param $id
     * @return array
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT
        CONCAT(vendor_name,' ',region) AS `Vendor Name`
        ,end_date AS `Date`
        ,COUNT(*) AS `Row(s) Count`
        ,vendor_id
        ,CASE WHEN `Dup`.`Duplicate` IS NULL
        THEN 'No'
        ELSE `Dup`.`Duplicate` END AS `Duplicate`
        FROM `src_inventory_monthly`
        LEFT JOIN(
        SELECT Temp.fk_vendor_id AS `Vendor Id`
        ,Temp.end_date AS `Date`
        ,CASE WHEN Temp.Duplicate_Count > 1
        THEN 'Yes'
        ELSE 'No' END AS `Duplicate`
        ,COUNT(*) AS `Row Count`
        FROM (
        SELECT
        `src_inventory_monthly`.`fk_vendor_id`,
        `src_inventory_monthly`.`end_date`,
        COUNT(*) AS Duplicate_Count
        FROM `src_inventory_monthly`
        GROUP BY `src_inventory_monthly`.`fk_vendor_id`,
        `src_inventory_monthly`.`asin`,
        `src_inventory_monthly`.`end_date`
        HAVING Duplicate_Count >1) AS `Temp`
        GROUP BY 1,2,3) AS `Dup`
        
        ON `src_inventory_monthly`.`fk_vendor_id` = `Dup`.`Vendor Id`
        AND `src_inventory_monthly`.`end_date` = `Dup`.`Date`
        
        INNER JOIN `mgmt_vendor`
        ON `src_inventory_monthly`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
        WHERE `src_inventory_monthly`.`fk_vendor_id` = '" . $id . "'
        GROUP BY 1,2,4,5
        ORDER BY vendor_name");
    }

    /**
     * Move Data from src_inventory_monthly To core by sp.
     * @param $id
     * @return array
     */
    public static function moveSelectedDataToCore($id)
    {
        return DB::select('call sp_etl_core_inventory_monthly(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_inventory_monthly To core by sp.
     */
    public static function moveDataToCore()
    {
        return DB::select('call sp_etl_core_inventory_monthly(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_inventory_monthly WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_inventory_monthly WHERE fk_vendor_id='$id' and end_date='$date'", [1]);
    }
}
