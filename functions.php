<?php

function the_TopPage($accountId ,$maxCount, $start_date, $end_date, $isShowViewCount, $linkExtras)
{
    echo get_the_TopPage($accountId ,$maxCount, $start_date, $end_date, $isShowViewCount, $linkExtras);
}

function get_the_TopPage($accountId ,$maxCount, $start_date, $end_date, $isShowViewCount, $linkExtras)
{
    $html = "";
    $exclude = apply_filters('gade_toppage_exclude', array('/', '/index.php'));
    if (class_exists('GALib')) {
        if (get_option('gad_auth_token') == 'gad_see_oauth') {
            $ga = new GALib('oauth', NULL, get_option('gad_oauth_token'), get_option('gad_oauth_secret'), $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        } else {
            $ga = new GALib('client', get_option('gad_auth_token'), NULL, NULL, $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        }
        $populate_pages = $ga->pages_for_date_period($start_date, $end_date);
        if($ga->isError())
        {
            return "Error while get account info. Please check Wordpress Analytic Dasboard's Setting one more time. <a href='".admin_url("/options-general.php?page=google-analytics-dashboard/gad-admin-options.php")."'>Goto Setting</a>";
        }
        $count = 0;
        $html .= '<ul class="gade_toppage">';
        foreach ($populate_pages as $index => $page) {
            if ($count == $maxCount) {
                break;
            }
            if (in_array($page['value'], $exclude)) {
                continue;
            }
            $count++;
            if ($isShowViewCount) {
                $html .= sprintf('<li><a href="%1$s" %4$s >%2$s <span class="gade_viewcount">(%3$s)</span></a></li>', $page['value'], $page['children']['value'], $page['children']['children']['ga:pageviews'], $linkExtras);
            } else {
                $html .= sprintf('<li><a href="%1$s" %3$s >%2$s</a></li>', $page['value'], $page['children']['value'], $linkExtras);

            }
        }
        $html .= "</ul>";
        return $html;
    } else {
        return "Need activate Yoast Analitycs Daskboard";
    }
}