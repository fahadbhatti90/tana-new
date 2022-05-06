<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #000000; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">
<style>
    .paragraph {
        box-sizing: border-box !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol' !important;
        position: relative !important;
        font-size: 16px !important;
        line-height: 1.5em !important;
        margin-top: 0 !important;
        text-align: left !important;
        padding: 1px !important;
    }

    .body_table, .body_td, .body_th {
        border: 1px solid #ddd !important;
        text-align: center !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
    }

    .body_table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    .body_th, .body_td {
        padding: 1px !important;
    }

    @media  only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media  only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position: relative;-premailer-cellpadding: 0;-premailer-cellspacing: 0;-premailer-width: 100%;background-color: #f4f4f4;margin: 0;padding: 0;width: 100%;">
    <tbody><tr>
        <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                <tbody><tr>
                    <td class="header" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; padding: 25px 0; text-align: center;">
                        <a href="{{ route('home') }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #0FD4EE; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">CODE Informatics</a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position: relative;-premailer-cellpadding: 0;-premailer-cellspacing: 0;-premailer-width: 100%;background-color: #f4f4f4;border-bottom: 1px solid #f4f4f4;border-top: 1px solid #f4f4f4;margin: 0;padding: 0;width: 100%;">
                        <table class="inner-body" align="center" width="90%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';position: relative;-premailer-cellpadding: 0;-premailer-cellspacing: 0;-premailer-width: 570px;background-color: #ffffff;border-color: #e8e5ef;border-radius: 2px;border-width: 1px;box-shadow: 0 2px 0 rgba(0, 0, 0, 0), 2px 4px 0 rgb(244, 244, 244);margin: 0 auto;padding: 0;">
                            <!-- Body content -->
                            <tbody><tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                    <p class="paragraph">Hi,</p>
                                    <p class="paragraph">
                                        {{ $content }}
                                    </p>
                                    <p class="paragraph">
                                        Rule information<br/>
                                        Rule Profile ID: {{ ucfirst($profileInfo->profile_id) }}.<br/>
                                        Rule Profile Name: {{ ucfirst($profileInfo->name) }}.<br/>
                                        Rule Look Back Period: {{ ucfirst(str_replace("_", " ", $rule_info->look_back_period)) }}.<br/>
                                        Rule Look Back Range: {{ $look_back_range }}.<br/>
                                        Rule Frequency: {{ ucfirst(str_replace("_", " ", $rule_info->frequency)) }}.<br/>
                                        Rule Statement: {{$rule_info->getStatment() }}.<br/>
                                    </p>
                                    @if(sizeof($triggeredData) != 0)
                                        <p class="paragraph">Execution Changes</p>
                                            <table class="body_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="body_th">Campaign ID</th>
                                                        <th class="body_th">Campaign Name</th>
                                                        <th class="body_th">Ad Type Information</th>
                                                        <th class="body_th">Select Type</th>
                                                        <th class="body_th">Select Type Id</th>
                                                        <th class="body_th">Select Type Text</th>
                                                        <th class="body_th">Old Bid</th>
                                                        <th class="body_th">New Bid</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i =0; $i < sizeof($triggeredData); $i++)
                                                        <tr>
                                                            <td class="body_td">{{ $triggeredData[$i]['campaign_id'] }}</td>
                                                            <td class="body_td">{{ $triggeredData[$i]['campaign_name'] }}</td>
                                                            <td class="body_td">{{ $triggeredData[$i]['ad_type_information'] }}</td>
                                                            <td class="body_td">{{ isset($triggeredData[$i]['keyword_id']) ? 'Keyword' : 'Target' }}</td>
                                                            <td class="body_td">{{ $triggeredData[$i]['keyword_id'] ?? $triggeredData[$i]['target_id'] }}</td>

                                                            <td class="body_td">{{ $triggeredData[$i]['ad_type_text'] }}</td>
                                                            <td class="body_td">{{ $triggeredData[$i]['old_bid'] }}</td>
                                                            <td class="body_td">{{ $triggeredData[$i]['new_bid'] }}</td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                    @endif

                                    <p class="paragraph">
                                        <br>Thanks.<br>CODE Informatics
                                    </p>

                                    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
                                        <tbody><tr>
                                            <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                                <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;"><i>Note: This is an automated email. Please don’t reply to it.</i></p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tbody><tr>
                                <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px; text-align: center;">COPYRIGHT © {{ now()->year }} <a class="text-bold-800 darken-2" href="https://codeinformatics.com/" target="_blank">CODE Informatics,</a> All rights Reserved</p>
                                </td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                </tbody></table>
        </td>
    </tr>
    </tbody></table>


</body></html>
