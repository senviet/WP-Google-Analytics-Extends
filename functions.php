<?php

function WADE_the_TopPage($accountId, $maxCount, $maxCount = 5, $start_date, $end_date, $isShowViewCount, $linkExtras)
{
    echo WADE_get_the_TopPage($accountId, $maxCount, $start_date, $end_date, $isShowViewCount, $linkExtras);
}

function WADE_the_keyword($accountId, $path = "/", $minwordCount = 1, $minSearchCount = 2, $isShowSearchCount = false, $maxCount, $start_date, $end_date, $exclude = array('(not provided)'))
{
    echo WADE_get_the_keyword($accountId, $path, $minwordCount, $minSearchCount, $isShowSearchCount, $start_date, $end_date, $exclude);
}

function WADE_get_the_keyword($accountId, $path = "/", $minwordCount = 1, $minSearchCount = 2, $isShowSearchCount = false, $maxCount = 5, $start_date, $end_date, $exclude = array('(not provided)'))
{
    $html = "";
    if (class_exists('GALib')) {
        if (get_option('gad_auth_token') == 'gad_see_oauth') {
            $ga = new GALib('oauth', NULL, get_option('gad_oauth_token'), get_option('gad_oauth_secret'), $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        } else {
            $ga = new GALib('client', get_option('gad_auth_token'), NULL, NULL, $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        }
        $populate_keyword = $ga->complex_report_query($start_date, $end_date, array('ga:keyword', 'ga:pagePath'), array('ga:organicSearches'), array('-ga:organicSearches'), array('ga:pagePath==' . $path));
        if ($ga->isError()) {
            return "{$ga->error_message}. <a href='" . admin_url("/options-general.php?page=google-analytics-dashboard/gad-admin-options.php") . "'>Goto Setting</a>";
        }
        if (count($populate_keyword) < 1) {
            return __("there are no page.", "wp-gade");
        }
        $count = 0;
        //str_word_count($str, 0, 'àáãç3')
        foreach ($populate_keyword as $index => $keyword) {
            if ($count == $maxCount) {
                break;
            }
            if ($keyword['children']['children']['ga:organicSearches'] < $minSearchCount) {
                break; // break because this array was sorted.
            }
            if (in_array($keyword['value'], $exclude)) {
                continue;
            }
            if (count(explode(' ', $keyword['value'])) < $minwordCount) {
                continue;
            }
            $count++;
            if ($isShowSearchCount) {
                $html .= sprintf('<span class="ga_keyword">%1$s<span class="ga_searchcount"> (%2$d)</span>,</span>', $keyword['value'], $keyword['children']['children']['ga:organicSearches']);
            } else {
                $html .= sprintf('<span class="ga_keyword">%1$s</span>,', $keyword['value']);

            }
        }
        return $html;
    } else {
        return "Need activate Yoast Analitycs Daskboard";
    }
}

function WADE_get_the_pageview($accountId, $path = "/", $start_date, $end_date)
{
    $html = "";
    if (class_exists('GALib')) {
        if (get_option('gad_auth_token') == 'gad_see_oauth') {
            $ga = new GALib('oauth', NULL, get_option('gad_oauth_token'), get_option('gad_oauth_secret'), $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        } else {
            $ga = new GALib('client', get_option('gad_auth_token'), NULL, NULL, $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        }
        $pageview = $ga->complex_report_query($start_date, $end_date, array('ga:pagePath'), array('ga:sessions'), array('-ga:sessions'), array('ga:pagePath==' . $path));
        if ($ga->isError()) {
            return "{$ga->error_message}. <a href='" . admin_url("/options-general.php?page=google-analytics-dashboard/gad-admin-options.php") . "'>Goto Setting</a>";
        }
        $html = sprintf('<span class="ga_pageview">%1$s</span>', $pageview[0]['children']['ga:sessions']);
        return $html;
    } else {
        return "Need activate Yoast Analitycs Daskboard";
    }
}

function WADE_get_the_TopPage($accountId, $maxCount, $start_date, $end_date, $isShowViewCount, $linkExtras, $exclude = array('/', '/index.php'))
{
    $html = "";
    if (class_exists('GALib')) {
        if (get_option('gad_auth_token') == 'gad_see_oauth') {
            $ga = new GALib('oauth', NULL, get_option('gad_oauth_token'), get_option('gad_oauth_secret'), $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        } else {
            $ga = new GALib('client', get_option('gad_auth_token'), NULL, NULL, $accountId, get_option('gad_cache_timeout') !== false ? get_option('gad_cache_timeout') : 60);
        }
        $populate_pages = $ga->complex_report_query($start_date, $end_date, array('ga:pagePath', 'ga:pageTitle'), array('ga:pageviews'), array('-ga:pageviews'), array());
        if ($ga->isError()) {
            return "Error while get account info. Please check Wordpress Analytic Dasboard's Setting one more time. <a href='" . admin_url("/options-general.php?page=google-analytics-dashboard/gad-admin-options.php") . "'>Goto Setting</a>";
        }
        if (count($populate_pages) < 1) {
            return __("there are no page.", "wp-gade");
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