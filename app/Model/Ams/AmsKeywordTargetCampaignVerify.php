<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmsKeywordTargetCampaignVerify extends Model
{
    /**
     * fetch data from sc tables.
     */
    public static function getVerifyRecord($type, $subtyp, $startDate, $endDate)
    {
        return DB::select('call sp_ams_verify(?,?,?,?,?,?)', array($type, $subtyp, 0, 'Main', $startDate, $endDate));
    }

    /**
     * Move Data from src_sales To core by sp.
     */
    public static function getDetailVerifyRecord($type, $subtyp, $startDate, $endDate, $profile_id)
    {
        return DB::select('call sp_ams_verify(?,?,?,?,?,?)', array($type, $subtyp, $profile_id, 'Detail', $startDate, $endDate));
    }

    /**
     * Move Data from src_sales To core by sp.
     */
    public static function getDashboardRecord($reportType)
    {
        if ($reportType == 'Keyword') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_bidding_rule_keyword`
            ORDER BY 2");
        }
        if ($reportType == 'Targets') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_bidding_rule_targets`
            ORDER BY 2");
        }
        if ($reportType == 'Campaing') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_campaign`
            ORDER BY 2");
        }
        if ($reportType == 'Targets_audience') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_bidding_rule_targets_audience`
            ORDER BY 2");
        }
        if ($reportType == 'Keyword_search_term') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_keyword_search_term`
            ORDER BY 2");
        }
        if ($reportType == 'Product_ads') {
            return DB::select("SELECT
            `profile_id`
            ,`profile_name`
            ,`report_type`
            ,`max_reported_date`
            ,`inserted_at`
            FROM `metadata_core_ams_product_ads`
            ORDER BY 2");
        }
    }

    /**
     * Move Data from src_sales To core by sp.
     */
    public static function moveToCore($type, $startDate, $endDate)
    {
        if ($type == 'Keyword') {
            return DB::select('call sp_etl_core_ams_bidding_rule_keyword(?,?)', array($startDate, $endDate));
        }
        if ($type == 'Targets') {
            return DB::select('call sp_etl_core_ams_bidding_rule_targets(?,?)', array($startDate, $endDate));
        }
        if ($type == 'Campaing') {
            return DB::select('call sp_etl_core_ams_campaign(?,?)', array($startDate, $endDate));
        }
        if ($type == 'Targets_audience') {
            return DB::select('call sp_etl_core_ams_bidding_rule_targets_audience(?,?)', array($startDate, $endDate));
        }
        if ($type == 'Keyword_search_term') {
            return DB::select('call sp_etl_core_ams_keyword_search_term(?,?)', array($startDate, $endDate));
        }
        if ($type == 'Product_ads') {
            return DB::select('call sp_etl_core_ams_product_ads(?,?)', array($startDate, $endDate));
        }
    }

    /**
     * Move Data from src_sales To core by sp.
     */
    public static function spDeleteDuplication($type, $subtype)
    {
        return DB::select('call sp_ams_delete_duplication(?,?)', array($type, $subtype));
    }

    /**
     * To delete Verify page record.
     */
    public static function deleteSpRecord($type, $id)
    {
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sp_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sp_report_data`  WHERE `profile_id` = '$id'");
        }
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sp_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Keyword_search_term') {
            return DB::select("DELETE FROM `tbl_ams_keyword_search_term_sp_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Product_ads') {
            return DB::select("DELETE FROM `tbl_ams_product_ads_sp_report_data` WHERE `profile_id` ='$id'");
        }
    }

    /**
     * To delete Verify page record.
     */
    public static function deleteSbRecord($type, $id)
    {
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sb_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sb_report_data`  WHERE `profile_id` = '$id'");
        }
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sb_report_data` WHERE `profile_id` ='$id'");
        }
    }

    /**
     * To delete Verify page record.
     */
    public static function deleteSdRecord($type, $id)
    {
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sd_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sd_report_data`  WHERE `profile_id` = '$id'");
        }
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sd_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Targets_audience') {
            return DB::select("DELETE FROM `tbl_ams_targets_sd_audiences_report_data` WHERE `profile_id` ='$id'");
        }
        if ($type == 'Product_ads') {
            return DB::select("DELETE FROM `tbl_ams_product_ads_sd_report_data` WHERE `profile_id` ='$id'");
        }
    }

    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSp($id, $date, $type)
    {
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sp_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sp_report_data`
            WHERE `profile_id` ='$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sp_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Keyword_search_term') {
            return DB::select("DELETE FROM `tbl_ams_keyword_search_term_sp_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Product_ads') {
            return DB::select("DELETE FROM `tbl_ams_product_ads_sp_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
    }

    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSb($id, $date, $type)
    {
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sb_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sb_report_data`
            WHERE `profile_id` ='$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sb_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
    }

    /**
     * To delete Verify 2nd page record.
     */
    public static function deleteSelectedRecordSd($id, $date, $type)
    {
        if ($type == 'Targets') {
            return DB::select("DELETE FROM `tbl_ams_targets_sd_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Campaing') {
            return DB::select("DELETE FROM `tbl_ams_campaign_sd_report_data`
            WHERE `profile_id` ='$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Keyword') {
            return DB::select("DELETE FROM `tbl_ams_keyword_sd_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Targets_audience') {
            return DB::select("DELETE FROM `tbl_ams_targets_sd_audiences_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
        if ($type == 'Product_ads') {
            return DB::select("DELETE FROM `tbl_ams_product_ads_sd_report_data`
            WHERE `profile_id` = '$id' AND `reported_date` = '$date'");
        }
    }

    /**
     * Generate log table for campaign, keyword, targets & target audience reports.
     *
     * @return array
     */
    public static function generateLogTable($reported_type)
    {
        switch ($reported_type) {
            case 'campaign';
                return DB::select('call sp_move_log_ams_campaign()');
            case 'keyword';
                return DB::select('call sp_move_log_ams_keyword()');
            case 'targets';
                return DB::select('call sp_move_log_ams_targets()');
            case 'audience';
                return DB::select('call sp_move_log_ams_targets_audiences()');
            case 'Keyword_search_term';
                return DB::select('call sp_move_log_ams_keyword_search_term()');
            case 'Product_ads';
                return DB::select('call sp_move_log_ams_product_ads()');
        } // end switch
    } // end function
}
