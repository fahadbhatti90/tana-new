<?php

namespace App\Model\Traffic;

use Illuminate\Database\Eloquent\Model;
use DB;

class Traffic extends Model
{
    /**
     * The database table and primary Key used by the model.
     */
    protected $table = 'src_traffic';

    /**
     * insert Data for src_traffic Table.
     */
    public static function Insertion($data)
    {
        DB::beginTransaction();

        try {
            $qry = DB::table('src_traffic')->insert($data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return $qry;
    }
    /**
     * Fetch Data for src_traffic Table for 1st page verify.
     */
    public static function fetchData()
    {
        return \Illuminate\Support\Facades\DB::select("SELECT
	 CONCAT(vendor_name,' ',region) 	AS `Vendor Name`
	,COUNT( DISTINCT report_date) 		AS `No. of day(s)`
	,MAX(report_date) 			AS `Max Sale Date`
	,COUNT(*) 				AS `Row(s) Count`
	,vendor_id
	,CASE 	WHEN `Dup`.`Duplicate` IS NULL THEN 'No'
		ELSE `Dup`.`Duplicate` END 	AS `Duplicate`
FROM `src_traffic`
LEFT JOIN
(
	SELECT
		 Temp.fk_vendor_id 		AS `Vendor Id`
		,CASE 	WHEN Temp.Duplicate_Count > 1 THEN 'Yes'
			ELSE 'No' END 		AS `Duplicate`
	FROM (
        SELECT
		`src_traffic`.`fk_vendor_id`,
		`src_traffic`.`report_date`,
		COUNT(*) AS Duplicate_Count
		FROM `src_traffic`
		GROUP BY
		 `src_traffic`.`fk_vendor_id`
		,`src_traffic`.`asin`
		,`src_traffic`.`product_title`
		,`src_traffic`.`subcategory`
		,`src_traffic`.`category`
		,`src_traffic`.`model_number`
		,`src_traffic`.`glance_views`
		,`src_traffic`.`glance_views_%_of total`
		,`src_traffic`.`glance_view_prior_period`
		,`src_traffic`.`glance_view_last_year`
		,`src_traffic`.`conversion_rate`
		,`src_traffic`.`conversion_rate_prior_period`
		,`src_traffic`.`conversion_rate_last_year`
		,`src_traffic`.`unique_visitors_prior_period`
		,`src_traffic`.`unique_visitors_last_year`
		,`src_traffic`.`fast_track_gv`
		,`src_traffic`.`fast_track_gv_prior_period`
		,`src_traffic`.`fast_track_gv_last_year`

		,`src_traffic`.`report_date`
            HAVING Duplicate_Count >1) AS `Temp`
        GROUP BY 1,2) AS `Dup`
    ON `src_traffic`.`fk_vendor_id` = `Dup`.`Vendor Id`
    INNER JOIN `mgmt_vendor`
    ON `src_traffic`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    GROUP BY 1,5,6
    ORDER BY vendor_name");
    }

    /**
     * Fetch Data from src_traffic Table for verify 2nd page.
     * @param $id
     * @return array
     */
    public static function fetchDetailData($id)
    {
        return DB::select("SELECT
	 CONCAT(vendor_name,' ',region) 		AS `Vendor_Name`
	,report_date 					AS `Reported_Date`
	,COUNT(*) 					AS `Rows_Count`
	,vendor_id
	,CASE 	WHEN `Dup`.`Duplicate` IS NULL THEN 'No'
		ELSE `Dup`.`Duplicate` END		AS `Duplicate`
FROM `src_traffic`

LEFT JOIN(
	SELECT
		 Temp.fk_vendor_id 			AS `Vendor Id`
		,Temp.report_date			AS  `Report Date`
		,CASE 	WHEN Temp.Duplicate_Count > 1
				THEN 'Yes'
			ELSE 'No' END 			AS `Duplicate`
		,COUNT(*)				AS `Row Count`
	FROM (
		SELECT
		     `src_traffic`.`fk_vendor_id`
		    ,`src_traffic`.`report_date`
		    ,COUNT(*) 				AS Duplicate_Count
		FROM `src_traffic`
		GROUP BY
			 `src_traffic`.`fk_vendor_id`
		,`src_traffic`.`asin`
		,`src_traffic`.`product_title`
		,`src_traffic`.`subcategory`
		,`src_traffic`.`category`
		,`src_traffic`.`model_number`
		,`src_traffic`.`glance_views`
		,`src_traffic`.`glance_views_%_of total`
		,`src_traffic`.`glance_view_prior_period`
		,`src_traffic`.`glance_view_last_year`
		,`src_traffic`.`conversion_rate`
		,`src_traffic`.`conversion_rate_prior_period`
		,`src_traffic`.`conversion_rate_last_year`
		,`src_traffic`.`unique_visitors_prior_period`
		,`src_traffic`.`unique_visitors_last_year`
		,`src_traffic`.`fast_track_gv`
		,`src_traffic`.`fast_track_gv_prior_period`
		,`src_traffic`.`fast_track_gv_last_year`
		,`src_traffic`.`report_date`
    HAVING Duplicate_Count >1) AS `Temp`
    GROUP BY 1,2,3) AS `Dup`

    ON `src_traffic`.`fk_vendor_id` = `Dup`.`Vendor Id`
    AND `src_traffic`.`report_date` = `Dup`.`Report Date`

    INNER JOIN `mgmt_vendor`
    ON `src_traffic`.`fk_vendor_id` = `mgmt_vendor`.`vendor_id`
    WHERE `src_traffic`.`fk_vendor_id` ='" . $id . "'
    GROUP BY 1,2,4,5
    ORDER BY vendor_name");
    }

    /**
     * Move Data from src_traffic To core by sp.
     * @param $id
     * @return array
     */
    public static function moveSelectedDataToCore($id)
    {
        return DB::select('call sp_etl_core_traffic(?,?)', array($id, 0));
    }
    /**
     * Move Selected Data from src_traffic To core by sp.
     */
    public static function moveDataToCore()
    {
        return DB::select('call sp_etl_core_traffic(?,?)', array(0, 1));
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteAllRecord($id)
    {
        return DB::select("DELETE FROM src_traffic WHERE `fk_vendor_id` = '$id'", [1]);
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecord($id, $date)
    {
        return DB::select("DELETE FROM src_traffic WHERE fk_vendor_id='$id' and report_date='$date'", [1]);
    }
}
