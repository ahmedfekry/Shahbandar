<?php
// require '.././libs/Slim/Slim.php';
    require '../../vendor/autoload.php';
    require_once 'dbHelper.php';
    require_once '/models/registered_user.php';
    require_once 'passwordHash.php';
    $app = new \Slim\App;
    $registerdUser = new RegisteredUser();
/**
 * Database Helper Function templates
 */
/*
select(table name, where clause as associative array)
insert(table name, data as associative array, mandatory column names as array)
update(table name, column names as associative array, where clause as associative array, required columns as array)
delete(table name, where clause as array)
*/

// $rows = $db->select("customers_php",array());
// $rows = $db->select("customers_php",array('id'=>171));
// $rows = $db->insert("customers_php",array('name' => 'Ipsita Sahoo', 'email'=>'ipi@angularcode.com'), array('name', 'email'));
// $rows = $db->update("customers_php",array('name' => 'Ipsita Sahoo', 'email'=>'email'),array('id'=>'170'), array('name', 'email'));
// $rows = $db->delete("customers_php", array('name' => 'Ipsita Sahoo', 'id'=>'227'));
    // require_once '';
    require_once 'user.php';

$app->run();


?>
