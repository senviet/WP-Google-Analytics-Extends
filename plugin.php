<?php
/*
Plugin Name: Google Analytics Dashboard Extends
Version: 1.0.0
Description: Add some feature for Google Analytics Dashboard
Author: Vô Minh
Plugin URI: http://laptrinh.senviet.org
*/

include dirname(__FILE__) . '/scb/load.php';
include_once dirname(__FILE__) . '/core.php';
function _GADE_init()
{
    GADE_Core::instance()->init();
}

scb_init('_GADE_init');
