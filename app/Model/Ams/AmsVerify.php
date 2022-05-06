<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsVerify extends Model
{
    /**
     * Move Data from src_sales To core by sp.
     */
    public static function spAmsVerify($type, $subType, $vendor, $page)
    {
        return DB::select('call sp_ams_verify(?,?,?,?)', array($type, $subType, $vendor, $page));
    }
    /**
     *remove diplication sp.
     */
    public static function spDeleteDuplication($type, $subType)
    {
        return DB::select('call sp_ams_delete_duplication(?,?)', array($type, $subType));
    }

    /**
     * call etl
     *
     * @param $type
     * @param $updateArray
     */
    public static function moveToCore()
    {
        return DB::select('call sp_etl_core_ams_campaign()');
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteCampaignSpRecord($id)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sp_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sp_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')");
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteCampaignSbRecord($id)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sb_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sb_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')");
    }
    /**
     * To delete Verify page record.
     */
    public static function deleteCampaignSdRecord($id)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sd_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sd_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')");
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSp($id, $date)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sp_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sp_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')
        AND `reported_date` = '$date'");
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSb($id, $date)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sb_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sb_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')
        AND `reported_date` = '$date'");
    }
    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSd($id, $date)
    {
        return DB::select("DELETE FROM `tbl_ams_campaign_sd_report_data`
        WHERE `id` IN (SELECT a.id FROM (SELECT * FROM `tbl_ams_campaign_sd_report_data`) a INNER JOIN `tbl_ams_profiles`
        ON a.`profile_id`=`tbl_ams_profiles`.`profile_id`
        WHERE `tbl_ams_profiles`.`id`='$id')
        AND `reported_date` = '$date'");
    }
    public static function updateEmailStatus()
    {
        return DB::select("UPDATE `error_log_ams` SET sent = 1 WHERE DATE(captured_at) = CURRENT_DATE");
    }
}
