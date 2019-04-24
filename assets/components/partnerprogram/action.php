<?php
// CHECK BEFORE BUILD
$dev = 1;

if (empty($_REQUEST['action']) && empty($_REQUEST['pp_action'])) {
	die('Access denied');
}
if (!empty($_REQUEST['action'])) {
	$_REQUEST['pp_action'] = $_REQUEST['action'];
}
/** @noinspection PhpIncludeInspection */
if($dev) {
	require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';
}else{
	require dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
}