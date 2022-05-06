<?php

namespace App\Model\purchaseOrder;

use DB;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /**
     * Fetch Vendors Table data.
     */
    public static function fetchVendors()
    {
        $qry = DB::table('mgmt_vendor')
            ->where('marketplace', '!=', '3P')
            ->where('is_active', '=', 1)->get();
        return $qry;
    }

    public static function checkVendors($fkVendorId)
    {
        $qry = DB::table('mgmt_vendor')
            ->where('marketplace', '!=', '3P')
            ->where('is_active', '=', 1)->get();
        return $qry;
    }
    /**
     * insert Data for src_sales Table.
     */
    public static function insertPoData($data)
    {
        DB::beginTransaction();

        try {
            foreach (array_chunk($data, 1000) as $t) {
                $qry = DB::table('src_purchase_order')->insert($t);
            }
            // $qry = DB::table('src_purchase_order')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }
    /**
     * fetch data Data for src_sales Table.
     */
    public static function fetchData()
    {
        return DB::select("SELECT
        `mgmt_vendor`.`vendor_id`	AS `Vendor_Id`
        ,CONCAT(`mgmt_vendor`.`vendor_name`,' ',`mgmt_vendor`.region) AS `vendor_name`
        ,COUNT( DISTINCT `src_purchase_order`.`ordered_on`)	AS `No. of day(s)`
        ,MAX(`src_purchase_order`.`ordered_on`)		AS `Max OrderOn Date`
        ,COUNT(*)	AS `Row(s) Count`
        ,IF(`Temp2`.`fk_vendor_id` IS NULL,'No','Yes')	AS `Duplicate`
    FROM  `src_purchase_order`
    LEFT JOIN (
        SELECT
            DISTINCT(`fk_vendor_id`)
        FROM(
            SELECT
                COUNT(*) AS Duplicate_Count
                ,`src_purchase_order`.`fk_vendor_id`
            FROM  `src_purchase_order`
            GROUP BY
                `src_purchase_order`.`fk_vendor_id`
                ,`src_purchase_order`.`po`
                ,`src_purchase_order`.`asin`
                ,`src_purchase_order`.`ordered_on`
            HAVING Duplicate_Count > 1 ) AS `Temp` ) AS `Temp2`
    ON `src_purchase_order`.`fk_vendor_id` = `Temp2`.`fk_vendor_id`
    INNER JOIN  `mgmt_vendor`
    ON `src_purchase_order`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    GROUP BY 1,6");
    }
    /**
     * Fetch Data from src_sales Table for verify 2nd page.
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT
        `vendor_id`
        ,CONCAT(vendor_name,' ',region)	AS `vendor_name` ,`T-1`.`ordered_on` AS `ordered_on_date` ,`T-1`.`row_count`	AS `Rows_Count`  ,IF(`T-2`.`duplicate_count` IS NULL,'No','Yes')	AS `Duplicate`
        FROM ( SELECT
            `fk_vendor_id`	AS `fk_vendor_id`
            ,`ordered_on`	AS `ordered_on`
            ,COUNT(*)		AS `row_count`
            FROM  `src_purchase_order`
            WHERE fk_vendor_id =$id /*Insert Vendor Id Here*/
            GROUP BY 1,2
            ORDER BY 2) AS `T-1`
        LEFT JOIN ( SELECT
             `ordered_on`
            ,`duplicate_count`
            FROM ( SELECT
                `ordered_on`		AS `ordered_on`
                ,COUNT(*)		AS `duplicate_count`
                FROM  `src_purchase_order`
                WHERE `fk_vendor_id` =$id /*Insert Vendor Id Here*/
                GROUP BY `po`, `asin`, `ordered_on`
                HAVING Duplicate_Count > 1 ) AS `T-3`
            GROUP BY 1, 2 ) AS `T-2`
        ON `T-1`.`ordered_on` = `T-2`.`ordered_on`
        INNER JOIN  `mgmt_vendor`
        ON `T-1`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`");
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_purchase_order WHERE fk_vendor_id='$id' and ordered_on='$date'", [1]);
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_purchase_order WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * Move Data from src_sales To core by sp.
     */
    public static function moveDataToCore($id)
    {
        return DB::select('call sp_etl_core_purchase_order(?)', array($id));
    }
}
