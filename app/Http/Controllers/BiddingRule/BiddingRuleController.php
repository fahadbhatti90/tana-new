<?php

namespace App\Http\Controllers\BiddingRule;

use App\Http\Controllers\Controller;
use App\Mail\biddingRuleCreationMail;
use App\Model\Ams\BiddingRule\BiddingRule;
use App\Model\Ams\BiddingRule\PreSetRule;
use App\Model\Ams\Campaign;
use App\Model\Ams\Portfolio;
use App\Model\Ams\Profile;
use App\Model\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BiddingRuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('userPermission:1,1')->only(['index', 'getPortfolioOrCampaignList', 'getPreSetPortfolioOrCampaignList', 'getPreSetRules', 'getPreSetRuleInfo']);
        $this->middleware('userPermission:2,1')->only(['storePreSetRule', 'storeRule', 'setRuleStatus']);
        $this->middleware('userPermission:3,1')->only(['show', 'updateRule']);
        $this->middleware('userPermission:4,1')->only(['destroyRule']);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $profile_ids = array();
            $profilesData = auth()->user()->getUserBrandProfile();
            foreach ($profilesData as $profileData) {
                array_push($profile_ids, $profileData->id . "|" . $profileData->profile_id);
            } // end foreach
            $data = BiddingRule::where('fk_user_id', auth()->user()->user_id)->whereIn('profile_id', $profile_ids)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rule_name', function ($data) {
                    return ucwords($data->rule_name);
                })
                ->addColumn('rule_select_type', function ($data) {
                    return ucwords($data->rule_select_type);
                })
                ->addColumn('pre_set_rule_name', function ($data) {
                    if ($data->fk_pre_set_rule_id != 0) {
                        $preset_rule = PreSetRule::findOrFail($data->fk_pre_set_rule_id);
                        return $preset_rule->preset_name;
                    } else {
                        return 'NA';
                    } // end else
                })
                ->addColumn('look_back', function ($data) {
                    return ucwords(str_replace("_", " ", $data->look_back_period));
                })
                ->addColumn('frequency', function ($data) {
                    return ucwords(str_replace("_", " ", $data->frequency));
                })
                ->addColumn('statement', function ($data) {
                    return $data->getStatment();
                })
                ->addColumn('executed_at', function ($data) {
                    $last_execution_time = $data->getCronInfo()->get()->first()->last_execution_time;
                    if ($last_execution_time == '') {
                        return "-";
                    } // end if
                    return date('M, d Y h:i A', strtotime($last_execution_time));
                })
                ->addColumn('status', function ($data) {
                    $button = '<span class="badge badge-danger badge-pill float-right mr-2 test ">Paused</span>';
                    if ($data->is_active == 1) {
                        $button = '<span class="badge badge-success badge-pill float-right mr-2 test ">Resumed</span>';
                    } // end if
                    return $button;
                })
                ->addColumn('included', function ($data) {
                    $select_type_list = explode(',', $data->rule_select_type_value);
                    $content = "<ol class='pl-1'>";
                    if ($data->rule_select_type == 'campaign') {
                        $title = $data->rule_select_type;
                        foreach ($select_type_list as $campaign_info) {
                            $campaign_name = Campaign::where('campaign_id', $campaign_info)->get()->first()->name;
                            $content .= "<li>" . ($campaign_name) . "</li>";
                        } // end foreach
                    } else {
                        $title = $data->rule_select_type . " and its campaigns";
                        foreach ($select_type_list as $portfolio_info) {
                            $portfolio = Portfolio::where('portfolios_id', $portfolio_info)->get()->first();
                            $content .= "<li>" . ($portfolio->portfolios_name);
                            $content .= "<ul class='pl-1'>";
                            foreach ($portfolio->getCampaign as $campaign_info) {
                                $content .= "<li>" . ($campaign_info->name) . "</li>";
                            }
                            $content .= "</ul>";
                            $content .= "</li>";
                        } // end foreach
                    } // end else
                    $content .= "</ol>";

                    return '<label class="dataList show-popover badge badge-pill badge-warning waves-effect waves-light"
                                   data-toggle="popover"
                                   data-html="true"
                                   data-content="' . $content . '"
                                   data-trigger="click"
                                   data-original-title="List of ' . $title . '"
                                  >
                                List
                            </label>';
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (checkUserPermission(array(1), 3)) {
                        $button = '<button type="button" name="status" id="' . $data->id . '" title="Resume Rule" value="1" class="status btn-icon btn btn-warning btn-round btn-sm waves-effect waves-light"><i class="feather icon-play"></i> </button>';
                        if ($data->is_active == 1) {
                            $button = '<button type="button" name="status" id="' . $data->id . '" title="Pause Rule"  value="0" class="status btn-icon btn btn-secondary btn-round btn-sm waves-effect waves-light"><i class="feather icon-pause"></i> </button>';
                        } // end if
                        $button .= ' <button type="button"  name="editRule" id="' . $data->id . '" title="Edit Rule Information" value="' . $data->id . '" class="editRule btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    } // end if
                    if (checkUserPermission(array(1), 4)) {
                        $button .= ' <button type="button"  name="deleteRule" id="' . $data->id . '" title="Delete Rule" value="' . $data->id . '" class="deleteRule btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    } // end if
                    return $button;
                })
                ->rawColumns(['included', 'status', 'action'])
                ->make(true);
        } // end if

        $profile = auth()->user()->getUserBrandProfile();
        $preset_rules = PreSetRule::orderBy('preset_name', 'asc')->get();
        return view('biddingRule/index')
            ->with('profiles', $profile)
            ->with('preset_rules', $preset_rules);
    } // end function

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $rule_info = BiddingRule::findOrFail($id);

        $data = array();

        $data['id'] = $rule_info->id;
        $data['rule_name'] = $rule_info->rule_name;

        $data['profile'] = $rule_info->profile_id;
        $data['profiles'] = $profile = auth()->user()->getUserBrandProfile();

        $data['rule_ad_type'] = $rule_info->rule_ad_type;

        $data['rule_select_type'] = $rule_info->rule_select_type;
        $data['rule_select_type_value'] = explode(',', $rule_info->rule_select_type_value);

        $profile_info = explode('|', $data['profile']);

        $profile = Profile::where('profile_id', $profile_info[1])->get()->first();
        $select_type = $profile->getCampaign()->where('type', $data['rule_ad_type'])->get();
        if ($data['rule_select_type'] == 'portfolio') {
            $select_type = $profile->getPortfolio()->get();
        } // end if
        $data['select_types'] = $select_type;

        $data['pre_set_rules'] = PreSetRule::orderBy('preset_name', 'asc')->get();

        $data['fk_pre_set_rule_id'] = $rule_info->fk_pre_set_rule_id;
        $data['look_back_period'] = $rule_info->look_back_period . "|" . $rule_info->look_back_period_days;
        $data['frequency'] = $rule_info->frequency;

        $data['statement_metric'] = $rule_info->metric;
        $data['statement_condition'] = $rule_info->condition;
        $data['statement_value'] = $rule_info->integer_values;

        if ($rule_info->and_or != "NA") {

            $metric = explode(',', $rule_info->metric);
            $condition = explode(',', $rule_info->condition);
            $value = explode(',', $rule_info->integer_values);

            $data['statement_metric'] = $metric[0];
            $data['statement_condition'] = $condition[0];
            $data['statement_value'] = $value[0];

            $data['and_or'] = $rule_info->and_or;

            $data['statement2_metric'] = $metric[1];
            $data['statement2_condition'] = $condition[1];
            $data['statement2_value'] = $value[1];
        } // end if

        $data['bid_cpc_type'] = $rule_info->bid_cpc_type;
        $data['then_clause'] = $rule_info->then_clause;
        $data['bid_by_type'] = $rule_info->bid_by_type;
        $data['bid_by_value'] = $rule_info->bid_by_value;

        $data['cc_emails'] = explode(',', $rule_info->cc_emails);

        $rules = BiddingRule::where('fk_user_id', auth()->user()->user_id)->get();
        $emailList = array();
        foreach ($rules as $rule) {
            $list = explode(",", $rule->cc_emails);
            foreach ($list as $email) {
                if (!in_array($email, $emailList)) {
                    array_push($emailList, $email);
                } // end if
            } // end foreach
        } // end foreach

        return response()->json([
            'rule_info' => $data,
            'emailList' => $emailList,
        ]);
    } // end function

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPortfolioOrCampaignList(Request $request)
    {
        $rules = array(
            'profile' => ['required'],
            'rule_ad_type' => ['required', 'in:SP,SB,SD'],
            'rule_select_type' => ['required', 'in:portfolio,campaign'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $profile_info = explode('|', $request['profile']);
        $profile = Profile::where('profile_id', $profile_info[1])->get()->first();

        $select_type = $profile->getCampaign()->where('type', $request['rule_ad_type'])->get();
        if ($request['rule_select_type'] == 'portfolio') {
            $select_type = $profile->getPortfolio()->get();
        } // end if
        return response()->json(['select_type' => $select_type]);
    } // end function

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOldRuleList(Request $request)
    {
        $rules = array(
            'profile' => ['required'],
            'rule_ad_type' => ['required', 'in:SP,SB,SD'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $data = BiddingRule::where('fk_user_id', auth()->user()->user_id)
            ->where('profile_id', $request['profile'])
            ->where('rule_ad_type', $request['rule_ad_type'])
            ->get();

        return response()->json([
            'old_rule_list' => $data,
        ]);
    } // end function

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPreSetPortfolioOrCampaignList(Request $request)
    {
        $rules = array(
            'old_rule' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $oldRuleInfo = BiddingRule::findOrFail($request['old_rule']);

        if (isset($oldRuleInfo->rule_name) && $oldRuleInfo->fk_user_id == Auth::user()->user_id) {
            $profile_info = explode('|', $oldRuleInfo->profile_id);
            $profile = Profile::where('profile_id', $profile_info[1])->get()->first();

            $rule_ad_type = $oldRuleInfo->rule_ad_type;
            $rule_select_type = $oldRuleInfo->rule_select_type;

            $rule_select_type_value = explode(',', $oldRuleInfo->rule_select_type_value);

            if ($rule_select_type == 'portfolio') {
                $select_type = $profile->getPortfolio()->get();
            } else {
                $select_type = $profile->getCampaign()->where('type', $rule_ad_type)->get();
            } // end else

            return response()->json([
                'rule_select_type' => $rule_select_type,
                'select_type' => $select_type,
                'rule_select_type_value' => $rule_select_type_value,
            ]);
        } else {
            return response()->json(['error' => "Invalid rule found for preset portfolio/campaign"]);
        } // end else
    } // end function

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storePreSetRule(Request $request)
    {
        $rules = array(
            'rule_name' => ['required'],

            'look_back_period' => ['required', 'in:last_7_days|7,last_14_days|14,last_21_days|21,last_1_month|30,last_2_months|60'],
            'frequency' => ['required', 'in:once_per_day,every_other_day,once_per_week,once_per_month'],

            'statement_metric' => ['required', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'statement_condition' => ['required', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'statement_value' => ['required'],

            'add_more_exist' => ['required', 'in:1,0'],

            'rule_select2' => ['required_if:add_more_exist,==,1', 'in:and,or'],

            'statement2_metric' => ['required_if:add_more_exist,==,1', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'statement2_condition' => ['required_if:add_more_exist,==,1', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'statement2_value' => ['required_if:add_more_exist,==,1'],

            'bid_cpc_type' => ['required', 'in:bid,cpc'],
            'bid' => ['required', 'in:raise,lower'],
            'bid_by_type' => ['required', 'in:percent,dollar'],
            'bid_by_value' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $look_back_period_info = explode('|', $request['look_back_period']);

        $form_data = array(
            'preset_name' => $request['rule_name'],
            'look_back_period' => $look_back_period_info[0],
            'look_back_period_days' => $look_back_period_info[1],
            'frequency' => $request['frequency'],

            'metric' => isset($request['statement2_metric']) ? $request['statement_metric'] . "," . $request['statement2_metric'] : $request['statement_metric'],
            'condition' => isset($request['statement2_condition']) ? $request['statement_condition'] . "," . $request['statement2_condition'] : $request['statement_condition'],
            'integer_values' => isset($request['statement2_value']) ? $request['statement_value'] . "," . $request['statement2_value'] : $request['statement_value'],

            'and_or' => isset($request['statement2_metric']) ? $request['rule_select2'] : 'NA',

            'bid_cpc_type' => $request['bid_cpc_type'],
            'then_clause' => $request['bid'],
            'bid_by_type' => $request['bid_by_type'],
            'bid_by_value' => $request['bid_by_value'],
        );

        PreSetRule::create($form_data);

        return response()->json([
            'success' => 'Preset rule is added successfully.',
        ]);
    } // end function

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeRule(Request $request)
    {

        $rules = array(
            'rule_name' => ['required'],
            'profile' => ['required'],
            'rule_ad_type' => ['required', 'in:SP,SB,SD'],
            'rule_select_type' => ['required', 'in:portfolio,campaign'],
            'rule_select_type_value' => ['required', 'array', 'min:1'],

            'look_back_period' => ['required', 'in:last_7_days|7,last_14_days|14,last_21_days|21,last_1_month|30,last_2_months|60'],
            'frequency' => ['required', 'in:once_per_day,every_other_day,once_per_week,once_per_month'],

            'statement_metric' => ['required', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'statement_condition' => ['required', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'statement_value' => ['required'],

            'add_more_exist' => ['required', 'in:1,0'],

            'rule_select2' => ['required_if:add_more_exist,==,1', 'in:and,or'],

            'statement2_metric' => ['required_if:add_more_exist,==,1', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'statement2_condition' => ['required_if:add_more_exist,==,1', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'statement2_value' => ['required_if:add_more_exist,==,1'],

            'bid_cpc_type' => ['required', 'in:bid,cpc'],
            'bid' => ['required', 'in:raise,lower'],
            'bid_by_type' => ['required', 'in:percent,dollar'],
            'bid_by_value' => ['required'],

            'cc_emails' => ['array'],
            'cc_emails.*' => ['email', 'distinct'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $look_back_period_info = explode('|', $request['look_back_period']);

        $form_data = array(
            'fk_user_id' => Auth::user()->user_id,
            'rule_name' => $request['rule_name'],
            'profile_id' => $request['profile'],
            'rule_ad_type' => $request['rule_ad_type'],
            'rule_select_type' => $request['rule_select_type'],
            'rule_select_type_value' => implode(',', $request['rule_select_type_value']),


            'fk_pre_set_rule_id' => isset($request['pre_set_rule']) ? $request['pre_set_rule'] : "0",
            'look_back_period' => $look_back_period_info[0],
            'look_back_period_days' => $look_back_period_info[1],
            'frequency' => $request['frequency'],

            'metric' => isset($request['statement2_metric']) ? $request['statement_metric'] . "," . $request['statement2_metric'] : $request['statement_metric'],
            'condition' => isset($request['statement2_condition']) ? $request['statement_condition'] . "," . $request['statement2_condition'] : $request['statement_condition'],
            'integer_values' => isset($request['statement2_value']) ? $request['statement_value'] . "," . $request['statement2_value'] : $request['statement_value'],

            'and_or' => isset($request['statement2_metric']) ? $request['rule_select2'] : 'NA',

            'bid_cpc_type' => $request['bid_cpc_type'],
            'then_clause' => $request['bid'],
            'bid_by_type' => $request['bid_by_type'],
            'bid_by_value' => $request['bid_by_value'],

            'cc_emails' => isset($request['cc_emails']) ? implode(',', $request['cc_emails']) : "",
        );

        $biddingRuleData = BiddingRule::create($form_data);

        $user = User::findorFail(Auth::user()->user_id);
        $cc_mail_list = explode(',', $form_data['cc_emails']);
        if (sizeof($cc_mail_list) == 1 && $cc_mail_list[0] == "") {
            Mail::to($user->email)
                ->send(new biddingRuleCreationMail($user->username, $biddingRuleData));
        } else {
            Mail::to($user->email)
                ->cc($cc_mail_list)
                ->send(new biddingRuleCreationMail($user->username, $biddingRuleData));
        } // end else

        return response()->json([
            'success' => 'Rule is added successfully.',
        ]);
    } // end function


    /**
     * get list of emails in bidding rule.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEmailList(Request $request)
    {
        $rules = BiddingRule::where('fk_user_id', auth()->user()->user_id)->get();
        $emailList = array();
        foreach ($rules as $rule) {
            $list = explode(",", $rule->cc_emails);
            foreach ($list as $email) {
                if (!in_array($email, $emailList)) {
                    array_push($emailList, $email);
                }
            }
        }
        return response()->json([
            'emailList' => $emailList,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateRule(Request $request, $id)
    {
        $rules = array(
            'edit_rule_name' => ['required'],
            'edit_profile' => ['required'],
            'edit_rule_ad_type' => ['required', 'in:SP,SB,SD'],
            'edit_rule_select_type' => ['required', 'in:portfolio,campaign'],
            'edit_rule_select_type_value' => ['required', 'array', 'min:1'],

            'edit_look_back_period' => ['required', 'in:last_7_days|7,last_14_days|14,last_21_days|21,last_1_month|30,last_2_months|60'],
            'edit_frequency' => ['required', 'in:once_per_day,every_other_day,once_per_week,once_per_month'],

            'edit_statement_metric' => ['required', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'edit_statement_condition' => ['required', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'edit_statement_value' => ['required'],

            'edit_add_more_exist' => ['required', 'in:1,0'],

            'edit_rule_select2' => ['required_if:edit_add_more_exist,==,1', 'in:and,or'],

            'edit_statement2_metric' => ['required_if:edit_add_more_exist,==,1', 'in:impressions,clicks,cost,revenue,ROAS,ACOS,CPC,CPA,bid'],
            'edit_statement2_condition' => ['required_if:edit_add_more_exist,==,1', 'in:greater_than,less_than,greater_than_equal_to,less_than_equal_to,equal_to'],
            'edit_statement2_value' => ['required_if:edit_add_more_exist,==,1'],

            'edit_bid_cpc_type' => ['required', 'in:bid,cpc'],
            'edit_bid' => ['required', 'in:raise,lower'],
            'edit_bid_by_type' => ['required', 'in:percent,dollar'],
            'edit_bid_by_value' => ['required'],

            'edit_cc_emails' => ['array'],
            'edit_cc_emails.*' => ['email', 'distinct'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $look_back_period_info = explode('|', $request['edit_look_back_period']);

        $form_data = array(
            'fk_user_id' => Auth::user()->user_id,
            'rule_name' => $request['edit_rule_name'],
            'profile_id' => $request['edit_profile'],
            'rule_ad_type' => $request['edit_rule_ad_type'],
            'rule_select_type' => $request['edit_rule_select_type'],
            'rule_select_type_value' => implode(',', $request['edit_rule_select_type_value']),


            'fk_pre_set_rule_id' => isset($request['edit_pre_set_rule']) ? $request['edit_pre_set_rule'] : "0",
            'look_back_period' => $look_back_period_info[0],
            'look_back_period_days' => $look_back_period_info[1],
            'frequency' => $request['edit_frequency'],

            'metric' => isset($request['edit_statement2_metric']) ? $request['edit_statement_metric'] . "," . $request['edit_statement2_metric'] : $request['edit_statement_metric'],
            'condition' => isset($request['edit_statement2_condition']) ? $request['edit_statement_condition'] . "," . $request['edit_statement2_condition'] : $request['edit_statement_condition'],
            'integer_values' => isset($request['edit_statement2_value']) ? $request['edit_statement_value'] . "," . $request['edit_statement2_value'] : $request['edit_statement_value'],

            'and_or' => isset($request['edit_statement2_metric']) ? $request['edit_rule_select2'] : 'NA',

            'bid_cpc_type' => $request['edit_bid_cpc_type'],
            'then_clause' => $request['edit_bid'],
            'bid_by_type' => $request['edit_bid_by_type'],
            'bid_by_value' => $request['edit_bid_by_value'],

            'cc_emails' => isset($request['edit_cc_emails']) ? implode(',', $request['edit_cc_emails']) : "",
        );

        switch ($request['edit_frequency']) {
            case "once_per_day":
                $cronFrequency = 1;
                break;
            case "every_other_day":
                $cronFrequency = 2;
                break;
            case "once_per_week":
                $cronFrequency = 7;
                break;
            case "once_per_month":
                $cronFrequency = 30;
                break;
            default:
                $cronFrequency = 1;
        } // end switch

        $rule = BiddingRule::findOrFail($id);

        $rule->getCampaignsInfo()->delete();
        $rule->update($form_data);
        $rule->getCronInfo()->update([
            'last_run' => date('Y-m-d H:i:s', strtotime('-' . $cronFrequency . ' day', time())),
            'next_run' => date('Y-m-d H:i:s'),
            'current_run' => date('Y-m-d H:i:s'),
            'rule_ad_type' => $request['edit_rule_ad_type'],
            'look_back_period' => $look_back_period_info[0],
            'look_back_period_days' => $look_back_period_info[1],
            'frequency' => $request['edit_frequency'],
            'frequency_days' => $cronFrequency,
        ]);

        return response()->json([
            'success' => 'Rule is successfully updated.',
        ]);
    } // end function

    /**
     * Delete the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroyRule($id)
    {
        $rule_info = BiddingRule::findOrFail($id);
        $rule_info->getCampaignsInfo()->delete();
        $rule_info->getCronInfo()->update(["is_active" => 0]);
        $rule_info->delete();
        return response()->json([
            'success' => 'Rule is successfully deleted.',
        ]);
    } // end function

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */
    public function getPreSetRules()
    {
        $preset_rule = PreSetRule::orderBy('preset_name', 'asc')->get();
        return response()->json(['preset_rule' => $preset_rule]);
    } // end function

    /**
     * Get a pre set rule info form storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPreSetRuleInfo(Request $request)
    {
        $rules = array(
            'pre_set_rule' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $preset_rule = PreSetRule::findorfail($request['pre_set_rule']);
        return response()->json(['preset_rule' => $preset_rule]);
    } // end function

    /**
     * Get a pre set rule info form storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function setRuleStatus(Request $request, $id)
    {
        $rules = array(
            'is_active' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        } // end if

        $rule_info = BiddingRule::findOrFail($id);

        $rule_info->is_active = $request['is_active'];
        $rule_info->save();
        $cronFrequency = 1;
        switch ($rule_info->frequency) {
            case "once_per_day":
                $cronFrequency = 1;
                break;
            case "every_other_day":
                $cronFrequency = 2;
                break;
            case "once_per_week":
                $cronFrequency = 7;
                break;
            case "once_per_month":
                $cronFrequency = 30;
                break;
            default:
                $cronFrequency = 1;
        } // end switch

        $rule_info->getCronInfo()->update([
            'frequency_days' => $cronFrequency,
            'last_run' => date('Y-m-d H:i:s', strtotime('-' . $cronFrequency . ' day', time())),
            'next_run' => date('Y-m-d H:i:s'),
            'current_run' => date('Y-m-d H:i:s'),
            'run_status' => '0',
            "is_active" => $request['is_active'],
        ]);

        return response()->json(['success' => 'Rule status is updated']);
    } // end function

} // end class
