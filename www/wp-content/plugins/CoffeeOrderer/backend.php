<?php
require_once 'Slim/Slim.php';
require_once 'SlimWpOptions.php';

\Slim\Slim::registerAutoloader();

const GET_COFFEE_TABLE_NAME = "getcoffeecatalog";
const TABLE_IDENTIFIER = "custom_";
const GET_COFFEE_ORDER_TABLE_NAME = "orders";

add_filter('rewrite_rules_array', function ($rules) {
    $new_rules = array(
        '('.get_option('slim_base_path','slim/api/').')' => 'index.php',
    );
    $rules = $new_rules + $rules;
    return $rules;
});

function GetPurchasableCoffeeTable() {
    global $wpdb, $table_prefix;
    
    // for some reason this is the only loop that doesn't crash wordpress :/

    $counter = 1;
    $tableName = $table_prefix . TABLE_IDENTIFIER . GET_COFFEE_TABLE_NAME;
    $targetPageId = 22;
    $tableRows = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM " . $tableName, null));
    $maxImageHeight = '200px'; 
    $maxImageWidth = '200px';
    $rowDividerCount = 0;
    $maxItemsPerRow = 2;

    while (true) {
        if ($counter > $tableRows) {
            break;
        }

        $getCoffeeCatalogId = $wpdb->get_var(
            $wpdb->prepare("SELECT id FROM " . $tableName . " WHERE id = " . $counter, null));
                    
        if ($rowDividerCount == 0) {
            echo '<div class="get-coffee-sections">';
        }

        echo '<figure>';
        echo 
        '<h2>' . $wpdb->get_var(
            $wpdb->prepare("SELECT title FROM " . $tableName . " WHERE id = " . $counter, null)
        ) . '</h2>' .
        '<a href="/index.php?page_id=' . $targetPageId . '&getCoffeeCatalogId=' . $getCoffeeCatalogId . '">' . 
        '<img src="' . 
        $wpdb->get_var(
            $wpdb->prepare("SELECT imageURL FROM " . $tableName . " WHERE id = " . $counter, null)
        ) .
        '" width="' . $maxImageHeight . '" height="' . $maxImageHeight . '" alt="Server error">' .
        '</a>'.
        '<figcaption>' . $wpdb->get_var(
            $wpdb->prepare("SELECT description FROM " . $tableName . " WHERE id = " . $counter, null)
        ) . '</figcaption>' .
        '<a class="custom-button" href="/index.php?page_id=' . $targetPageId . '&getCoffeeCatalogId=' . $getCoffeeCatalogId . '">Customize</a>';
        
        echo '</figure>';

        $rowDividerCount++;
        $counter++;

        if ($rowDividerCount == $maxItemsPerRow || $counter > $tableRows) {
            $rowDividerCount = 0;
            echo '</div>';
        }
    }
}

add_action('init', function () {
    if (strstr($_SERVER['REQUEST_URI'], get_option('slim_base_path','slim/api/'))) {
        $slim = new \Slim\Slim();
        do_action('slim_mapping',$slim);
        $slim->post('/slim/api/orders', function(){
            global $wpdb, $table_prefix;
            $postdata = file_get_contents("php://input");
            $oOrders = json_decode($postdata);

            $ssQL = "INSERT INTO " . $table_prefix . TABLE_IDENTIFIER . GET_COFFEE_ORDER_TABLE_NAME .
            "(firstName, lastName, email, street, city, province, country, postalCode, size, quantity, cardNumber, cardType, blend) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
            $stmt = $wpdb->prepare($ssQL, 
                array($oOrders->firstname, $oOrders->lastname, $oOrders->email, $oOrders->street, $oOrders->city, $oOrders->province, $oOrders->country, $oOrders->postalcode, $oOrders->size, $oOrders->quantity, $oOrders->cardnumber, $oOrders->cardtype, $oOrders->blend));
            $wpdb->query($stmt);

            // echo json_encode($oOrders);
        });
        $slim->run();
        exit;
    }
});