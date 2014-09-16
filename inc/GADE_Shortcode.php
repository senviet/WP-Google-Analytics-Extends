<?php
/**
 * Project : WP-Google-Analytics-Extends
 * User: thuytien
 * Date: 9/16/2014
 * Time: 7:38 PM
 */

class GADE_Shortcode
{

    public static function init()
    {
        add_shortcode('ga_toppage', array(__CLASS__,'gatoppage'));
        add_shortcode('ga_pageview', array(__CLASS__,'gapageview'));
    }

    public static function gatoppage($atts)
    {
        require_once dirname(__FILE__) . "/../functions.php";
        $defaults = array(
            'url' => '',
            'accountID' =>'',
            'start_date' => '',
            'end_date'=>'',
            'count' => 5,
            'isShowViewCount' =>true,
            'linkExtras' => 'target="_blank"'
        );
        $atts = shortcode_atts($defaults, $atts);
        extract($atts);
        if(!$accountID)
        {
            $accountID =get_option('gad_account_id');
        }
        if(!$start_date && !$end_date) {
            $start_date_ts = time() - (60 * 60 * 24 * 30); // 30 days in the past
            $start_date = date('Y-m-d', $start_date_ts);
            $end_date = date('Y-m-d');
        }
        if(!$start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($end_date .' -30 day'));
        }
        if($start_date && !$end_date) {
            $end_date = date('Y-m-d', strtotime($start_date .' +30 day'));
        }
        return get_the_TopPage($accountID, $count, $start_date, $end_date, $isShowViewCount, $linkExtras);
    }
}