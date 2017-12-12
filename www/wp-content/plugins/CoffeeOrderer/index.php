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