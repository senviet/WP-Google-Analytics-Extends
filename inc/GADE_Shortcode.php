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
        add_shortcode('ga_toppage', array(__CLASS__, 'gatoppage'));
        add_shortcode('ga_pageview', array(__CLASS__, 'ga_pageview'));
        add_shortcode('ga_keyword', array(__CLASS__, 'ga_keyword'));

    }

    public static function gatoppage($atts)
    {
        require_once dirname(__FILE__) . "/../functions.php";
        $defaults = array(
            'accountID' => '',
            'start_date' => '',
            'end_date' => '',
            'count' => 5,
            'isShowViewCount' => true,
            'linkExtras' => 'target="_blank"'
        );
        $atts = shortcode_atts($defaults, $atts);
        extract($atts);
        if (!$accountID) {
            $accountID = get_option('gad_account_id');
        }
        if (!$start_date && !$end_date) {
            $start_date_ts = time() - (60 * 60 * 24 * 30); // 30 days in the past
            $start_date = date('Y-m-d', $start_date_ts);
            $end_date = date('Y-m-d');
        }
        if (!$start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($end_date . ' -30 day'));
        }
        if ($start_date && !$end_date) {
            $end_date = date('Y-m-d', strtotime($start_date . ' +30 day'));
        }
        return WADE_get_the_TopPage($accountID, $count, $start_date, $end_date, $isShowViewCount, $linkExtras);
    }

    public static function ga_keyword($atts)
    {
        require_once dirname(__FILE__) . "/../functions.php";
        $defaults = array(
            'accountID' => '',
            'path' => '',
            'start_date' => '',
            'end_date' => '',
            'maxcount' => 5,
            'minsearchcount' => 2,
            'minwordCount' => 2,
            'showcount' => 'false',
        );
        $atts = shortcode_atts($defaults, $atts);
        extract($atts);
        $isShowSearchCount = ($showcount == 'true');
        if (!$accountID) {
            $accountID = get_option('gad_account_id');
        }
        if (!$start_date && !$end_date) {
            $start_date_ts = time() - (60 * 60 * 24 * 30); // 30 days in the past
            $start_date = date('Y-m-d', $start_date_ts);
            $end_date = date('Y-m-d');
        }
        if (!$start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($end_date . ' -30 day'));
        }
        if ($start_date && !$end_date) {
            $end_date = date('Y-m-d', strtotime($start_date . ' +30 day'));
        }
        if ($path == '') {
            $path = $_SERVER['REQUEST_URI'];
        }
        return WADE_get_the_keyword($accountID, $path, $minwordCount, $minsearchcount, $isShowSearchCount, $maxcount, $start_date, $end_date);
    }

    public static function ga_pageview($atts)
    {
        require_once dirname(__FILE__) . "/../functions.php";
        $defaults = array(
            'accountID' => '',
            'path' => '',
            'start_date' => '',
            'end_date' => ''
        );
        $atts = shortcode_atts($defaults, $atts);
        extract($atts);
        if (!$accountID) {
            $accountID = get_option('gad_account_id');
        }
        if (!$start_date && !$end_date) {
            $start_date_ts = time() - (60 * 60 * 24 * 30); // 30 days in the past
            $start_date = date('Y-m-d', $start_date_ts);
            $end_date = date('Y-m-d');
        }
        if (!$start_date && $end_date) {
            $start_date = date('Y-m-d', strtotime($end_date . ' -30 day'));
        }
        if ($start_date && !$end_date) {
            $end_date = date('Y-m-d', strtotime($start_date . ' +30 day'));
        }
        if ($path == '') {
            $path = $_SERVER['REQUEST_URI'];
        }
        return WADE_get_the_pageview($accountID, $path, $start_date, $end_date);
    }

}