<?php
require_once dirname(__FILE__)."/inc/GADE_Shortcode.php";
class GADE_Core
{
    private $rulers;
    private static $instance;

    function __construct()
    {

    }

    public static function instance()
    {
        if (self::$instance) {
            return self::$instance;
        } else {
            self::$instance = new self();
            return self::$instance;
        }
    }

    public function init()
    {
        $this->registerWidget();
        GADE_Shortcode::init();
    }
    public function registerWidget()
    {
        require_once dirname(__FILE__).'/inc/GADE_Widget.php';
        scbWidget::init( 'GADE_Toppage_Widget' );
    }
}