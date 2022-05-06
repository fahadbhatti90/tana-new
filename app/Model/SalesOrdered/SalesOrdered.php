<?php

namespace App\Model\SalesOrdered;

use Illuminate\Database\Eloquent\Model;
use DB;

class SalesOrdered extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_sale_manufacturing_ordered_rev';

    /**
     * insert Data for src_sale_manufacturing_ordered_rev Table.
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_sale_manufacturing_ordered_rev')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }
    /**
     * Fetch Data for src_sale_manufacturing_ordered_rev Table for 1st page verify.
     */
    public static function fetchData()
    {
        return \Illuminate\Support\Facades\DB::select("SELECT
	 CONCAT(vendor_name,' ',region) 	AS `Vendor Name`
	,COUNT( DISTINCT sale_date) 		AS `No. of day(s)`
	,MAX(sale_date) 			AS `Max Sale Date`
	,COUNT(*) 				AS `Row(s) Count`
	,vendor_id
	,CASE 	WHEN `Dup`.`Duplicate` IS NULL THEN 'No'
		ELSE `Dup`.`Duplicate` END 	AS `Duplicate`
FROM `src_sale_manufacturing_ordered_rev`
LEFT JOIN
(
	SELECT
		 Temp.fk_vendor_id 		AS `Vendor Id`
		,CASE 	WHEN Temp.Duplicate_Count > 1 THEN 'Yes'
			ELSE 'No' END 		AS `Duplicate`
	FROM (
        SELECT
		`src_sale_manufacturing_ordered_rev`.`fk_vendor_id`,
		`src_sale_manufacturing_ordered_rev`.`sale_date`,
		COUNT(*) AS Duplicate_Count
		FROM `src_sale_manufacturing_ordered_rev`
		GROUP BY
		 `src_sale_manufacturing_ordered_rev`.`fk_vendor_id`
		,`src_sale_manufacturing_ordered_rev`.`asin`
		,`src_sale_manufacturing_ordered_rev`.`product_title`
		,`src_sale_manufacturing_ordered_rev`.`subcategory`
		,`src_sale_manufacturing_ordered_rev`.`category`
		,`src_sale_manufacturing_ordered_rev`.`model_number`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_last_year`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_last_year`
		,`src_sale_manufacturing_ordered_rev`.`subcategory_sales_rank`
		,`src_sale_manufacturing_ordered_rev`.`avg_sale_price`
		,`src_sale_manufacturing_ordered_rev`.`avg_sale_price_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`glance_views`
		,`src_sale_manufacturing_ordered_rev`.`glance_views_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`change_in_GV_last_year`
		,`src_sale_manufacturing_ordered_rev`.`conversion_rate`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`LBB_price`
		,`src_sale_manufacturing_ordered_rev`.`sale_date`
            HAVING Duplicate_Count >1) AS `Temp`
        GROUP BY 1,2) AS `Dup`
    ON `src_sale_manufacturing_ordered_rev`.`fk_vendor_id` = `Dup`.`Vendor Id`
    INNER JOIN `mgmt_vendor`
    ON `src_sale_manufacturing_ordered_rev`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    GROUP BY 1,5,6
    ORDER BY vendor_name");
    }

    /**
     * Fetch Data from src_sale_manufacturing_ordered_rev Table for verify 2nd page.
     * @param $id
     * @return array
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT
	 CONCAT(vendor_name,' ',region) 		AS `Vendor_Name`
	,sale_date 					AS `SaleDate`
	,COUNT(*) 					AS `Rows_Count`
	,vendor_id
	,CASE 	WHEN `Dup`.`Duplicate` IS NULL THEN 'No'
		ELSE `Dup`.`Duplicate` END		AS `Duplicate`
FROM `src_sale_manufacturing_ordered_rev`

LEFT JOIN(
	SELECT
		 Temp.fk_vendor_id 			AS `Vendor Id`
		,Temp.sale_date				AS  `Sale Date`
		,CASE 	WHEN Temp.Duplicate_Count > 1
				THEN 'Yes'
			ELSE 'No' END 			AS `Duplicate`
		,COUNT(*)				AS `Row Count`
	FROM (
		SELECT
		     `src_sale_manufacturing_ordered_rev`.`fk_vendor_id`
		    ,`src_sale_manufacturing_ordered_rev`.`sale_date`
		    ,COUNT(*) 				AS Duplicate_Count
		FROM `src_sale_manufacturing_ordered_rev`
		GROUP BY
		 `src_sale_manufacturing_ordered_rev`.`fk_vendor_id`
		,`src_sale_manufacturing_ordered_rev`.`asin`
		,`src_sale_manufacturing_ordered_rev`.`product_title`
		,`src_sale_manufacturing_ordered_rev`.`subcategory`
		,`src_sale_manufacturing_ordered_rev`.`category`
		,`src_sale_manufacturing_ordered_rev`.`model_number`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`ordered_revenue_last_year`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`ordered_units_last_year`
		,`src_sale_manufacturing_ordered_rev`.`subcategory_sales_rank`
		,`src_sale_manufacturing_ordered_rev`.`avg_sale_price`
		,`src_sale_manufacturing_ordered_rev`.`avg_sale_price_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`glance_views`
		,`src_sale_manufacturing_ordered_rev`.`glance_views_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`change_in_GV_last_year`
		,`src_sale_manufacturing_ordered_rev`.`conversion_rate`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS_%_of_total`
		,`src_sale_manufacturing_ordered_rev`.`rep_OOS_prior_period`
		,`src_sale_manufacturing_ordered_rev`.`LBB_price`
		,`src_sale_manufacturing_ordered_rev`.`sale_date`
    HAVING Duplicate_Count >1) AS `Temp`
    GROUP BY 1,2,3) AS `Dup`

    ON `src_sale_manufacturing_ordered_rev`.`fk_vendor_id` = `Dup`.`Vendor Id`
    AND `src_sale_manufacturing_ordered_rev`.`sale_date` = `Dup`.`Sale Date`

    INNER JOIN `mgmt_vendor`
    ON `src_sale_manufacturing_ordered_rev`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    WHERE `src_sale_manufacturing_ordered_rev`.`fk_vendor_id` = '" . $id . "'
    GROUP BY 1,2,4,5
    ORDER BY vendor_name");
    }

    /**
     * Move Data from src_sale_manufacturing_ordered_rev To core by sp.
     * @param $id
     * @return array
     */
    public static function moveSelectedDataToCore($id)
    {
        return DB::select('call sp_etl_core_sale_manufacturing_ordered_rev(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_sale_manufacturing_ordered_rev To core by sp.
     */
    public static function moveDataToCore()
    {
        return DB::select('call sp_etl_core_sale_manufacturing_ordered_rev(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_sale_manufacturing_ordered_rev WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_sale_manufacturing_ordered_rev WHERE fk_vendor_id='$id' and sale_date='$date'", [1]);
    }
}
