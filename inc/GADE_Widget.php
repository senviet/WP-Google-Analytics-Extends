<?php

/**
 * Project : wp-weather
 * User: thuytien
 * Date: 9/2/2014
 * Time: 2:57 PM
 */
class GADE_Toppage_Widget extends scbWidget
{
    private static $isAdded;
protected $defaults = array(
    'title' => 'GAD - Top page',
    'count' => 5,
    'rangeType' => '7',
    'fromdate' => "2014/09/1",
    'todate' => "2014/09/10",
    'isShowViewCount' => true,
    'isTargetBlank' => false,
    'isExternalNofollow' => false
);

    function __construct()
    {
        parent::__construct('GADE_Toppage_widget', 'GADE - Top page', array(
            'description' => 'Display top page from google analitycs'
        ));
    }

function form($instance)
{
    if (class_exists('GALib')) {
        if (get_option('gad_auth_token') == 'gad_see_oauth') {
            $ga = new GALib('oauth', NULL, get_option('gad_oauth_token'), get_option('gad_oauth_secret'), '', get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        } else {
            $ga = new GALib('client', get_option('gad_auth_token'), NULL, NULL, '', get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        }
        $account_hash = $ga->account_query();
        if($ga->isError())
        {
            echo "Error while get account info. Please check Wordpress Analytic Dasboard's Setting one more time. <a href='".admin_url("/options-general.php?page=google-analytics-dashboard/gad-admin-options.php")."'>Goto Setting</a>";
            return;
        }
        $this->formScript();
        self::$isAdded = true;
        echo html('p', $this->input(array(
            'type' => 'text',
            'name' => 'title',
            'desc' => __('Title:', 'wp-GADE')
        ), $instance));

        echo html('p', $this->input(array(
            'type' => 'select',
            'name' => 'accountid',
            'value' => $account_hash,
            'desc' => __('Account ID:', 'wp-GADE'),
            'extra' => array('class' => 'accountid widefat')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'number',
            'name' => 'count',
            'extra' => array('class' => 'widefat'),
            'desc' => __('Count to show:', 'wp-GADE')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'select',
            'name' => 'rangeType',
            'value' => array(
                "-1" => __("Custom static range", "wp-gade"),
                "90" => __("90 days ago ", "wp-gade"),
                "30" => __("30 days ago ", "wp-gade"),
                "7" => __("7 days ago ", "wp-gade"),
                "1" => __("a days ago ", "wp-gade")),
            'desc' => __('Date range :', 'wp-GADE'),
            'extra' => array('class' => 'rangeType widefat')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'date',
            'name' => 'fromdate',
            'extra' => array('class' => 'widefat'),
            'desc' => __('From date:', 'wp-GADE'),
            'extra' => array('class' => 'fromdate widefat')
        ), $instance));

        echo html('p', $this->input(array(
            'type' => 'date',
            'name' => 'todate',
            'extra' => array('class' => 'widefat'),
            'desc' => __('To date:', 'wp-GADE'),
            'extra' => array('class' => 'todate widefat')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'checkbox',
            'name' => 'isShowViewCount',
            'desc' => __('Display view count', 'wp-GADE')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'checkbox',
            'name' => 'isTargetBlank',
            'desc' => __('Open on new Tab', 'wp-GADE')
        ), $instance));
        echo html('p', $this->input(array(
            'type' => 'checkbox',
            'name' => 'isExternalNofollow',
            'desc' => __('Is external, nofollow ?', 'wp-GADE')
        ), $instance));
    } else {
        echo html('p', "you must enable Wordpress Analytics Dashboard first.");
    }
}

function content($instance)
{
    require_once dirname(__FILE__) . "/../functions.php";
    $linkExtras = "";
    if ($instance['isTargetBlank']) {
        $linkExtras .= 'target="_blank"';
    }

    if ($instance['isTargetBlank']) {
        $linkExtras .= ' rel="external nofollow"';
    }
    if($instance['rangeType'] != -1)
    {
        $start_date_ts = time() - ( 60 * 60 * 24 * $instance['rangeType'] ); // 30 days in the past
        $instance['fromdate'] = date( 'Y-m-d', $start_date_ts );
        $instance['todate']   = date( 'Y-m-d' );
    }
    WADE_the_TopPage($instance['accountid'], $instance['count'], $instance['fromdate'], $instance['todate'], $instance['isShowViewCount'], $linkExtras);
}

    function formScript()
    {
        if(self::$isAdded)
            return;
        ?>
        <script>
            jQuery(document).ready(function($){
                $('.rangeType').change(function()
                {
                    if($(this).val() != "-1") {
                        $(this).parent().find('.fromdate').prop('disabled', true);
                    }
                });
            });
        </script>
        <?php
    }
} 