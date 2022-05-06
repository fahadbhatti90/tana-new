<?php

namespace App\Model\Ams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;


class Profile extends Model
{
    protected $table = 'tbl_ams_profiles';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    /**
     * @param $storeArray
     */
    public static function updateProfileRecords($storeArray)
    {
        // update all profile status
        DB::table('tbl_ams_profiles')->where('is_sandbox_profile', 0)->whereNotIn('id', $storeArray)->update(['is_active' => 0, 'last_update' => date('Y-m-d H:i:s')]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getTokenDetail()
    {
        return $this->belongsTo('App\Model\Ams\AuthToken', 'fk_access_token', 'id');
    }

    /**
     * @param $storeArray
     */
    public static function updateSandboxProfileRecords($storeArray)
    {
        // update all profile status
        DB::table('tbl_ams_profiles')->where('is_sandbox_profile', 1)->whereNotIn('id', $storeArray)->update(['is_active' => 0, 'last_update' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get the Assigned profile for this vendor.
     */
    public function vendor()
    {
        return $this->belongsToMany('App\Model\Vendors', 'mgmt_vendor_entity', 'fk_profile_id', 'fk_vendor_id');
    }

    public function getPortfolio()
    {
        return $this->hasMany('App\Model\Ams\Portfolio', 'profile_id', 'profile_id')->orderBy('portfolios_name', 'asc');
    }

    public function getCampaign()
    {
        return $this->hasMany('App\Model\Ams\Campaign', 'profile_id', 'profile_id')->orderBy('name', 'asc');
    }

    /**
     * This function is used to get SP Campaign Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPCampaignProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Campaign\SP\SPCampaignReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SP\SPCampaignReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SP\SPCampaignReportId', 'fk_profile_id', 'id');
    }

    public function getSPCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SP\SPCampaignReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SP\SPCampaignReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SD Campaign Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdCampaignProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Campaign\SD\SdCampaignReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\SdCampaignReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\SdCampaignReportId', 'fk_profile_id', 'id');
    }

    public function getSdCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\SdCampaignReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\SdCampaignReportLink', 'profile_id', 'profile_id');
    }
    /**
     * This function is used to get SD Campaign Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdAudiencesCampaignProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdAudiencesCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudiencesCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportId', 'fk_profile_id', 'id');
    }

    public function getSdAudiencesCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudiencesCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SD\Audiences\SdAudiencesCampaignReportLink', 'profile_id', 'profile_id');
    }


    /**
     * This function is used to get SP AdGroup Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPAdGroupProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\AdGroup\SP\SPAdGroupReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SP\SPAdGroupReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SP\SPAdGroupReportId', 'fk_profile_id', 'id');
    }

    public function getSPAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SP\SPAdGroupReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SP\SPAdGroupReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SD AdGroup Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdAdGroupProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\AdGroup\SD\SdAdGroupReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\SdAdGroupReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\SdAdGroupReportId', 'fk_profile_id', 'id');
    }

    public function getSdAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\SdAdGroupReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\SdAdGroupReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SD AdGroup Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdAudienceAdGroupProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\AdGroup\SD\Audiences\SdAudiencesAdGroupReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdAudienceAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\Audiences\SdAudiencesAdGroupReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudienceAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\Audiences\SdAudiencesAdGroupReportId', 'fk_profile_id', 'id');
    }

    public function getSdAudienceAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\Audiences\SdAudiencesAdGroupReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudienceAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SD\Audiences\SdAudiencesAdGroupReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP Keyword Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPKeywordProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Keyword\SP\SPkeywordReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPKeywordReportId()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SP\SPkeywordReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPKeywordReportId()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SP\SPkeywordReportId', 'fk_profile_id', 'id');
    }

    public function getSPKeywordReportLink()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SP\SPkeywordReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPKeywordReportLink()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SP\SPkeywordReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP Search Term Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPSearchTermProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\SearchTerm\SP\SPSearchTermReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPSearchTermReportId()
    {
        return $this->hasMany('App\Model\Ams\SearchTerm\SP\SPSearchTermReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPSearchTermReportId()
    {
        return $this->hasMany('App\Model\Ams\SearchTerm\SP\SPSearchTermReportId', 'fk_profile_id', 'id');
    }

    public function getSPSearchTermReportLink()
    {
        return $this->hasMany('App\Model\Ams\SearchTerm\SP\SPSearchTermReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPSearchTermReportLink()
    {
        return $this->hasMany('App\Model\Ams\SearchTerm\SP\SPSearchTermReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP ProductAds Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPProductAdsProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\ProductAds\SP\SPProductAdsReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SP\SPProductAdsReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SP\SPProductAdsReportId', 'fk_profile_id', 'id');
    }

    public function getSPProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SP\SPProductAdsReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SP\SPProductAdsReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP ProductAds Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdProductAdsProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\ProductAds\SD\SdProductAdsReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\SdProductAdsReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\SdProductAdsReportId', 'fk_profile_id', 'id');
    }

    public function getSdProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\SdProductAdsReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\SdProductAdsReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP ProductAds Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdAudiencesProductAdsProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\ProductAds\SD\Audiences\SdAudiencesProductAdsReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdAudiencesProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\Audiences\SdAudiencesProductAdsReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudiencesProductAdsReportId()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\Audiences\SdAudiencesProductAdsReportId', 'fk_profile_id', 'id');
    }

    public function getSdAudiencesProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\Audiences\SdAudiencesProductAdsReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdAudiencesProductAdsReportLink()
    {
        return $this->hasMany('App\Model\Ams\ProductAds\SD\Audiences\SdAudiencesProductAdsReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP Asin Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPAsinProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Asin\SP\SPAsinReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPAsinReportId()
    {
        return $this->hasMany('App\Model\Ams\Asin\SP\SPAsinReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPAsinReportId()
    {
        return $this->hasMany('App\Model\Ams\Asin\SP\SPAsinReportId', 'fk_profile_id', 'id');
    }

    public function getSPAsinReportLink()
    {
        return $this->hasMany('App\Model\Ams\Asin\SP\SPAsinReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPAsinReportLink()
    {
        return $this->hasMany('App\Model\Ams\Asin\SP\SPAsinReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SP Target Report ID with profile id
     * @return HasManyThrough
     */
    public function getSPTargetProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Target\SP\SPTargetReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSPTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SP\SPTargetReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SP\SPTargetReportId', 'fk_profile_id', 'id');
    }

    public function getSPTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SP\SPTargetReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSPTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SP\SPTargetReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SD Target Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdTargetProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Target\SD\SdTargetReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\SdTargetReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\SdTargetReportId', 'fk_profile_id', 'id');
    }

    public function getSdTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\SdTargetReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\SdTargetReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SD Target Report ID with profile id
     * @return HasManyThrough
     */
    public function getSdTargetAudienceProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Target\SD\Audience\SdTargetAudienceReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSdTargetAudienceReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\Audience\SdTargetAudienceReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdTargetAudienceReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\Audience\SdTargetAudienceReportId', 'fk_profile_id', 'id');
    }

    public function getSdTargetAudienceReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\Audience\SdTargetAudienceReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSdTargetAudienceReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SD\Audience\SdTargetAudienceReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SB Campaign Report ID with profile id
     * @return HasManyThrough
     */
    public function getSBCampaignProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Campaign\SB\SBCampaignReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSBCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SB\SBCampaignReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBCampaignReportId()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SB\SBCampaignReportId', 'fk_profile_id', 'id');
    }

    public function getSBCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SB\SBCampaignReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBCampaignReportLink()
    {
        return $this->hasMany('App\Model\Ams\Campaign\SB\SBCampaignReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SB AdGroup Report ID with profile id
     * @return HasManyThrough
     */
    public function getSBAdGroupProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\AdGroup\SB\SBAdGroupReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSBAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SB\SBAdGroupReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBAdGroupReportId()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SB\SBAdGroupReportId', 'fk_profile_id', 'id');
    }

    public function getSBAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SB\SBAdGroupReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBAdGroupReportLink()
    {
        return $this->hasMany('App\Model\Ams\AdGroup\SB\SBAdGroupReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SB Keyword Report ID with profile id
     * @return HasManyThrough
     */
    public function getSBKeywordProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Keyword\SB\SBKeywordReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSBKeywordReportId()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SB\SBKeywordReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBKeywordReportId()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SB\SBKeywordReportId', 'fk_profile_id', 'id');
    }

    public function getSBKeywordReportLink()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SB\SBKeywordReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBKeywordReportLink()
    {
        return $this->hasMany('App\Model\Ams\Keyword\SB\SBKeywordReportLink', 'profile_id', 'profile_id');
    }

    /**
     * This function is used to get SB Target Report ID with profile id
     * @return HasManyThrough
     */
    public function getSBTargetProfileList()
    {
        return $this->hasManyThrough(
            'App\Model\Ams\AuthToken',
            'App\Model\Ams\Target\SB\SBTargetReportId',
            'fk_profile_id',
            'id',
            'fk_access_token',
            'id'
        );
    }

    public function getSBTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SB\SBTargetReportId', 'fk_profile_id', 'id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBTargetReportId()
    {
        return $this->hasMany('App\Model\Ams\Target\SB\SBTargetReportId', 'fk_profile_id', 'id');
    }

    public function getSBTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SB\SBTargetReportLink', 'profile_id', 'profile_id')
            ->where('is_done', '=', '0');
    }

    public function getAllSBTargetReportLink()
    {
        return $this->hasMany('App\Model\Ams\Target\SB\SBTargetReportLink', 'profile_id', 'profile_id');
    }

    public function getUnassignedProfiles()
    {
        return DB::select('SELECT * FROM `tbl_ams_profiles` WHERE is_active = 1 AND id NOT IN ( SELECT fk_profile_id FROM `mgmt_vendor_entity`)');
    }
}
