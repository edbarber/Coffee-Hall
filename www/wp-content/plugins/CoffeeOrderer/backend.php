<?php
require_once 'Slim/Slim.php';
require_once 'SlimWpOptions.php';

\Slim\Slim::registerAutoloader();

const GET_COFFEE_TABLE_NAME = "getcoffeecatalog";
const TABLE_IDENTIFIER = "custom_";

add_filter('rewrite_rules_array', function ($rules) {
    $new_rules = array(
        '('.get_option('slim_base_path','slim/api/').')' => 'index.php',
    );
    $rules = $new_rules + $rules;
    return $rules;
});

function GetPurchasableCoffeeTable() {
    $slim = new \Slim\Slim();
    //do_action('slim_mapping',$slim);
    global $wpdb, $table_prefix;
    echo($table_prefix . TABLE_IDENTIFIER . GET_COFFEE_TABLE_NAME);
    echo json_encode(
        $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM " . 
            $table_prefix . TABLE_IDENTIFIER . GET_COFFEE_TABLE_NAME, null)));
    //$slim->run();
    exit;
}