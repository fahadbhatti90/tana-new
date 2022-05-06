<?php

namespace App\Model;

use DB;

use Illuminate\Database\Eloquent\Model;

class VerifySales extends Model
{
    /**
     * Fetch Data for src_sales Table for 1st page verify.
     */
    public static function fetchData()
    {
        return DB::select("SELECT CONCAT(vendor_name,' ',region) AS `Vendor Name`,COUNT( DISTINCT sale_date) AS `No. of day(s)` ,MAX(sale_date) AS `Max Sale Date` ,COUNT(*) AS `Row(s) Count` ,vendor_id ,CASE WHEN `Dup`.`Duplicate` IS NULL 
         THEN 'No' ELSE `Dup`.`Duplicate` END AS `Duplicate` FROM `src_sale` LEFT JOIN( SELECT Temp.fk_vendor_id 	AS `Vendor Id` ,CASE WHEN Temp.Duplicate_Count > 1 THEN 'Yes' ELSE 'No' END AS `Duplicate`	
    FROM (
        SELECT 
        `src_sale`.`fk_vendor_id`,
        `src_sale`.`sale_date`,
        COUNT(*) AS Duplicate_Count
        FROM `src_sale`
        GROUP BY `src_sale`.`fk_vendor_id`,
        `src_sale`.`asin`,
        `src_sale`.`product_title`,
        `src_sale`.`subcategory`,
        `src_sale`.`category`,
        `src_sale`.`model_no`,
        `src_sale`.`shipped_cogs`,
        `src_sale`.`shipped_cogs_percentage_of_total`,
        `src_sale`.`shipped_cogs_prior_period`,
        `src_sale`.`shipped_cogs_last_year`,
        `src_sale`.`shipped_units`,
        `src_sale`.`shipped_units_percentage_of_total`,
        `src_sale`.`shipped_units_prior_period`,
        `src_sale`.`shipped_units_last_year`,
        `src_sale`.`customer_returns`,
        `src_sale`.`free_replacements`,
        `src_sale`.`average_sales_price`,
        `src_sale`.`average_sales_price_prior_period`,
        `src_sale`.`sale_date`
            HAVING Duplicate_Count >1) AS `Temp`
        GROUP BY 1,2) AS `Dup`
    ON `src_sale`.`fk_vendor_id` = `Dup`.`Vendor Id`
    INNER JOIN `mgmt_vendor`
    ON `src_sale`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    GROUP BY 1,5,6
    ORDER BY vendor_name", [1]);
    }
    /**
     * Fetch Data from src_sales Table for verify 2nd page.
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT CONCAT(vendor_name,' ',region) AS `Vendor_Name` ,sale_date AS `SaleDate`,COUNT(*) AS `Rows_Count`,vendor_id ,CASE WHEN `Dup`.`Duplicate` IS NULL THEN 'No' ELSE `Dup`.`Duplicate` END	AS `Duplicate` FROM `src_sale`
    LEFT JOIN(
        SELECT   Temp.fk_vendor_id 	AS `Vendor Id`
    ,Temp.sale_date		AS  `Sale Date`
    ,CASE WHEN Temp.Duplicate_Count > 1 THEN 'Yes' ELSE 'No' END AS `Duplicate`	
    ,COUNT(*)		AS `Row Count`
            FROM (
            SELECT 
    `src_sale`.`fk_vendor_id`,
    `src_sale`.`sale_date`,
    COUNT(*) AS Duplicate_Count
        FROM `src_sale`
        GROUP BY `src_sale`.`fk_vendor_id`,
    `src_sale`.`asin`,
    `src_sale`.`product_title`,
    `src_sale`.`subcategory`,
    `src_sale`.`category`,
    `src_sale`.`model_no`,
    `src_sale`.`shipped_cogs`,
    `src_sale`.`shipped_cogs_percentage_of_total`,
    `src_sale`.`shipped_cogs_prior_period`,
    `src_sale`.`shipped_cogs_last_year`,
    `src_sale`.`shipped_units`,
    `src_sale`.`shipped_units_percentage_of_total`,
    `src_sale`.`shipped_units_prior_period`,
    `src_sale`.`shipped_units_last_year`,
    `src_sale`.`customer_returns`,
    `src_sale`.`free_replacements`,
    `src_sale`.`average_sales_price`,
    `src_sale`.`average_sales_price_prior_period`,
    `src_sale`.`sale_date`
    HAVING Duplicate_Count >1) AS `Temp`
    GROUP BY 1,2,3) AS `Dup`

    ON `src_sale`.`fk_vendor_id` = `Dup`.`Vendor Id`
    AND `src_sale`.`sale_date` = `Dup`.`Sale Date`

    INNER JOIN `mgmt_vendor`
    ON `src_sale`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    WHERE `src_sale`.`fk_vendor_id` =$id
    GROUP BY 1,2,4
    ORDER BY vendor_name", [1]);
    }
    /**
     * Move Data from src_sales To core by sp.
     */
    public static function moveDataToCore($id)
    {
        return DB::select('call sp_etl_core_sale(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_sales To core by sp.
     */
    public static function moveSelectedDataToCore()
    {
        return DB::select('call sp_etl_core_sale(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_sale WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_sale WHERE fk_vendor_id='$id' and sale_date='$date'", [1]);
    }
}
