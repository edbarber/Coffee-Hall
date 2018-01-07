<?php
// Plugin Name: Coffee Orderer
// Description: Use this plugin to generate the Get Coffee page and place an order on the Customize Coffee Page.

include_once "backend.php";

function orderer_shortcodes_init()
{
    function orderer_shortcode_getcoffee()
    {
        GetPurchasableCoffeeTable();
    }
    add_shortcode('generate-get-coffee-page', 'orderer_shortcode_getcoffee');

    wp_register_style('style_style', plugins_url('style.css', __FILE__), true);
    wp_enqueue_style('style_style');
}
add_action('init', 'orderer_shortcodes_init');

add_action('init', function() {
    add_shortcode('customize-coffee-form',function($atts = [], $content = null){
        $content .= "<script>var atts = " .
        json_encode($atts) .
        "</script>";
        $content .= file_get_contents(dirname(__FILE__) . "/order.php");
        return $content;
    });
    wp_register_style('style_style', plugins_url('style.css', __FILE__), true);
    wp_enqueue_style('style_style');
    
    wp_register_script('angular_script', 
    "https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.js", 
    true);
    
    wp_enqueue_script('angular_script');
    wp_register_script('coffeecontroller_script', 
    plugins_url('indexcontroller.js', __FILE__), 
    true);

    wp_enqueue_script('coffeecontroller_script');
   
});


